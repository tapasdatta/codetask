<?php

namespace App\User;

class User
{
    public function __construct(
        public string $username = "tapas",
        public string $email = "tapash.me@gmail.com"
    ) {
        //
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
