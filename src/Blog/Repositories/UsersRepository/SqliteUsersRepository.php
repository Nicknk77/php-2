<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\UsersRepository;

use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Person\Name;
use PDO;
use \PDOStatement;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name) VALUES (:uuid, :username,  :first_name, :last_name)'
        );
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$user->uuid(),
            ':username' => $user->username(),
            ':first_name' => $user->name()->first(),
            ':last_name' => $user->name()->last(),
        ]);
        //Нам нужно выполнить что-то вроде:
        //
        //$connection->exec(
        // "INSERT INTO users (first_name, last_name) VALUES ('First', 'Last')"
        //);
    }

        // Также добавим метод для получения
        // пользователя по его UUID
    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );
        $statement->execute([(string)$uuid]);
        return $this->getUser($statement, $uuid);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function getByUsername(string $username): User {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = ?'
        );
        $statement->execute([$username]);
        return $this->getUser($statement, $username);
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    private function getUser(PDOStatement $statement, string $errorString): User
    {

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // Бросаем исключение, если пользователь не найден
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot get user: $errorString"
            );
        }
        return new User(
            new UUID($result['uuid']),
            new Name($result['first_name'], $result['last_name']),
            $result['username']
        );
    }

}