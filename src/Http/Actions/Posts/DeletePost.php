<?php

namespace Geekbrains\LevelTwo\Http\Actions\Posts;

use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use Geekbrains\LevelTwo\Blog\Exceptions\JsonException;
use Geekbrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;
use Geekbrains\LevelTwo\Http\Actions\ActionInterface;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $postUuid = new UUID($request->jsonBodyField('uuid'));
            $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException | InvalidArgumentException | HttpException | JsonException$e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->postsRepository->delete($postUuid);
        return new SuccessfulResponse(['uuid' => (string)$postUuid]);
    }
}