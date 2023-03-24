<?php

namespace GeekBrains\LevelTwo\UnitTests\Commands;

use Geekbrains\LevelTwo\Blog\Commands\Users\CreateUser;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\DummyUsersRepository;
use Geekbrains\LevelTwo\Blog\Commands\Arguments;
use Geekbrains\LevelTwo\Blog\Commands\CreateUserCommand;
use Geekbrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use Geekbrains\LevelTwo\Blog\Exceptions\CommandException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateUserCommandTest extends TestCase
{
    // Проверяем, что команда создания пользователя бросает исключение,
    // если пользователь с таким именем уже существует
    /**
     * @throws ArgumentsException
     * @throws InvalidArgumentException
     */
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        // Создаём объект команды
        // У команды одна зависимость - UsersRepositoryInterface
        $command = new CreateUserCommand(
            // Передаём наш стаб в качестве реализации UsersRepositoryInterface
            new DummyUsersRepository(),
            new DummyLogger()
        );
        // Описываем тип ожидаемого исключения
        $this->expectException(CommandException::class);

        // и его сообщение
        $this->expectExceptionMessage('User already exists: admin');

        // Запускаем команду с аргументами
        $command->handle(new Arguments(['username' => 'admin']));
    }

    public function testItRequiresPassword(): void
    {
        $command = new CreateUser(
            new DummyUsersRepository()
        );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "first_name, last_name, password"');
        $command->run(new ArrayInput(['username' => 'Ivan',]), new NullOutput());
    }

//    public function testItRequiresPassword(): void
//    {
//        $command = new CreateUserCommand(
//            new DummyUsersRepository(),
//            new DummyLogger()
//        );
//        $this->expectException(ArgumentsException::class);
//        $this->expectExceptionMessage('No such argument: password');
//        $command->handle(new Arguments(['username' => 'Ivan',]));
//    }

    public function testItRequiresLastName(): void
    {
        // Тестируем новую команду
        $command = new CreateUser(
            new DummyUsersRepository(),
        );
        // Меняем тип ожидаемого исключения ..
        $this->expectException(RuntimeException::class);
        // .. и его сообщение
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "last_name").'
        );
        // Запускаем команду методом run вместо handle
        $command->run(
            // Передаём аргументы как ArrayInput,
            // а не Arguments
            // Сами аргументы не меняются
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
                'first_name' => 'Ivan',
            ]),
            // Передаём также объект,
            // реализующий контракт OutputInterface
            // Нам подойдёт реализация,
            // которая ничего не делает
            new NullOutput()
        );
    }

    public function testItRequiresFirstName(): void
    {
        $command = new CreateUser(
            new DummyUsersRepository()
        );
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "first_name, last_name").'
        );
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'password' => 'some_password',
            ]),
            new NullOutput()
        );
    }

}