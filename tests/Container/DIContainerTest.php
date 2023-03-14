<?php

namespace GeekBrains\LevelTwo\UnitTests\Container;

use Geekbrains\LevelTwo\Blog\Container\DIContainer;
use Geekbrains\LevelTwo\Blog\Exceptions\NotFoundException;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\InMemoryUsersRepository;
use Geekbrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Описываем ожидаемое исключение
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            'Cannot resolve type: GeekBrains\LevelTwo\UnitTests\Container\SomeClass'
        );
        // Пытаемся получить объект несуществующего класса
        $container->get(SomeClass::class); //посредством конструкции ::class можно получить полное имя класса вместе с пространством имён
    }

    /**
     * @throws NotFoundException
     */
    public function testItResolvesClassWithoutDependencies(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Пытаемся получить объект класса без зависимостей
        $object = $container->get(SomeClassWithoutDependencies::class);
        // Проверяем, что объект, который вернул контейнер, имеет желаемый тип
        $this->assertInstanceOf(SomeClassWithoutDependencies::class, $object);
    }

    /**
     * @throws NotFoundException
     */
    public function testItResolvesClassByContract(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Устанавливаем правило, по которому
        // всякий раз, когда контейнеру нужно
        // создать объект, реализующий контракт
        // UsersRepositoryInterface, он возвращал бы
        // объект класса SqliteUsersRepository
        $container->bind(UsersRepositoryInterface::class,InMemoryUsersRepository::class);
        // Пытаемся получить объект класса,
        // реализующего контракт UsersRepositoryInterface
        $object = $container->get(UsersRepositoryInterface::class);
        // Проверяем, что контейнер вернул
        // объект класса SqliteUsersRepository
        $this->assertInstanceOf(InMemoryUsersRepository::class, $object);
    }

    /**
     * @throws NotFoundException
     */
    public function testItReturnsPredefinedObject(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Устанавливаем правило, по которому
        // всякий раз, когда контейнеру нужно
        // вернуть объект типа SomeClassWithParameter,
        // он возвращал бы предопределённый объект
        $container->bind(SomeClassWithParameter::class, new SomeClassWithParameter(42));
        // Пытаемся получить объект типа SomeClassWithParameter
        $object = $container->get(SomeClassWithParameter::class);
        // Проверяем, что контейнер вернул
        // объект того же типа
        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );
        // Проверяем, что контейнер вернул
        // тот же самый объект
        $this->assertSame(42, $object->value());
    }

    /**
     * @throws NotFoundException
     */
    public function testItResolvesClassWithDependencies(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Устанавливаем правило получения
        // объекта типа SomeClassWithParameter
        $container->bind(SomeClassWithParameter::class, new SomeClassWithParameter(42));
        // Пытаемся получить объект типа ClassDependingOnAnother
        $object = $container->get(ClassDependingOnAnother::class);
        // Проверяем, что контейнер вернул
        // объект нужного нам типа
        $this->assertInstanceOf(
            ClassDependingOnAnother::class,
            $object
        );
    }

}