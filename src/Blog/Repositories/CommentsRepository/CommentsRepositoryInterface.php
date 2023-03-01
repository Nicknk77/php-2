<?php

namespace Geekbrains\LevelTwo\Blog\Repositories;

use Geekbrains\LevelTwo\Blog\Comments;
use Geekbrains\LevelTwo\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function get(UUID $uuid) :self;
    public function save(Comments $ob) :void;

}