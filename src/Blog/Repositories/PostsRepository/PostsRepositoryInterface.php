<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\PostsRepository;

use Geekbrains\LevelTwo\Blog\Post;
//use Geekbrains\LevelTwo\Blog\UUID;

interface PostsRepositoryInterface
{
//    public function get(UUID $uuid) :Post;
    public function save(Post $post) :void;
}