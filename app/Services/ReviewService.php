<?php

namespace App\Services;

use App\Repositories\ReviewRepository;

class ReviewService
{
    public function __construct(
        private ReviewRepository $repo
    ) {}

    public function getPaginatedByPlace(int $placeId, int $page, int $perPage = 50)
    {
        return $this->repo->getByPlace($placeId, $page, $perPage);
    }

    public function sync(int $placeId, array $reviews): void
    {
        $this->repo->sync($placeId, $reviews);
    }
}