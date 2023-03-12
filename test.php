<?php

use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Geekbrains\LevelTwo\Http\Actions\Users\CreateUser;
use Geekbrains\LevelTwo\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));
$user = new CreateUser(new SqlitePostsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite')));
echo "request";

$user->handle($request);