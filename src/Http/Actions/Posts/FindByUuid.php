<?php

namespace Geekbrains\LevelTwo\Http\Actions\Posts;

use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\Actions\ActionInterface;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;

class FindByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $uuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $post = $this->postsRepository->get(new UUID($uuid));
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            'title' => $post->getTitle(),
            'text' => $post->getText(),
        ]);
    }
}