<?php
declare(strict_types=1);

namespace Src\DTO;


class UpdateCustomerDTO
{
    public int $id;
    public string $firstName;
    public string $lastName;
    public ?string $city;
    public ?string $street;
    public ?string $zipCode;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        ?string $city = null,
        ?string $street = null,
        ?string $zipCode = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->city = $city !== '' ? $city : null;
        $this->street = $street !== '' ? $street : null;
        $this->zipCode = $zipCode !== '' ? $zipCode : null;
    }

    
    public static function fromArray(array $data): self
    {
        $id = (int)($data['id'] ?? 0);
        $firstName = trim((string)($data['firstName'] ?? ''));
        $lastName = trim((string)($data['lastName'] ?? ''));
        $city = isset($data['city']) ? trim((string)$data['city']) : null;
        $street = isset($data['street']) ? trim((string)$data['street']) : null;
        $zipCode = isset($data['zipCode']) ? trim((string)$data['zipCode']) : null;

        
        $errors = [];
        if ($id <= 0) {
            $errors[] = 'ID має бути додатним числом';
        }

        if (empty($firstName)) {
            $errors[] = 'Ім\'я обов\'язкове';
        } elseif (strlen($firstName) > 100) {
            $errors[] = 'Ім\'я має містити не більше 100 символів';
        }

        if (empty($lastName)) {
            $errors[] = 'Прізвище обов\'язкове';
        } elseif (strlen($lastName) > 100) {
            $errors[] = 'Прізвище має містити не більше 100 символів';
        }

        if ($city !== null && strlen($city) > 100) {
            $errors[] = 'Місто має містити не більше 100 символів';
        }

        if ($street !== null && strlen($street) > 255) {
            $errors[] = 'Вулиця має містити не більше 255 символів';
        }

        if ($zipCode !== null && !preg_match('/^\d{5}$/', $zipCode)) {
            $errors[] = 'Поштовий індекс має бути 5 цифр';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode('; ', $errors));
        }

        return new self(
            $id,
            $firstName,
            $lastName,
            $city,
            $street,
            $zipCode
        );
    }
}


