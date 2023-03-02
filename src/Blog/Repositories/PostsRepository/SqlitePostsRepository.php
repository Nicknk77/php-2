<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\PostsRepository;

use Geekbrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Person\Name;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;
use \PDO;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @throws UserNotFoundException
     * @throws PostNotFoundException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = ?'
        );
        $statement->execute([(string)$uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // Бросаем исключение, если пользователь не найден
        if (false === $result) {
            throw new PostNotFoundException(
                "Cannot get post with UUID: $uuid"
            );
        }
        $uuidUser = $result['author_uuid'];
        $statementUser = $this->connection->prepare(
            "SELECT * FROM users WHERE uuid= '" . $uuidUser . "'"
        );
        $statementUser->execute();
        $resultUser = $statementUser->fetch(PDO::FETCH_ASSOC);
        if (false === $resultUser) {
            throw new UserNotFoundException(
                "Cannot get user with UUID: " . $result['author_uuid']
            );
        }
        $username = $resultUser['username'];
        $first_name = $resultUser['first_name'];
        $last_name = $resultUser['last_name'];

        return new Post(
            new UUID($result['uuid']),
            new User(new UUID($result['author_uuid']), new Name($first_name, $last_name), $username),
            $result['title'],
            $result['text']
        );
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid,  :title, :text)'
        );
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$post->uuid(),
//            ':author_uuid' => $post->getUser()->uuid(),
            ':author_uuid' => '189e5f4e-c80b-4774-91ac-14518a0b7a6f',
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }


}