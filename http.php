<?php

require_once __DIR__ . '/vendor/autoload.php';

use Geekbrains\LevelTwo\Blog\Exceptions\AppException;
use Geekbrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Geekbrains\LevelTwo\Http\Actions\Comments\CreateComment;
use Geekbrains\LevelTwo\Http\Actions\Posts\CreatePost;
use Geekbrains\LevelTwo\Http\Actions\Posts\DeletePost;
use Geekbrains\LevelTwo\Http\Actions\Posts\FindByUuid;
use Geekbrains\LevelTwo\Http\Actions\Users\FindByUsername;
use Geekbrains\LevelTwo\Http\ErrorResponse;
use Geekbrains\LevelTwo\Http\Request;
use Geekbrains\LevelTwo\Http\SuccessfulResponse;

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));
try {
    // Пытаемся получить путь из запроса
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}
try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
    '/users/show' => new FindByUsername(new SqliteUsersRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'))),
    '/posts/show' => new FindByUuid(new SqlitePostsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'))),
    ],
    'POST' => [
        '/posts/create' => new CreatePost(new SqlitePostsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite')),
            new SqliteUsersRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'))),
        '/posts/delete' => new DeletePost(new SqlitePostsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'))),
        '/posts/comment' => new CreateComment(new SqlitePostsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite')),
            new SqliteUsersRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite')),
        new CommentsRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite')))
    ],
];
if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Such method Not found'))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Route Not found'))->send();
    return;
}
$action = $routes[$method][$path];
try {
    $response = $action->handle($request);
    $response->send();
} catch (AppException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

//$header = $request->header('Some-header');
//$path = $request->path();
//$parameter = $request->query('username');
//echo $path . "\n";
//echo $parameter . "\n";
//echo $header;
//$user = new FindByUsername(new SqliteUsersRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite')));
//$user->handle($request)->send();
//$response = new ErrorResponse();
//$response = new SuccessfulResponse(['message' => 'Hello from me',]);
//$response->send();
//
//
