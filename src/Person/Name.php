<?php

namespace Geekbrains\LevelTwo\Person;

class Name
{
    private string $firstName;
    private string $lastName;

    /**
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function first(): string{
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void{
        $this->firstName = $firstName;
    }

    public function last(): string{
        return $this->lastName;
    }

    public function setLatsName(string $lastName): void{
        $this->lastName = $lastName;
    }

}