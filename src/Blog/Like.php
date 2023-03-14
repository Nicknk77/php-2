<?php

namespace Geekbrains\LevelTwo\Blog;

class Like
{
    public function __construct(
        private UUID $uuid,
        private UUID $uuid_post,
        private UUID $uuid_user
    ){}

    public function __toString(){
        return $this->uuid_user . ' ставит лайк посту: ' . $this->uuid_post . PHP_EOL;
    }

    public function getUuid(): UUID{
        return $this->uuid;
    }

    public function setUuid(UUID $uuid): void{
        $this->uuid = $uuid;
    }

    public function getUuidPost(): UUID{
        return $this->uuid_post;
    }

    public function setUuidPost(UUID $uuid_post): void{
        $this->uuid_post = $uuid_post;
    }

    public function getUuidUser(): UUID{
        return $this->uuid_user;
    }

    public function setUuidUser(UUID $uuid_user): void{
        $this->uuid_user = $uuid_user;
    }

}