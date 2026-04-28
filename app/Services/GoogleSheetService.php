<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
use App\Models\GoogleIntegration;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleSheetService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(route('google.sync.callback'));
        $this->client->addScope(Sheets::SPREADSHEETS);
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->addScope('email');
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function connect()
    {
        return redirect()->away($this->client->createAuthUrl());
    }

    public function refreshToken(GoogleIntegration $integration)
    {
        $this->client->setAccessToken([
            'access_token' => $integration->access_token,
            'refresh_token' => $integration->refresh_token,
            'expires_in' => Carbon::now()->diffInSeconds($integration->expires_at),
        ]);

        if ($this->client->isAccessTokenExpired() && $integration->refresh_token) {
            try {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($integration->refresh_token);
                
                if (!isset($newToken['error'])) {
                    $integration->update([
                        'access_token' => $newToken['access_token'],
                        'expires_at' => Carbon::now()->addSeconds($newToken['expires_in']),
                    ]);
                } else {
                    Log::error('Google token refresh failed', ['error' => $newToken]);
                }
            } catch (\Exception $e) {
                Log::channel('google_sync')->error('Error refreshing token: ' . $e->getMessage(), [
                    'user_id' => $integration->user_id,
                ]);
                throw $e;
            }
        }

        return $this->client;
    }

    public function createSheet(GoogleIntegration $integration, string $type, string $title)
    {
        $this->refreshToken($integration);
        $service = new Sheets($this->client);
        
        $spreadsheet = new Sheets\Spreadsheet([
            'properties' => [
                'title' => $title . " - " . config('app.name') . " - " . Carbon::now()->format('Y-m-d')
            ]
        ]);

        try {
            $spreadsheet = $service->spreadsheets->create($spreadsheet, [
                'fields' => 'spreadsheetId,spreadsheetUrl'
            ]);
            
            $integration->sheets()->updateOrCreate(
                ['sheet_type' => $type],
                [
                    'sheet_id' => $spreadsheet->spreadsheetId,
                    'sheet_url' => $spreadsheet->spreadsheetUrl,
                ]
            );

            return $spreadsheet;
        } catch (\Exception $e) {
            Log::channel('google_sync')->error('Failed to create sheet: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sync(GoogleIntegration $integration, string $type, string $title, array $data)
    {
        try {
            $sheet = $integration->sheets()->where('sheet_type', $type)->first();
            
            if (!$sheet || !$sheet->sheet_id) {
                $spreadsheet = $this->createSheet($integration, $type, $title);
                $sheetId = $spreadsheet->spreadsheetId;
            } else {
                $sheetId = $sheet->sheet_id;
            }
            
            $this->updateSheetValues($integration, $sheetId, 'Sheet1', $data);
            
            if ($sheet) {
                $sheet->update(['last_synced_at' => now()]);
            }

            return true;
        } catch (\Exception $e) {
            Log::channel('google_sync')->error("Sync failed for type {$type}: " . $e->getMessage());
            return false;
        }
    }

    public function syncFile(GoogleIntegration $integration, string $type, string $title, string $fileContent)
    {
        try {
            $sheet = $integration->sheets()->where('sheet_type', $type)->first();
            
            if (!$sheet || !$sheet->sheet_id) {
                $spreadsheet = $this->createSheet($integration, $type, $title);
                $fileId = $spreadsheet->spreadsheetId;
            } else {
                $fileId = $sheet->sheet_id;
            }

            $this->refreshToken($integration);
            $driveService = new Drive($this->client);

            // Update existing file with Excel content
            $fileMetadata = new Drive\DriveFile([
                'mimeType' => 'application/vnd.google-apps.spreadsheet'
            ]);

            $driveService->files->update($fileId, $fileMetadata, [
                'data' => $fileContent,
                'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'uploadType' => 'multipart'
            ]);

            if ($sheet) {
                $sheet->update(['last_synced_at' => now()]);
            }

            return true;
        } catch (\Exception $e) {
            Log::channel('google_sync')->error("File sync failed for type {$type}: " . $e->getMessage());
            return false;
        }
    }

    protected function updateSheetValues(GoogleIntegration $integration, string $sheetId, string $range, array $data)
    {
        $this->refreshToken($integration);
        $service = new Sheets($this->client);

        try {
            // Aggressive Sanitization
            $sanitizedData = [];
            foreach ($data as $row) {
                $newRow = [];
                // Convert row to array if it's an object/collection
                $rowArray = is_array($row) ? $row : (is_object($row) && method_exists($row, 'toArray') ? $row->toArray() : (array)$row);
                
                foreach ($rowArray as $val) {
                    // Convert everything to string/number, handle nulls as empty strings
                    if ($val === null) {
                        $newRow[] = "";
                    } elseif (is_scalar($val)) {
                        $newRow[] = $val;
                    } else {
                        $newRow[] = (string)$val;
                    }
                }
                $sanitizedData[] = array_values($newRow);
            }
            
            // Final safety net to ensure pure indexed array structure for JSON serialization
            $sanitizedData = json_decode(json_encode(array_values($sanitizedData)), true);

            // Clear range first
            $clearBody = new Sheets\ClearValuesRequest();
            $service->spreadsheets_values->clear($sheetId, $range, $clearBody);

            // Update values
            $body = new Sheets\ValueRange(['values' => $sanitizedData]);
            $params = ['valueInputOption' => 'USER_ENTERED'];
            $service->spreadsheets_values->update($sheetId, $range . '!A1', $body, $params);
        } catch (\Exception $e) {
            Log::channel('google_sync')->error("Failed to update values for range {$range}: " . $e->getMessage());
            throw $e;
        }
    }
}
