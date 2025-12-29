<?php

namespace Src\DTO;

use DateTimeImmutable;
use DateTimeInterface;

class CreateOrderDTO
{
    public function __construct(
        public int $customerId,
        public int $employeeId,
        public ?string $shipCity,
        public ?string $shipStreet,
        public ?string $shipZip,
        public ?DateTimeInterface $shipDate
    ) {}

    public static function fromArray(array $data): self
    {
        $customerId = (int)($data['customer_id'] ?? 0);
        $employeeId = (int)($data['employee_id'] ?? 0);
        $shipCity = ($data['ship_city'] ?? null) !== '' ? trim((string)$data['ship_city']) : null;
        $shipStreet = ($data['ship_street'] ?? null) !== '' ? trim((string)$data['ship_street']) : null;
        $shipZip = ($data['ship_zip'] ?? null) !== '' ? trim((string)$data['ship_zip']) : null;
        $dateStr = trim((string)($data['ship_date'] ?? ''));
        $date = null;
        if ($dateStr !== '') {
            try {
                $date = new DateTimeImmutable($dateStr);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException('Некоректна дата доставки');
            }
        }

        
        $errors = [];
        if ($customerId <= 0) {
            $errors[] = 'ID клієнта має бути додатним числом';
        }

        if ($employeeId <= 0) {
            $errors[] = 'ID співробітника має бути додатним числом';
        }

        if ($shipCity !== null && strlen($shipCity) > 100) {
            $errors[] = 'Місто доставки має містити не більше 100 символів';
        }

        if ($shipStreet !== null && strlen($shipStreet) > 255) {
            $errors[] = 'Вулиця доставки має містити не більше 255 символів';
        }

        if ($shipZip !== null && !preg_match('/^\d{5}$/', $shipZip)) {
            $errors[] = 'Поштовий індекс доставки має бути 5 цифр';
        }

        if ($date !== null && $date < new DateTimeImmutable('today')) {
            $errors[] = 'Дата доставки не може бути в минулому';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode('; ', $errors));
        }

        return new self(
            $customerId,
            $employeeId,
            $shipCity,
            $shipStreet,
            $shipZip,
            $date
        );
    }
}


