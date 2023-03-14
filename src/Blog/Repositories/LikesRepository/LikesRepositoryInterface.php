<?php

namespace Geekbrains\LevelTwo\Blog\Repositories\LikesRepository;

use Geekbrains\LevelTwo\Blog\Like;
use Geekbrains\LevelTwo\Blog\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $post) :void;
    public function get(UUID $uuid) :Like;
    public function delete(UUID $uuid): void;
    public function getByPostUuid(UUID $uuidPost): array;
}