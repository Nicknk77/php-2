<?php

namespace Geekbrains\LevelTwo\Blog\Commands;

use Geekbrains\LevelTwo\Blog\Exceptions\CommandException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    // php cli.php username=ivan first_name=Ivan last_name=Nikitin

    // Команда зависит от контракта репозитория пользователей,
    // а не от конкретной реализации
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger,
    ) {
    }

//    /**
//     * @throws CommandException
//     * @throws InvalidArgumentException
//     */
//    public function handle(array $rawInput): void
//    {
//        $input = $this->parseRawInput($rawInput);
//        $username = $input['username'];
//        // Проверяем, существует ли пользователь в репозитории
//        if ($this->userExists($username)) {
//            // Бросаем исключение, если пользователь уже существует
//            throw new CommandException("User already exists: $username");
//        }
//
//        // Сохраняем пользователя в репозиторий
//        $this->usersRepository->save(new User(
//            UUID::random(),
//            new Name($input['first_name'], $input['last_name']),
//            $username
//        ));
//    }
    // Преобразуем входной массив
    // из предопределённой переменной $argv
    //
    // array(4) {
    // [0]=>
    // string(18) "/some/path/cli.php"
    // [1]=>
    // string(13) "username=ivan"
    // [2]=>
    // string(15) "first_name=Ivan"
    // [3]=>
    // string(17) "last_name=Nikitin"
    // }
    //
    // в ассоциативный массив вида
    // array(3) {
    // ["username"]=>
    // string(4) "ivan"
    // ["first_name"]=>
    // string(4) "Ivan"
    // ["last_name"]=>
    // string(7) "Nikitin"
    //}
//    /**
//     * @throws CommandException
//     */
//    private function parseRawInput(array $rawInput): array
//    {
//        $input = [];
//        foreach ($rawInput as $argument) {
//            $parts = explode('=', $argument);
//            if (count($parts) !== 2) {
//                continue;
//            }
//            $input[$parts[0]] = $parts[1];
//        }
//        foreach (['username', 'first_name', 'last_name'] as $argument) {
//            if (!array_key_exists($argument, $input)) {
//                throw new CommandException(
//                    "No required argument provided: $argument"
//                );
//            }
//            if (empty($input[$argument])) {
//                throw new CommandException("Empty argument provided: $argument");
//            }
//        }
//        return $input;
//    }

    /**
     * @throws \Geekbrains\LevelTwo\Blog\Exceptions\ArgumentsException
     * @throws CommandException
     * @throws \Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException
     */
    public function handle(Arguments $arguments): void
    {
        // Логируем информацию о том, что команда запущена
        // Уровень логирования – INFO
        $this->logger->info("Create user command started");
        $username = $arguments->get('username');

        //Получаем пароль для нового пользователя
        $password = $arguments->get('password');
        // Вычисляем SHA-256-хеш пароля
        $hash = hash('sha256', $password);

        if ($this->userExists($username)) {
//            throw new CommandException("User already exists: $username");
            // Логируем сообщение с уровнем WARNING
            $this->logger->warning("User already exists: $username");
            // Вместо выбрасывания исключения просто выходим из функции
            return;
        }

//        $uuid = UUID::random();
//        $this->usersRepository->save(new User(
//            $uuid,
//            new Name($arguments->get('first_name'), $arguments->get('last_name')),
//            // Вместо пароля записываем его хеш
//            $hash,
//            $username
//        ));
        // Создаём объект пользователя
        // Функция createFrom сама создаст UUID
        // и захеширует пароль
        $user = User::createFrom(
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            ),
            $arguments->get('password'),
            $username
        );
        $this->usersRepository->save($user);
        // Логируем информацию о новом пользователе
        $this->logger->info("User created: " . $user->uuid());
    }

    private function userExists(string $username): bool
    {
        try {
            // Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }

}