<?php

//Создаём объект подключения к SQLite
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

//Вставляем строку в таблицу пользователей
//$connection->exec(
//    "INSERT INTO users (first_name, last_name) VALUES ('Petr', 'Petrov')"
//);
//$connection->exec(
//    "DELETE FROM users"
//);

$connection->exec("DROP TABLE users; CREATE TABLE users (
    uuid TEXT NOT NULL
    CONSTRAINT uuid_primary_key PRIMARY KEY,
    username TEXT NOT NULL
    CONSTRAINT username_unique_key UNIQUE,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL
)");


// запрос на создание таблицы posts
//create table posts
//(
//    uuid TEXT NOT NULL CONSTRAINT uuid_primary_key PRIMARY KEY,
//    author_uuid TEXT NOT NULL,
//    title TEXT NOT NULL,
//    text TEXT NOT NULL
//);

// запрос на создание таблицы comments
//create table comments
//(
//    uuid TEXT NOT NULL CONSTRAINT uuid_primary_key PRIMARY KEY,
//    post_uuid TEXT NOT NULL,
//    author_uuid TEXT NOT NULL,
//    text TEXT NOT NULL
//);