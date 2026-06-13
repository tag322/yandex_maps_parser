<?php

namespace App\Repositories;

use App\DTO\YandexPlaceDTO;
use App\Models\YandexPlace;

// что-то вроде кэша, чтобы реже парсер запускать
class YandexPlaceRepository {
    private const DEFAULT_TTL = 600; // 10min 

    public function __construct(
        private YandexPlace $storeModel
    ) {}

    public function fetchCached(int $oid): ?YandexPlace
    {
        return $this->storeModel
            ->where('yandex_oid', $oid)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function store(YandexPlaceDTO $data): ?YandexPlace
    {
        return $this->storeModel::updateOrCreate(
            ['yandex_oid' => $data->yandexOid],
            [
                'title' => $data->title,
                'rating' => $data->rating,
                'reviews_count' => $data->reviewsCount,
                'ratings_count' => $data->ratingsCount,
                'expires_at' => now()->addSeconds(self::DEFAULT_TTL),
            ]
        );
    }
}