<?php
// точка входа

use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Comments;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;
use Geekbrains\LevelTwo\Blog\User as User; // если имя остаётся такое же, то алиас не надо
use Geekbrains\LevelTwo\Person\{Name, Person};

//spl_autoload_register('load');
//function load($className) {
//    $file = $className . '.php';
//    $file = str_replace('Geekbrains\LevelTwo', 'src', $file);
//    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
//    if (file_exists($file)) require_once $file;
//}

include __DIR__ . '/vendor/autoload.php';   // абсолютный путь
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$userRepository = new InMemoryUsersRepository();

for($i=2; $i<6; $i++) {
    ${"user$i"} = new User($i, new Name('Ivan', 'Ivanov'), "user$i");
    $userRepository->save(${"user$i"});
}

//try {
//    echo $userRepository->get(2);
//    echo $userRepository->get(8);
//} catch (UserNotFoundException | Exception $e) {    // наше исключение или какое-то ещё альт - 0124
//    echo $e->getMessage();
//}

$faker = Faker\Factory::create('ru_RU');
$name = new Name($faker->firstName('male'), $faker->lastName('male'));
$user = new User($faker->randomDigit(), $name, $faker->word);

$route = $argv[1] ?? null;

switch ($route) {
    case "user":
        echo $user;
        break;
    case "post":
        $post = new Post(1, $user, $faker->word, $faker->sentence(10));
        echo $post;
        break;
    case "comment":
        $nameComment = new Name($faker->firstName('female'), $faker->lastName('female'));
        $userComment = new User(1, $nameComment, $faker->word);
        $post = new Post(1, $user, $faker->word, $faker->sentence(10));
        $comment = new Comments(1, $userComment, $post, $faker->realText(rand(50,60)));
        echo $comment;
        break;
    default:
        echo "error parameter: user, post, comment";
}

