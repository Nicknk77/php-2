<?php

namespace GeekBrains\LevelTwo\UnitTests\Commands;

use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\DummyUsersRepository;
use Geekbrains\LevelTwo\Blog\Commands\Arguments;
use Geekbrains\LevelTwo\Blog\Commands\CreateUserCommand;
use Geekbrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use Geekbrains\LevelTwo\Blog\Exceptions\CommandException;
use Geekbrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

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

}