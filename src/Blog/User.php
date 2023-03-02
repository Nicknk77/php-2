<?php

namespace Geekbrains\LevelTwo\Blog;

use Geekbrains\LevelTwo\Person\Name;

class User
{
    private UUID $uuid;
    private Name $name;
    private string $username;

    /**
     * @param UUID $uuid
     * @param Name $name
     * @param string $username
     */
    public function __construct(UUID $id, Name $name, string $login)
    {
        $this->uuid = $id;
        $this->name = $name;
        $this->username = $login;
    }

    public function __toString() :string{
        return "Юзер $this->uuid с именем $this->name и логином $this->username";
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