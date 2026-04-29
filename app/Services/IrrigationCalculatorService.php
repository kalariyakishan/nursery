<?php

namespace App\Services;

class IrrigationCalculatorService
{
    /**
     * Generate main and sub pipelines based on points and water source
     */
    public function generatePipelines(array $points, array $waterSource, array $customMainPipeline = [], string $routing = 'auto'): array
    {
        if (empty($points)) {
            return [
                'main_pipeline' => [],
                'sub_pipelines' => [],
                'main_length' => 0,
                'sub_length' => 0,
            ];
        }

        // Group points into rows (by approximate latitude)
        $rows = [];
        // Define a small tolerance for grouping by latitude due to floating point precision
        $tolerance = 0.00001; 
        
        foreach ($points as $point) {
            $matchedRow = null;
            foreach ($rows as $lat => &$rowPoints) {
                if (abs((float)$lat - $point['lat']) < $tolerance) {
                    $matchedRow = $lat;
                    $rowPoints[] = $point;
                    break;
                }
            }
            
            if ($matchedRow === null) {
                $rows[(string)$point['lat']] = [$point];
            }
        }

        $mainPipelinePaths = [];
        $subPipelines = [];
        $mainLength = 0;
        $subLength = 0;

        // Sort each row by longitude
        foreach ($rows as &$rowPoints) {
            usort($rowPoints, function($a, $b) {
                return $a['lng'] <=> $b['lng'];
            });
        }
        
        // Sort rows by latitude numerically
        uksort($rows, function($a, $b) {
            return (float)$a <=> (float)$b;
        });
        
        if ($routing === 'custom' && !empty($customMainPipeline)) {
            // Main pipe is exactly what the user drew
            $mainPipelinePaths[] = $customMainPipeline;
            for ($i = 0; $i < count($customMainPipeline) - 1; $i++) {
                $mainLength += $this->calculateDistance($customMainPipeline[$i], $customMainPipeline[$i+1]);
            }
            
            // Connect each row to the closest point on the custom main pipeline
            foreach ($rows as $rowPoints) {
                $leftPlant = $rowPoints[0];
                $rightPlant = end($rowPoints);
                
                // Find closest point on main pipeline to leftPlant and rightPlant
                $closestToLeft = $this->closestPointOnPolyline($leftPlant, $customMainPipeline);
                $closestToRight = $this->closestPointOnPolyline($rightPlant, $customMainPipeline);
                
                $distLeft = $this->calculateDistance($leftPlant, $closestToLeft);
                $distRight = $this->calculateDistance($rightPlant, $closestToRight);
                
                if ($distLeft < $distRight) {
                    $startPlant = $leftPlant;
                    $endPlant = $rightPlant;
                    $connectionPoint = $closestToLeft;
                } else {
                    $startPlant = $rightPlant;
                    $endPlant = $leftPlant;
                    $connectionPoint = $closestToRight;
                }
                
                // Sub pipeline goes from connectionPoint -> startPlant -> endPlant
                $subPath = [$connectionPoint, $startPlant, $endPlant];
                $subPipelines[] = $subPath;
                $subLength += min($distLeft, $distRight);
                $subLength += $this->calculateDistance($startPlant, $endPlant);
            }
        } else {
            // AUTO ROUTING (Hugs the field border)
            $leftSum = 0;
            $rightSum = 0;
            $rowCount = count($rows);
            
            foreach ($rows as $rowPoints) {
                $leftSum += $rowPoints[0]['lng'];
                $rightSum += end($rowPoints)['lng'];
            }
            
            $avgLeftLng = $leftSum / $rowCount;
            $avgRightLng = $rightSum / $rowCount;
            
            $waterSourceLng = $waterSource['lng'] ?? $avgLeftLng;
            $isLeftSided = abs($waterSourceLng - $avgLeftLng) < abs($waterSourceLng - $avgRightLng);

            $connectionPlants = [];
            foreach ($rows as $latStr => $rowPoints) {
                $connectionPlants[] = $isLeftSided ? $rowPoints[0] : end($rowPoints);
            }

            // Find the edge plant closest to the water source
            $closestIdx = 0;
            $minDist = PHP_FLOAT_MAX;
            foreach ($connectionPlants as $idx => $plant) {
                $dist = $this->calculateDistance($waterSource, $plant);
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $closestIdx = $idx;
                }
            }

            // 1. Pipe from water source directly to the closest edge plant
            $mainPipelinePaths[] = [$waterSource, $connectionPlants[$closestIdx]];

            // 2. The main header pipe connecting all edge plants in sequence
            $mainPipelinePaths[] = $connectionPlants;

            // Calculate main pipeline length
            $mainLength = $this->calculateDistance($waterSource, $connectionPlants[$closestIdx]);
            for ($i = 0; $i < count($connectionPlants) - 1; $i++) {
                $mainLength += $this->calculateDistance($connectionPlants[$i], $connectionPlants[$i+1]);
            }

            // Generate sub pipelines
            foreach ($rows as $latStr => $rowPoints) {
                $startPlant = $isLeftSided ? $rowPoints[0] : end($rowPoints);
                $endPlant = $isLeftSided ? end($rowPoints) : $rowPoints[0];
                
                $subPath = [
                    $startPlant,
                    $endPlant
                ];
                $subPipelines[] = $subPath;
                
                $subLength += $this->calculateDistance($subPath[0], $subPath[1]);
            }
        }

        return [
            'main_pipeline' => $mainPipelinePaths,
            'sub_pipelines' => $subPipelines,
            'main_length' => $mainLength,
            'sub_length' => $subLength,
        ];
    }

    private function closestPointOnPolyline($p, $polyline) {
        $minDist = PHP_FLOAT_MAX;
        $closestPoint = $polyline[0];
        
        for ($i = 0; $i < count($polyline) - 1; $i++) {
            $a = $polyline[$i];
            $b = $polyline[$i+1];
            
            $pt = $this->closestPointOnSegment($p, $a, $b);
            $dx = $p['lng'] - $pt['lng'];
            $dy = $p['lat'] - $pt['lat'];
            $distSq = $dx*$dx + $dy*$dy;
            
            if ($distSq < $minDist) {
                $minDist = $distSq;
                $closestPoint = $pt;
            }
        }
        return $closestPoint;
    }
    
    private function closestPointOnSegment($p, $a, $b) {
        $dx = $b['lng'] - $a['lng'];
        $dy = $b['lat'] - $a['lat'];
        
        if ($dx == 0 && $dy == 0) return $a;
        
        $t = (($p['lng'] - $a['lng']) * $dx + ($p['lat'] - $a['lat']) * $dy) / ($dx * $dx + $dy * $dy);
        $t = max(0, min(1, $t));
        
        return [
            'lat' => $a['lat'] + $t * $dy,
            'lng' => $a['lng'] + $t * $dx
        ];
    }

    /**
     * Calculate distance between two points in meters (Haversine formula)
     */
    private function calculateDistance(array $p1, array $p2): float
    {
        $earthRadius = 6371000; // meters

        $lat1 = deg2rad($p1['lat']);
        $lat2 = deg2rad($p2['lat']);
        $latDelta = deg2rad($p2['lat'] - $p1['lat']);
        $lngDelta = deg2rad($p2['lng'] - $p1['lng']);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($lat1) * cos($lat2) *
             sin($lngDelta / 2) * sin($lngDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
