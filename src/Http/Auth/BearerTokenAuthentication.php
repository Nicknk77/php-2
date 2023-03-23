<?php

namespace Geekbrains\LevelTwo\Http\Auth;

use DateTimeImmutable;
use Geekbrains\LevelTwo\Blog\Exceptions\AuthException;
use Geekbrains\LevelTwo\Blog\Exceptions\AuthTokenNotFoundException;
use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Http\Request;

// Bearer — на предъявителя
class BearerTokenAuthentication implements TokenAuthenticationInterface
{

    private const HEADER_PREFIX = 'Bearer ';
    public function __construct(
        // Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository,
        // Репозиторий пользователей
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        // Получаем HTTP-заголовок
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
    // Проверяем, что заголовок имеет правильный формат
    if (!str_starts_with($header, self::HEADER_PREFIX)) {
        throw new AuthException("Malformed token: [$header]");
    }
    // Отрезаем префикс Bearer
    $token = mb_substr($header, strlen(self::HEADER_PREFIX));
    // Ищем токен в репозитории
    try {
        $authToken = $this->authTokensRepository->get($token);
    } catch (AuthTokenNotFoundException) {
        throw new AuthException("Bad token: [$token]");
    }
    // Проверяем срок годности токена
    if ($authToken->expiresOn() <= new DateTimeImmutable()) {
        throw new AuthException("Token expired: [$token]");
    }
    // Получаем UUID пользователя из токена
    $userUuid = $authToken->userUuid();
    // Ищем и возвращаем пользователя
    return $this->usersRepository->get($userUuid);
    }
}