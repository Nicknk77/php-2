<?php

namespace Geekbrains\LevelTwo\Blog\Exceptions;

// Согласно PSR-11, исключение, описывающее ситуацию,
// когда объект не найден в контейнере,
// должно реализовать контракт NotFoundExceptionInterface
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends AppException implements NotFoundExceptionInterface
{

}