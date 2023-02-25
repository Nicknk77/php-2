<?php

namespace Geekbrains\LevelTwo\Blog;

use Geekbrains\LevelTwo\Person\Person;

class Post
{
    private int $id;
    private Person $author;
    private string $title;
    private string $text;


    public function __construct(int $id, Person $author, string $title, string $text) {
        $this->id = $id;
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
    }

    public function __toString(){
        return $this->author . ' пишет пост с названием: <<' . $this->title . '>>, и текстом: <<' . $this->text . '>>' . PHP_EOL;
    }

    public function getAuthor(): Person{
        return $this->author;
    }

    public function getTitle(): string{
        return $this->title;
    }

    public function getText(): string{
        return $this->text;
    }
}