<?php

namespace Geekbrains\LevelTwo\Blog;

use Geekbrains\LevelTwo\Person\Name;

class User
{
    private UUID $uuid;
    private Name $name;
    private string $username;
    // Переименовали поле password
    private string $hashedPassword;
//    private string $password;

    /**
     * @param UUID $uuid
     * @param Name $name
     * @param string $username
     */
    public function __construct(UUID $id, Name $name, string $hashedPassword, string $login)
    {
        $this->uuid = $id;
        $this->name = $name;
        $this->hashedPassword = $hashedPassword;
        $this->username = $login;
    }

    public function __toString() :string{
        return "Юзер $this->uuid с именем $this->name и логином $this->username";
    }
    public function hashedPassword(): string{
        return $this->hashedPassword;
    }

    // Функция для вычисления хеша
    private static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256',$uuid . $password);
    }
    // Функция для проверки предъявленного пароля
    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }

    // Функция для создания нового пользователя
    public static function createFrom(
        Name $name,
        string $password,
        string $username,
    ): self
    {
        return new self(
            $uuid = UUID::random(),
            $name,
            self::hash($password, $uuid),
            $username
        );
    }

    public function uuid(): UUID{
        return $this->uuid;
    }

    /**
     * @return Name
     */
    public function name() :Name{
        return $this->name;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function username(): string{
        return $this->username;
    }

    public function setUsername(string $username): void{
        $this->username = $username;
    }

}