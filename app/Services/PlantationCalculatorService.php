<?php

namespace App\Services;

class PlantationCalculatorService
{
    /**
     * Calculate the area of a polygon in square meters
     */
    public function calculateArea(array $polygon): float
    {
        $area = 0;
        $numPoints = count($polygon);
        
        if ($numPoints < 3) return 0;
        
        // Convert lat/lng to meters for area calculation using Haversine approximation
        // Earth radius in meters
        $R = 6378137;
        
        // Approximate area using spherical polygon area formula
        $area = 0;
        for ($i = 0; $i < $numPoints; $i++) {
            $p1 = $polygon[$i];
            $p2 = $polygon[($i + 1) % $numPoints];
            
            // Convert to radians
            $lat1 = deg2rad($p1['lat']);
            $lng1 = deg2rad($p1['lng']);
            $lat2 = deg2rad($p2['lat']);
            $lng2 = deg2rad($p2['lng']);
            
            $area += ($lng2 - $lng1) * (2 + sin($lat1) + sin($lat2));
        }
        
        $area = abs($area * $R * $R / 2.0);
        
        return $area;
    }

    /**
     * Generate plant points using a grid layout
     */
    public function generateGridPoints(array $polygon, float $rowSpacing, float $plantSpacing): array
    {
        return $this->generatePoints($polygon, $rowSpacing, $plantSpacing, 'grid');
    }

    /**
     * Generate plant points using a zig-zag layout
     */
    public function generateZigZagPoints(array $polygon, float $rowSpacing, float $plantSpacing): array
    {
        return $this->generatePoints($polygon, $rowSpacing, $plantSpacing, 'zigzag');
    }

    /**
     * Generate plant points using a random layout
     */
    public function generateRandomPoints(array $polygon, float $rowSpacing, float $plantSpacing): array
    {
        // For random, we just generate points and ensure they are at least $plantSpacing apart
        $bounds = $this->getPolygonBounds($polygon);
        $points = [];
        
        // Approximate grid size to limit iterations
        $area = $this->calculateArea($polygon);
        $maxPoints = (int)($area / ($rowSpacing * $plantSpacing)) * 1.5;
        $attempts = 0;
        
        while (count($points) < $maxPoints && $attempts < $maxPoints * 10) {
            $lat = $bounds['minLat'] + mt_rand(0, 1000000) / 1000000 * ($bounds['maxLat'] - $bounds['minLat']);
            $lng = $bounds['minLng'] + mt_rand(0, 1000000) / 1000000 * ($bounds['maxLng'] - $bounds['minLng']);
            $point = ['lat' => $lat, 'lng' => $lng];
            
            if ($this->isPointInsidePolygon($point, $polygon)) {
                // Check distance to other points
                $tooClose = false;
                foreach ($points as $existingPoint) {
                    if ($this->calculateDistance($point, $existingPoint) < $plantSpacing) {
                        $tooClose = true;
                        break;
                    }
                }
                
                if (!$tooClose) {
                    $points[] = $point;
                }
            }
            $attempts++;
        }
        
        return $points;
    }

    /**
     * General method to generate points based on layout
     */
    private function generatePoints(array $polygon, float $rowSpacing, float $plantSpacing, string $layout): array
    {
        if (count($polygon) < 3) return [];

        $bounds = $this->getPolygonBounds($polygon);
        $points = [];

        // Determine orientation based on the longest side of bounding box
        $latDist = $this->calculateDistance(['lat' => $bounds['minLat'], 'lng' => $bounds['minLng']], ['lat' => $bounds['maxLat'], 'lng' => $bounds['minLng']]);
        $lngDist = $this->calculateDistance(['lat' => $bounds['minLat'], 'lng' => $bounds['minLng']], ['lat' => $bounds['minLat'], 'lng' => $bounds['maxLng']]);

        // Calculate step sizes in degrees (approximation)
        $latStep = ($bounds['maxLat'] - $bounds['minLat']) / ($latDist / $rowSpacing);
        $lngStep = ($bounds['maxLng'] - $bounds['minLng']) / ($lngDist / $plantSpacing);

        $rowIdx = 0;
        for ($lat = $bounds['minLat']; $lat <= $bounds['maxLat']; $lat += $latStep) {
            $startLng = $bounds['minLng'];
            
            if ($layout === 'zigzag' && $rowIdx % 2 !== 0) {
                $startLng += $lngStep / 2;
            }

            for ($lng = $startLng; $lng <= $bounds['maxLng']; $lng += $lngStep) {
                $point = ['lat' => $lat, 'lng' => $lng];
                if ($this->isPointInsidePolygon($point, $polygon)) {
                    $points[] = $point;
                }
            }
            $rowIdx++;
        }

        return $points;
    }

    /**
     * Check if a point is inside a polygon using ray casting algorithm
     */
    public function isPointInsidePolygon(array $point, array $polygon): bool
    {
        $x = $point['lng'];
        $y = $point['lat'];
        $inside = false;

        $j = count($polygon) - 1;
        for ($i = 0; $i < count($polygon); $i++) {
            $xi = $polygon[$i]['lng'];
            $yi = $polygon[$i]['lat'];
            $xj = $polygon[$j]['lng'];
            $yj = $polygon[$j]['lat'];

            $intersect = (($yi > $y) != ($yj > $y)) &&
                ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            
            if ($intersect) {
                $inside = !$inside;
            }
            $j = $i;
        }

        return $inside;
    }

    /**
     * Get bounding box of a polygon
     */
    private function getPolygonBounds(array $polygon): array
    {
        $lats = array_column($polygon, 'lat');
        $lngs = array_column($polygon, 'lng');

        return [
            'minLat' => min($lats),
            'maxLat' => max($lats),
            'minLng' => min($lngs),
            'maxLng' => max($lngs),
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
