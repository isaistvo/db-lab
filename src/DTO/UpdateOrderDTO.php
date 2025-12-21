<?php

namespace Src\DTO;

use DateTimeImmutable;
use DateTimeInterface;

class UpdateOrderDTO
{
    public function __construct(
        public int $id,
        public int $customerId,
        public int $employeeId,
        public ?string $shipCity,
        public ?string $shipStreet,
        public ?string $shipZip,
        public ?DateTimeInterface $shipDate
    ) {}

    public static function fromArray(array $data): self
    {
        $dateStr = trim((string)($data['ship_date'] ?? ''));
        $date = $dateStr !== '' ? new DateTimeImmutable($dateStr) : null;
        return new self(
            (int)($data['id'] ?? 0),
            (int)($data['customer_id'] ?? 0),
            (int)($data['employee_id'] ?? 0),
            ($data['ship_city'] ?? null) !== '' ? trim((string)$data['ship_city']) : null,
            ($data['ship_street'] ?? null) !== '' ? trim((string)$data['ship_street']) : null,
            ($data['ship_zip'] ?? null) !== '' ? trim((string)$data['ship_zip']) : null,
            $date
        );
    }
}
