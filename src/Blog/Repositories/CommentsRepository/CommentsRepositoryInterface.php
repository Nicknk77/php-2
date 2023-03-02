<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\CommentsRepository;

use Geekbrains\LevelTwo\Blog\Comments;
use Geekbrains\LevelTwo\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function get(UUID $uuid) :Comments;
    public function save(Comments $comment) :void;

}