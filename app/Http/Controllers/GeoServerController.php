<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeoServerController extends Controller
{
    public function show()
    {
        $url = 'http://localhost:8080/geoserver/diy/wfs';
        $params = [
            'service' => 'WFS',
            'version' => '1.1.0',
            'request' => 'GetFeature',
            'typeName' => 'diy:ADMIN_AR_DES_SLEMAN_Proj', // Sesuaikan dengan nama layer Anda
            'outputFormat' => 'application/json',
        ];

        // Mengirim permintaan GET ke GeoServer
        $response = Http::get($url, $params);

        if ($response->successful()) {
            $geojsonData = $response->json();
        } else {
            $geojsonData = null;
            $error = $response->body();
        }

        return view('geoserver-data', compact('geojsonData', 'error'));
    }
}
