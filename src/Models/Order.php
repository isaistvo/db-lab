<?php

namespace Src\Models;

use DateTimeInterface;

readonly class Order
{
    public function __construct(
        public ?int $id,
        public int $customerId,
        public int $employeeId,
        public ?string $shipCity,
        public ?string $shipStreet,
        public ?string $shipZip,
        public ?DateTimeInterface $shipDate
    ) {}
}


