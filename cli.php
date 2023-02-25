<?php
// точка входа

use Geekbrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Geekbrains\LevelTwo\Blog\Comment;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;
use Geekbrains\LevelTwo\Blog\User as User; // если имя остаётся такое же, то алиас не надо
use Geekbrains\LevelTwo\Person\{Name, Person};


//spl_autoload_register('load');
include __DIR__ . '/vendor/autoload.php';   // абсолютный путь

function load($className) {
    $file = $className . '.php';
    $file = str_replace('Geekbrains', 'src', $file);
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
    if (file_exists($file)) require_once $file;
}

$userRepository = new InMemoryUsersRepository();

for($i=2; $i<6; $i++) {
    ${"user$i"} = new User($i, new Name('Ivan', 'Ivanov'), "user$i");
    $userRepository->save(${"user$i"});
}

try {
//    echo $userRepository->get(2);
//    echo $userRepository->get(8);
} catch (UserNotFoundException | Exception $e) {    // наше исключение или какое-то ещё альт - 0124
    echo $e->getMessage();
}

switch ($argv[1]) {
    case "user":
        $faker = Faker\Factory::create('ru_RU');
        echo $faker->name();
        break;
    case "post":
        $name = new Name('Petr', 'Sidor');
        $person = new Person($name,new DateTimeImmutable());
        $post = new Post(1, $person, "HEADER", "This is my Blog about Blog.");
        echo $post;
        break;
    case "comment":
        $name = new Name('Ivan', 'Ivanov');
        $nameComment = new Name('Olga', 'Pegova');
        $user = new User(1, $nameComment, 'Admin');
        $person = new Person($name,new DateTimeImmutable());
        $post = new Post(1, $person, "HEADER", "This is my Blog about Blog.");
        $faker = Faker\Factory::create('ru_RU');
        $text = $faker->realText(rand(50,60));
        $comment = new Comment(1, $user, $post, $text);
        echo $comment;
}

