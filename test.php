<?php

use Geekbrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use Geekbrains\LevelTwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Http\Actions\Users\CreateUser;
use Geekbrains\LevelTwo\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));
//$user = new CreateUser(new SqlitePostsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite')));
echo "request";
$likesRepository = new SqliteLikesRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'));
$likes = $likesRepository->getByPostUuid(new UUID('bcae631b-40f1-46f8-a5e4-2cf6317b391c'));

echo(count($likes));
//$user->handle($request);