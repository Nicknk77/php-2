<?php

namespace Geekbrains\LevelTwo\Http\Actions\Posts;

use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use Geekbrains\LevelTwo\Blog\Exceptions\JsonException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\Actions\ActionInterface;
use Geekbrains\LevelTwo\Http\Auth\IdentificationInterface;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
//        private UsersRepositoryInterface $usersRepository,

        // Вместо контракта репозитория пользователей
        // внедряем контракт идентификации
        private IdentificationInterface $identification,
        // Внедряем контракт логгера
        private LoggerInterface $logger,
    ) {}

/**
 * @param Request $request
 * @return Response
 */
public function handle(Request $request): Response
{
//    try {
//        $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
//    } catch (HttpException | InvalidArgumentException | JsonException $e) {
//        return new ErrorResponse($e->getMessage());
//    }
//    try {
//        $user = $this->usersRepository->get($authorUuid);
//    } catch (UserNotFoundException $e) {
//        return new ErrorResponse($e->getMessage());
//    }
    $user = $this->identification->user($request);

    $newPostUuid = UUID::random();

    try {
        $post = new Post(
            $newPostUuid,
            $user,
            $request->jsonBodyField('title'),
            $request->jsonBodyField('text')
        );
    } catch (HttpException $e) {
        return new ErrorResponse($e->getMessage());
    }
    $this->postsRepository->save($post);

    // Логируем UUID новой статьи
    $this->logger->info("Post created: $newPostUuid");

    return new SuccessfulResponse(['data' => (string)$newPostUuid]);
}

}