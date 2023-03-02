<?php

use Geekbrains\LevelTwo\Blog\Command\Arguments;
use Geekbrains\LevelTwo\Blog\Command\CreateUserCommand;
use Geekbrains\LevelTwo\Blog\Comments;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Person\Name;

include __DIR__ . '/vendor/autoload.php';   // абсолютный путь

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);

$command = new CreateUserCommand($usersRepository);

$faker = Faker\Factory::create('ru_RU');
$name = new Name($faker->firstName('male'), $faker->lastName('male'));
$user = new User(UUID::random(), $name, $faker->word);
$post = new Post(UUID::random(), $user, $faker->word, $faker->sentence(10));

$postsRepository = new SqlitePostsRepository($connection);

$commentsRepository = new CommentsRepository($connection);
$nameComment = new Name($faker->firstName('female'), $faker->lastName('female'));
$userComment = new User(UUID::random(), $nameComment, $faker->word);
$comment = new Comments(UUID::random(), $userComment, $post, $faker->realText(rand(50,60)));

try {

//    $postsRepository->save($post);
//    $commentsRepository->save($comment);
//    echo $postsRepository->get(new UUID('b4e6f75c-fb89-42a4-b383-5ba60c520297'));
    echo $commentsRepository->get(new UUID('c3f6c79c-b4ad-4ea7-a622-e2eaacee1839'));

//    echo $usersRepository->getByUsername('admin');
//    $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Ivanov'), 'admin'));
//    echo("\n" . $usersRepository->get(new UUID('cdd29f59-d984-4335-bd21-dbd79a34a941')));
//    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $e) {
    echo $e->getMessage();
}