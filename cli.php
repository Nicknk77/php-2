<?php

use Geekbrains\LevelTwo\Blog\Commands\Arguments;
use Geekbrains\LevelTwo\Blog\Commands\CreateUserCommand;
use Geekbrains\LevelTwo\Blog\Commands\FakeData\PopulateDB;
use Geekbrains\LevelTwo\Blog\Commands\Posts\DeletePost;
use Geekbrains\LevelTwo\Blog\Commands\Users\CreateUser;
use Geekbrains\LevelTwo\Blog\Commands\Users\UpdateUser;
use Geekbrains\LevelTwo\Blog\Comments;
use Geekbrains\LevelTwo\Blog\Exceptions\AppException;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

// Подключаем файл bootstrap.php
// и получаем настроенный контейнер
$container = require __DIR__ . '/bootstrap.php';
// Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);

// unit-8
// Создаём объект приложения
$application = new Application();
// Перечисляем классы команд
$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    // Добавили команду генерирования тестовых данных
    PopulateDB::class,
];
foreach ($commandsClasses as $commandClass) {
// Посредством контейнера
// создаём объект команды
    $command = $container->get($commandClass);
// Добавляем команду к приложению
    $application->add($command);
}
// Запускаем приложение
$application->run();
// unit-8

//        // При помощи контейнера создаём команду
//        try {
//            $command = $container->get(CreateUserCommand::class);
//            $command->handle(Arguments::fromArgv($argv));
//        } catch (AppException $e) {
//            // Логируем информацию об исключении.
//        // Объект исключения передаётся логгеру
//        // с ключом "exception".
//        // Уровень логирования – ERROR
//            $logger->error($e->getMessage(), ['exception' => $e]);
//        }

//$likeRepos = new SqliteLikesRepository(new PDO('sqlite:' . __DIR__ . '/blog.sqlite'));
//$likes = $likeRepos->getByPostUuid(new UUID('bcae631b-40f1-46f8-a5e4-2cf6317b391c'));
//print_r($likes);



//include __DIR__ . '/vendor/autoload.php';   // абсолютный путь

//$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
//$usersRepository = new SqliteUsersRepository($connection);
//$command = new CreateUserCommand($usersRepository, Lo);
//
//$faker = Faker\Factory::create('ru_RU');
//$name = new Name($faker->firstName('male'), $faker->lastName('male'));
//$user = new User(UUID::random(), $name, $faker->word);
//$post = new Post(UUID::random(), $user, $faker->word, $faker->sentence(10));
//
//$postsRepository = new SqlitePostsRepository($connection);
//
//$commentsRepository = new CommentsRepository($connection);
//$nameComment = new Name($faker->firstName('female'), $faker->lastName('female'));
//$userComment = new User(UUID::random(), $nameComment, $faker->word);
//$comment = new Comments(UUID::random(), $userComment, $post, $faker->realText(rand(50,60)));

//try {

//    $postsRepository->save($post);
//    $commentsRepository->save($comment);
//    echo $postsRepository->get(new UUID('1efe6933-c355-4404-af7e-e6988be601b1'));
//    echo $commentsRepository->get(new UUID('c3f6c79c-b4ad-4ea7-a622-e2eaacee1839'));

//    echo $usersRepository->getByUsername('admin');
//    $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Ivanov'), 'admin'));
//    echo("\n" . $usersRepository->get(new UUID('cdd29f59-d984-4335-bd21-dbd79a34a941')));
//    $command->handle(Arguments::fromArgv($argv));
//} catch (Exception $e) {
//    echo $e->getMessage();
//}