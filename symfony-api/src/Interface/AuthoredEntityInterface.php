<?php

namespace App\Interface;

use App\Entity\User;


interface AuthoredEntityInterface
{
    public function setAuthor(User $user): AuthoredEntityInterface;
    public function getAuthor(): User;
}
