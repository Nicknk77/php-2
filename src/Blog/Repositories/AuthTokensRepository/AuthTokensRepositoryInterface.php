<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\AuthTokensRepository;

use Geekbrains\LevelTwo\Blog\AuthToken;

interface AuthTokensRepositoryInterface
{
    // Метод сохранения токена
    public function save(AuthToken $authToken): void;
// Метод получения токена
    public function get(string $token): AuthToken;
}