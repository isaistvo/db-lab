<?php
declare(strict_types=1);

namespace Src\DTO;

/**
 * DTO used for updating an existing Customer
 */
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

    /**
     * Build DTO from array (e.g., $_POST)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int)($data['id'] ?? 0),
            trim((string)($data['firstName'] ?? '')),
            trim((string)($data['lastName'] ?? '')),
            isset($data['city']) ? trim((string)$data['city']) : null,
            isset($data['street']) ? trim((string)$data['street']) : null,
            isset($data['zipCode']) ? trim((string)$data['zipCode']) : null
        );
    }
}
