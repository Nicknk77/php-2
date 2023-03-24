<?php

namespace Geekbrains\LevelTwo\Http\Auth;

use Geekbrains\LevelTwo\Blog\Exceptions\AuthException;
use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\JsonException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Http\Request;

class PasswordAuthentication implements PasswordAuthenticationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }

    /**
     * @throws AuthException
     * @throws JsonException
     */
    public function user(Request $request): User
    {
        // 1. Идентифицируем пользователя
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
        // 2. Аутентифицируем пользователя
        // Проверяем, что предъявленный пароль
        // соответствует сохранённому в БД
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        // Вычисляем SHA-256-хеш предъявленного пароля
        $hash = hash('sha256', $user->uuid() . $password);
        if ($hash !== $user->hashedPassword()) {
            // Если хеши не совпадают — бросаем исключение
            throw new AuthException('Wrong password');
        }
        // Пользователь аутентифицирован
        return $user;
    }
}