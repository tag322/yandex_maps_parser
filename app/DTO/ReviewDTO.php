<?php

namespace App\DTO;

final readonly class ReviewDTO
{
    public function __construct(
        public ?string $author,
        public ?int $rating,
        public string $text,
        public ?string $publishedAt,
    ) {}

    public static function fromModel($review): self
    {
        return new self(
            author: $review->author,
            rating: $review->rating,
            text: $review->text,
            publishedAt: $review->published_at?->toDateTimeString(),
        );
    }

    public function toArray(): array
    {
        return [
            'author' => $this->author,
            'rating' => $this->rating,
            'text' => $this->text,
            'publishedAt' => $this->publishedAt,
        ];
    }
}