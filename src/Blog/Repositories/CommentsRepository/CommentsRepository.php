<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\CommentsRepository;

use Geekbrains\LevelTwo\Blog\Comments;
use Geekbrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use Geekbrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Person\Name;
use \PDO;

class CommentsRepository implements CommentsRepositoryInterface {

    private PDO $connection;
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @throws UserNotFoundException
     * @throws CommentNotFoundException
     * @throws \Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException
     */
    public function get(UUID $uuid): Comments
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );
        $statement->execute([(string)$uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // Бросаем исключение, если пользователь не найден
        if (false === $result) {
            throw new CommentNotFoundException(
                "Cannot get comment with UUID: $uuid"
            );
        }

        $author = $this->getUserByUUID(new UUID($result['author_uuid']));
        $post = $this->getPostByUUID(new UUID($result['post_uuid']));

        return new Comments(
            new UUID($result['uuid']),
            $author,
            $post,
            $result['text']
        );
    }

    public function save(Comments $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$comment->uuid(),
            ':post_uuid' => (string)$comment->getPost()->uuid(),
            ':author_uuid' => '189e5f4e-c80b-4774-91ac-14518a0b7a6f',
            ':text' => $comment->getComment(),
        ]);
    }

    /**
     * @throws UserNotFoundException
     * @throws \Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException
     */
    private function getUserByUUID(UUID $uuid) :User {
        $uuid = (string)$uuid;
        $statementUser = $this->connection->prepare(
            "SELECT * FROM users WHERE uuid= '" . $uuid . "'"
        );
        $statementUser->execute();
        $result = $statementUser->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot get user with UUID: " . $uuid
            );
        }
        $username = $result['username'];
        $first_name = $result['first_name'];
        $last_name = $result['last_name'];
        return new User(new UUID($uuid), new Name($first_name, $last_name), $username);
    }

    /**
     * @throws UserNotFoundException
     * @throws \Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException
     */
    private function getPostByUUID(UUID $uuid) :Post {
        $uuidPost = (string)$uuid;
        $statementUser = $this->connection->prepare(
            "SELECT * FROM posts WHERE uuid= '" . $uuidPost . "'"
        );
        $statementUser->execute();
        $resultUser = $statementUser->fetch(PDO::FETCH_ASSOC);
        if (false === $resultUser) {
            throw new UserNotFoundException(
                "Cannot get post with UUID: " . $uuidPost
            );
        }
        $author_uuid = $resultUser['author_uuid'];
        $authorPost = $this->getUserByUUID(new UUID($author_uuid));
        $title = $resultUser['title'];
        $text = $resultUser['text'];
        return new Post(new UUID($uuidPost), $authorPost, $title, $text);
    }

}