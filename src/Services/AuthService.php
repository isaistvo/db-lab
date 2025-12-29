<?php

namespace Src\Services;

use Src\Repositories\UserRepository;
use Src\DTO\LoginDTO;
use Src\DTO\RegisterDTO;
use Src\Models\User;
use Src\Core\Logger;
use Src\Mappers\UserMapper;
use Src\Core\Database;

class AuthService
{
    private UserRepository $repository;

    public function __construct(Database $db = null)
    {
        $this->repository = new UserRepository($db);
    }

    public function authenticate(LoginDTO $input): ?User
    {
        if (empty($input->username) || empty($input->password)) {
            throw new \InvalidArgumentException("Username and password are required.");
        }

        $user = $this->repository->findByUsername($input->username);
        if (!$user) {
            Logger::info("Authentication failed: user not found", ['username' => $input->username]);
            return null;
        }

        if (!password_verify($input->password, $user->passwordHash)) {
            Logger::info("Authentication failed: invalid password", ['username' => $input->username]);
            return null;
        }

        Logger::info("User authenticated", ['username' => $input->username]);
        return $user;
    }

    public function registerUser(RegisterDTO $input): void
    {
        if (empty($input->username) || empty($input->password)) {
            throw new \InvalidArgumentException("Username and password are required.");
        }

        if (strlen($input->password) < 6) {
            throw new \InvalidArgumentException("Password must be at least 6 characters long.");
        }

        
        $existing = $this->repository->findByUsername($input->username);
        if ($existing) {
            throw new \InvalidArgumentException("Username already exists.");
        }

        $user = UserMapper::fromRegisterDTO($input);
        try {
            $this->repository->save($user);
        } catch (\Exception $e) {
            Logger::error("Failed to save user", ['error' => $e->getMessage(), 'username' => $input->username]);
            throw new \RuntimeException("Не вдалося зберегти користувача: " . $e->getMessage());
        }

        Logger::info("User registered", ['username' => $input->username]);
    }

    public function getUserById(int $id): ?User
    {
        return $this->repository->findById($id);
    }
}


