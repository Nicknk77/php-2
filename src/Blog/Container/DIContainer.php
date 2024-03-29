<?php

namespace Geekbrains\LevelTwo\Blog\Container;

use Geekbrains\LevelTwo\Blog\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

// Контейнер реализует контракт, отписанный в PSR-11
class DIContainer implements ContainerInterface
{
    // Массив правил создания объектов
    private array $resolvers = [];

    // Метод для добавления правил
    public function bind(string $type, $resolver): void{
        $this->resolvers[$type] = $resolver;
    }

    // Метод has из PSR-11

    public function has(string $test): bool
    {
        // Здесь мы просто пытаемся создать
        // объект требуемого типа
        try {
            $this->get($test);
        } catch (NotFoundException $e) {
            // Возвращаем false, если объект не создан...
            return false;
        }
        // и true, если создан
        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function get(string $type) :object {
        // Если есть правило для создания объекта типа $type,
        // (например, $type имеет значение
        // 'GeekBrains\.\.\UsersRepositoryInterface')
        if (array_key_exists($type, $this->resolvers)) {
            // .. тогда мы будем создавать объект того класса,
            // который указан в правиле
            // (например, 'GeekBrains\.\.\InMemoryUsersRepository')
            $typeToCreate = $this->resolvers[$type];
            // Если в контейнере для запрашиваемого типа
            // уже есть готовый объект — возвращаем его
            if (is_object($typeToCreate)) {
                return $typeToCreate;
            }
            return $this->get($typeToCreate);
        }
        if (!class_exists($type)) {
            throw new NotFoundException("Cannot resolve type: $type");
        }
        // Создаём объект рефлексии для запрашиваемого класса
        $reflectionClass = new ReflectionClass($type);
        // Исследуем конструктор класса
        $constructor = $reflectionClass->getConstructor();
        // Если конструктора нет -
        // просто создаём объект нужного класса
        if (null === $constructor) {
            return new $type();
        }
        // В этот массив мы будем собирать
        // объекты зависимостей класса
        $parameters = [];
        // Проходим по всем параметрам конструктора
        // (зависимостям класса)
        foreach ($constructor->getParameters() as $parameter) {
            // Узнаем тип параметра конструктора
            // (тип зависимости)
            $parameterType = $parameter->getType()->getName();
            // Получаем объект зависимости из контейнера
            $parameters[] = $this->get($parameterType);
        }
        // Создаём объект нужного нам типа
        // с параметрами
        return new $type(...$parameters);
    }

}