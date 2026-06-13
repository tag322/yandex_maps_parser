<?php

namespace App\Services;

use App\Services\YandexPlaceParser;
use App\Services\ReviewService;
use App\Repositories\YandexPlaceRepository;
use App\Support\Yandex;
use Illuminate\Support\Arr;
use App\DTO\YandexPlaceDTO;

class YandexPlaceService {
    public function __construct(
        private YandexPlaceParser $parser,
        private YandexPlaceRepository $repo,
        private ReviewService $reviewService,
    ) {}

    public function getPlace(string $url): YandexPlaceDTO
    {
        $oid = Yandex::extractOid($url);

        $placeModel = $this->repo->fetchCached($oid);

        if (!$placeModel) {
            $parsed = $this->parser->parse($url);

            $dto = new YandexPlaceDTO(
                yandexOid: $oid,
                title: $parsed['title'],
                rating: $parsed['rating'],
                reviewsCount: $parsed['reviews_count'],
                ratingsCount: $parsed['ratings_count'],
                reviews: []
            );

            $placeModel = $this->repo->store($dto);

            $this->reviewService->sync(
                $placeModel->yandex_oid,
                $parsed['reviews']
            );
        }

        $reviews = $this->reviewService->getPaginatedByPlace(
            $placeModel->yandex_oid,
            request('page', 1),
            50
        );

        return YandexPlaceDTO::fromModel(
            $placeModel,
            $reviews->toArray()
        );
    }
}
