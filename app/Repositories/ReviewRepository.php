<?php

namespace App\Repositories;

use App\Models\Review;
use App\DTO\ReviewDTO;
use App\DTO\PaginationDTO;
use Illuminate\Support\Facades\DB;

class ReviewRepository
{
    public function __construct(
        private Review $model
    ) {}

    public function getByPlace(int $placeId, int $page = 1, int $perPage = 50): PaginationDTO
    {
        $query = $this->model
            ->where('yandex_oid', $placeId)
            ->orderByDesc('published_at');

        $total = $query->count();

        $reviews = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(fn($review) => ReviewDTO::fromModel($review))
            ->values()
            ->toArray();

        return new PaginationDTO(
            data: $reviews,
            total: $total,
            perPage: $perPage,
            currentPage: $page,
            lastPage: (int) ceil($total / $perPage),
        );
    }

    public function sync(int $placeId, array $reviews): void
    {
        DB::transaction(function () use ($placeId, $reviews) {

            $this->model
                ->where('yandex_oid', $placeId)
                ->delete();

            $data = array_map(fn($r) => [
                'yandex_oid' => $placeId,
                'author' => $r['author'] ?? null,
                'rating' => $r['rating'] ?? null,
                'text' => $r['text'] ?? '',
                'published_at' => isset($r['date'])
                    ? \Carbon\Carbon::parse($r['date'])
                    : null,
                'created_at' => now(),
                'updated_at' => now(),
            ], $reviews);

            $this->model::insert($data);
        });
    }
}