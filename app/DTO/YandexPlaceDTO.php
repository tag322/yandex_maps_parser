<?php

namespace App\DTO;

final readonly class YandexPlaceDTO
{
    public function __construct(
        public int $yandexOid,
        public string $title,
        public ?float $rating,
        public int $reviewsCount,
        public int $ratingsCount,
        public array $reviews = [], // ReviewDTO[]
    ) {}

    public static function fromModel($place, array $reviews = []): self
    {
        return new self(
            yandexOid: $place->yandex_oid,
            title: $place->title,
            rating: $place->rating,
            reviewsCount: $place->reviews_count,
            ratingsCount: $place->ratings_count,
            reviews: $reviews,
        );
    }

    public function toArray(): array
    {
        return [
            'yandexOid' => $this->yandexOid,
            'title' => $this->title,
            'rating' => $this->rating,
            'reviewsCount' => $this->reviewsCount,
            'ratingsCount' => $this->ratingsCount,
            'reviews' => $this->reviews,
        ];
    }
}