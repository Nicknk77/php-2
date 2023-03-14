<?php

namespace Geekbrains\LevelTwo\Http\Actions\Likes;

use Geekbrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use Geekbrains\LevelTwo\Blog\Exceptions\JsonException;
use Geekbrains\LevelTwo\Blog\Exceptions\LikeWasFoundException;
use Geekbrains\LevelTwo\Blog\Like;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;
use Geekbrains\LevelTwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\Actions\ActionInterface;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;

class CreateLike implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
        private SqliteLikesRepository $likesRepository
    ){}

    /**
     * @param Request $request
     * @return Response
     * @throws LikeWasFoundException
     */
    public function handle(Request $request): Response
    {
        try {
            $uuidUser = new UUID($request->jsonBodyField('user_uuid'));
            $this->usersRepository->get($uuidUser);
        } catch (HttpException | InvalidArgumentException | JsonException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $uuidPost = new UUID($request->jsonBodyField('post_uuid'));
            $this->postsRepository->get($uuidPost);
        } catch (HttpException | InvalidArgumentException | JsonException $e) {
            return new ErrorResponse($e->getMessage());
        }
        if ($this->likesRepository->checkSameLike($uuidPost, $uuidUser))
            throw new LikeWasFoundException();

        $newLikeUuid = UUID::random();

        try {
            $like = new Like(
                $newLikeUuid,
                $uuidPost,
                $uuidUser
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->likesRepository->save($like);
        return new SuccessfulResponse(['data' => (string)$newLikeUuid]);
    }
}