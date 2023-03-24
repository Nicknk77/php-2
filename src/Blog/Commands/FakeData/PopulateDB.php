<?php

namespace Geekbrains\LevelTwo\Blog\Commands\FakeData;

use Faker\Generator;
use Geekbrains\LevelTwo\Blog\Post;
use Geekbrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Geekbrains\LevelTwo\Blog\User;
use Geekbrains\LevelTwo\Blog\UUID;
use Geekbrains\LevelTwo\Person\Name;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class PopulateDB extends Command
{
    // Внедряем генератор тестовых данных и
    // репозитории пользователей и статей
    public function __construct(
        private Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                // Имя опции
                'users-number',
                'u',
                // Опция имеет значения
                InputOption::VALUE_OPTIONAL,
                // Описание
                'Quantity of users',
            )->addOption(
            // Имя опции
                'posts-number',
                '',
                // Опция имеет значения
                InputOption::VALUE_OPTIONAL,
                // Описание
                'Quantity of posts',
            );
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        // Создаём десять пользователей
        $users = [];
        for ($i = 0; $i < $input->getOption('users-number'); $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->username());
        }
        // От имени каждого пользователя
        // создаём по ... статей
        foreach ($users as $user) {
            for ($i = 0; $i < $input->getOption('posts-number'); $i++) {
                $post = $this->createFakePost($user);
                $output->writeln('Post created: ' . $post->getTitle());
            }
        }
        return Command::SUCCESS;
    }
    private function createFakeUser(): User
    {
        $user = User::createFrom(
            // Генерируем имя пользователя
            new Name(
                // Генерируем имя
                $this->faker->firstName,
                // Генерируем фамилию
                $this->faker->lastName
            ),
            // Генерируем пароль
            $this->faker->password,
            $this->faker->userName,
        );
        // Сохраняем пользователя в репозиторий
        $this->usersRepository->save($user);
        return $user;
    }
    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            // Генерируем предложение не длиннее шести слов
            $this->faker->sentence(6, true),
            // Генерируем текст
            $this->faker->realText
        );
        // Сохраняем статью в репозиторий
        $this->postsRepository->save($post);
        return $post;
    }
}