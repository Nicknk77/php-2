<?php

namespace Geekbrains\LevelTwo\Blog;


class Post
{
    private UUID $uuid;
    private User $user;
    private string $title;
    private string $text;


    public function __construct(UUID $uuid, User $user, string $title, string $text) {
        $this->uuid = $uuid;
        $this->user = $user;
        $this->title = $title;
        $this->text = $text;
    }

    public function __toString(){
        return $this->user . ' пишет пост с названием: <<' . $this->title . '>>, и текстом: <<' . $this->text . '>>' . PHP_EOL;
    }

    public function uuid(): UUID{
        return $this->uuid;
    }

    public function getUser(): User{
        return $this->user;
    }

    public function getTitle(): string{
        return $this->title;
    }

    public function getText(): string{
        return $this->text;
    }
}