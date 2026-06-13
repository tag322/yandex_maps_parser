<?php

namespace App\DTO;

final readonly class PaginationDTO
{
    public function __construct(
        public array $data,
        public int $total,
        public int $perPage,
        public int $currentPage,
        public int $lastPage,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => [
                'total' => $this->total,
                'perPage' => $this->perPage,
                'currentPage' => $this->currentPage,
                'lastPage' => $this->lastPage,
            ],
        ];
    }
}