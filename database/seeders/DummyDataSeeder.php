<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worker;
use App\Models\LabourEntry;
use App\Models\LabourEntryDetail;
use App\Models\Advance;
use App\Models\RojmelEntry;
use App\Models\DailyBalance;
use App\Services\RojmelService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Workers
        $workerNames = [
            'રમેશભાઈ પરમાર',
            'જીતુભાઈ પ્રજાપતિ',
            'મનસુખભાઈ વાઘેલા',
            'હિંમતભાઈ ચૌહાણ',
            'પરેશભાઈ રાવળ',
            'અશોકભાઈ દરજી',
            'કાનજીભાઈ ઠાકોર',
            'ભરતભાઈ મેવાડા',
            'જયેશભાઈ સોલંકી',
            'વિઠ્ઠલભાઈ પટેલ'
        ];

        foreach ($workerNames as $name) {
            Worker::firstOrCreate(
                ['name' => $name],
                ['phone' => '98' . rand(10000000, 99999999), 'default_wage' => rand(300, 450)]
            );
        }

        $workers = Worker::all();
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now();

        $rojmelService = new RojmelService();

        // Optional: Clear existing data for these tables if needed
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // LabourEntryDetail::truncate();
        // LabourEntry::truncate();
        // Advance::truncate();
        // RojmelEntry::truncate();
        // DailyBalance::truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $processDateStr = $date->format('Y-m-d');
            $isSunday = $date->isSunday();

            // 2. Labour Attendance
            if (!$isSunday || rand(1, 10) > 8) { // Few workers work on Sunday
                $labourEntry = LabourEntry::updateOrCreate(
                    ['date' => $processDateStr],
                    ['total_workers' => 0, 'total_amount' => 0]
                );

                $dailyTotalWorkers = 0;
                $dailyTotalWage = 0;

                foreach ($workers as $worker) {
                    $presence = rand(1, 10);
                    if ($presence > 2) { // 80% present
                        $attendanceType = ($presence == 3) ? 'half' : 'full';
                        $wage = ($attendanceType == 'full') ? $worker->default_wage : ($worker->default_wage / 2);
                        
                        LabourEntryDetail::updateOrCreate(
                            [
                                'labour_entry_id' => $labourEntry->id,
                                'worker_id' => $worker->id
                            ],
                            [
                                'work_type' => ['વાવણી', 'પાણી પાવા', 'નીંદણ', 'જંતુનાશક', 'પેકિંગ'][rand(0, 4)],
                                'attendance_type' => $attendanceType,
                                'wage_amount' => $wage,
                                'notes' => ''
                            ]
                        );
                        $dailyTotalWorkers++;
                        $dailyTotalWage += $wage;
                    }
                }

                $labourEntry->update([
                    'total_workers' => $dailyTotalWorkers,
                    'total_amount' => $dailyTotalWage
                ]);

                // 3. Labour Payments (Javak) - Weekly on Mondays
                if ($date->isMonday()) {
                    RojmelEntry::create([
                        'date' => $processDateStr,
                        'type' => 'javak',
                        'amount' => $dailyTotalWage * 6,
                        'category' => 'મજૂરી',
                        'description' => 'ગયા અઠવાડિયાનો મજૂરી પગાર'
                    ]);
                }
            }

            // 4. Advances (Upad)
            if (rand(1, 25) == 1) { 
                $worker = $workers->random();
                $amount = rand(5, 20) * 100;
                Advance::create([
                    'worker_id' => $worker->id,
                    'date' => $processDateStr,
                    'amount' => $amount,
                    'note' => 'જરૂરિયાત માટે ઉપાડ'
                ]);

                // Add to Rojmel Javak
                RojmelEntry::create([
                    'date' => $processDateStr,
                    'type' => 'javak',
                    'amount' => $amount,
                    'category' => 'ઉપાડ',
                    'description' => $worker->name . ' ને ઉપાડ આપેલ'
                ]);
            }

            // 5. General Expenses (Javak)
            RojmelEntry::create([
                'date' => $processDateStr,
                'type' => 'javak',
                'amount' => rand(50, 150),
                'category' => 'ટીફીન-ચા-નાસ્તો',
                'description' => 'ચા-નાસ્તો'
            ]);

            if ($date->day == 10) {
                RojmelEntry::create([
                    'date' => $processDateStr,
                    'type' => 'javak',
                    'amount' => rand(2000, 5000),
                    'category' => 'ખાતર-દવા',
                    'description' => 'ખાતર અને દવાની ખરીદી'
                ]);
            }

            // 6. Income (Avak)
            if ($date->isTuesday() || $date->isFriday()) {
                RojmelEntry::create([
                    'date' => $processDateStr,
                    'type' => 'avak',
                    'amount' => rand(100, 500) * 100,
                    'category' => 'રોકડ વેચાણ',
                    'description' => 'છોડનું રોકડ વેચાણ'
                ]);
            }

            if (rand(1, 15) == 1) {
                RojmelEntry::create([
                    'date' => $processDateStr,
                    'type' => 'avak',
                    'amount' => rand(50, 200) * 10,
                    'category' => 'અન્ય આવક',
                    'description' => 'પરચૂરણ આવક'
                ]);
            }
        }

        // 7. Recalculate all balances
        $rojmelService->recalculateFrom($startDate->format('Y-m-d'));
    }
}
