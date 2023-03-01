<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\UsersRepository;

use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
}