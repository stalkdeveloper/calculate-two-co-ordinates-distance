<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Iroid\LaravelHaversine\Haversine;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class DistanceController extends Controller
{
    /* Method 1: Haversine Package */
    public function haversinePackage()
    {
        $lat1 = 40.7128; // New York
        $lon1 = -74.0060;
        $lat2 = 34.0522; // Los Angeles
        $lon2 = -118.2437;

        $km = Haversine::distance($lat1, $lon1, $lat2, $lon2);
        $miles = Haversine::distance($lat1, $lon1, $lat2, $lon2, 'miles');

        return response()->json([
            'method' => 'Haversine Package',
            'km' => $km,
            'miles' => $miles
        ]);
    }

    /* Method 2: Custom Spherical Law of Cosines */
    public function sphericalLaw(Request $request)
    {
        $lat1 = $request->lat1 ?? 40.7128;
        $lon1 = $request->lon1 ?? -74.0060;
        $lat2 = $request->lat2 ?? 34.0522;
        $lon2 = $request->lon2 ?? -118.2437;

        $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);

        return response()->json([
            'method' => 'Spherical Law of Cosines',
            'km' => $distance['km'],
            'miles' => $distance['miles']
        ]);
    }

    /* Method 3: MySQL ST_Distance_Sphere */
    // public function mysqlDistance(Request $request)
    // {
    //     $lat = $request->lat ?? 40.7128;
    //     $lon = $request->lon ?? -74.0060;

    //     $users = User::selectRaw("*, 
    //         ST_Distance_Sphere(
    //             POINT(?, ?),
    //             POINT(longitude, latitude)
    //         ) * 0.001 as distance_km,
    //         ST_Distance_Sphere(
    //             POINT(?, ?),
    //             POINT(longitude, latitude)
    //         ) * 0.000621371192 as distance_miles
    //     ", [$lon, $lat, $lon, $lat])
    //     ->orderBy('distance_km')
    //     ->limit(5)
    //     ->get();

    //     return response()->json([
    //         'method' => 'MySQL ST_Distance_Sphere',
    //         'data' => $users
    //     ]);
    // }

    // /* Method 4: Geographical Calculator Package */
    // public function geoPackage()
    // {
    //     $calculator = GeoFacade::setPoint([40.7128, -74.0060])
    //         ->setPoint([34.0522, -118.2437])
    //         ->setOptions(['units' => ['km', 'miles']]);

    //     $results = $calculator->getDistance();

    //     return response()->json([
    //         'method' => 'Geographical Calculator Package',
    //         'results' => $results
    //     ]);
    // }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + 
              cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = rad2deg(acos($dist)) * 60 * 1.1515;

        return [
            'miles' => round($dist, 2),
            'km' => round($dist * 1.609344, 2)
        ];
    }
}
