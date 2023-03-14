<?php

namespace Geekbrains\LevelTwo\Http\Actions\Comments;

use Geekbrains\LevelTwo\Blog\Comments;
use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use Geekbrains\LevelTwo\Blog\Exceptions\JsonException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;

class CreateComment implements \Geekbrains\LevelTwo\Http\Actions\ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
        private CommentsRepositoryInterface $commentsRepository
    ) {}

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException | JsonException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
        $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        $post = $this->postsRepository->get($postUuid);
    } catch (HttpException | InvalidArgumentException | JsonException $e) {
        return new ErrorResponse($e->getMessage());
    }
        try {
            $text = $request->jsonBodyField('text');
        } catch (HttpException | InvalidArgumentException | JsonException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $author = $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newCommentUuid = UUID::random();

        try {
            $comment = new Comments(
                $newCommentUuid,
                $author,
                $post,
                $text
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->commentsRepository->save($comment);
        return new SuccessfulResponse(['data' => (string)$newCommentUuid]);
    }
}