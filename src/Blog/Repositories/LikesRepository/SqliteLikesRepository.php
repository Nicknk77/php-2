<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\LikesRepository;

use Geekbrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use Geekbrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use Geekbrains\LevelTwo\Blog\Like;
use Geekbrains\LevelTwo\Blog\UUID;
use PDO;

class SqliteLikesRepository implements LikesRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @param Like $post
     * @return void
     */
    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (uuid, uuid_post, uuid_user) VALUES (:uuid, :uuid_post, :uuid_user)'
        );
        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)UUID::random(),
            ':uuid_post' => (string)$like->getUuidPost(),
            ':uuid_user' => (string)$like->getUuidUser(),
        ]);
    }

    /**
     * @param UUID $uuid
     * @return Like
     */
    public function get(UUID $uuid): Like
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE uuid = ?'
        );
        $statement->execute([(string)$uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // Бросаем исключение, если пользователь не найден
        if (false === $result) {
            throw new LikeNotFoundException(
                "Cannot get like with UUID: $uuid"
            );
        }

        $uuidPost = $result['uuid_post'];
        $uuidUser = $result['uuid_user'];

        return new Like(
            new UUID($result['uuid']),
            $uuidPost,
            $uuidUser
        );
    }

    /**
     * @param UUID $uuid
     * @return void
     */
    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM likes WHERE uuid=:uuid;'
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
    }

    /**
     * @param UUID $uuidPost
     * @return array
     * @throws PostNotFoundException
     */
    public function getByPostUuid(UUID $uuidPost): array
    {
        $uuidPost = (string)$uuidPost;
        $statement = $this->connection->prepare(
            "SELECT * FROM likes WHERE uuid_post= '$uuidPost'"
        );
        $statement->execute();
        do {
            $result[] = $statement->fetch(PDO::FETCH_ASSOC);
        } while ($statement->fetch(PDO::FETCH_ASSOC));
        if ($result == false) throw new PostNotFoundException("Cannot get post with UUID: " . $uuidPost);

        return $result;
    }
    public function checkSameLike(UUID $uuidPost, UUID $uuidUser) :bool {
        $uuidPost = (string)$uuidPost;
        $uuidUser = (string)$uuidUser;
        $statement = $this->connection->prepare(
            "SELECT * FROM likes WHERE uuid_post= '$uuidPost' AND uuid_user='$uuidUser'"
        );
        $statement->execute();
        if($statement->fetch()) return true;
        return false;
    }
}