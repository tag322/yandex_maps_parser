<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\YandexPlaceService;
use App\Models\YandexPlaceSettings;

class YandexPlaceController extends Controller
{
    public function getPlaceData(Request $req, YandexPlaceService $service)
    {
        $config = YandexPlaceSettings::latest()->first();

        if (!$config) {
            return response()->json([
                'error' => 'No URL configured. See /api/setPlace'
            ], 404);
        }

        $dto = $service->getPlace($config->url);

        return response()->json($dto->toArray());
    }

    public function setPlace(Request $request)
    {
        $data = $request->validate([
            'url' => ['required', 'url'],
        ]);

        YandexPlaceSettings::create([
            'url' => $data['url'],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
