<?php

namespace Geekbrains\LevelTwo\Http\Actions\Users;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Person\Name;

class CreateUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $newUserUuid = UUID::random();

            $user = new User(
                $newUserUuid,
                new Name(
                    $request->jsonBodyField('first_name'),
                    $request->jsonBodyField('last_name')
                ),
                $request->jsonBodyField('username')
            );

        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());

        }

        $this->usersRepository->save($user);

        return new SuccessfulResponse([
            'uuid' => (string)$newUserUuid,
        ]);
    }
}