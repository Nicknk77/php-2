<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\PostsRepository;

use Geekbrains\LevelTwo\Blog\Post;
//use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
//use Geekbrains\LevelTwo\Blog\User;
//use Geekbrains\LevelTwo\Blog\UUID;
//use Geekbrains\LevelTwo\Person\Name;
use \PDO;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

//    public function get(UUID $uuid): Post
//    {
//        $statement = $this->connection->prepare(
//            'SELECT * FROM posts WHERE uuid = ?'
//        );
//        $statement->execute([(string)$uuid]);
//        return $this->getPost($statement, $uuid);
//    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid,  :title, :text)'
        );
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$post->uuid(),
            ':author_uuid' => $post->getUser()->uuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }

//    /**
//     * @throws UserNotFoundException
//     * @throws \Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException
//     */
//    private function getPost(PDOStatement $statement, string $errorString): User
//    {
//
//        $result = $statement->fetch(PDO::FETCH_ASSOC);
//        // Бросаем исключение, если пользователь не найден
//        if (false === $result) {
//            throw new UserNotFoundException(
//                "Cannot get post: $errorString"
//            );
//        }
//        $statementUser = $this->connection->prepare(
//            'SELECT * FROM users WHERE uuid=?'
//        );
//        $statementUser->execute([]);
//
//
//        return new Post(
//            new UUID($result['uuid']),
//            new User($result['post_uuid'], new Name($result['first_name'], $result['last_name'])),
//            $result['title'],
//            $result['text']
//        );
//    }
}