<?php

namespace Geekbrains\LevelTwo\Person;

class Name
{
    private string $firstName;
    private string $latsName;

    /**
     * @param string $firstName
     * @param string $latsName
     */
    public function __construct(string $firstName, string $latsName)
    {
        $this->firstName = $firstName;
        $this->latsName = $latsName;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->latsName;
    }

}