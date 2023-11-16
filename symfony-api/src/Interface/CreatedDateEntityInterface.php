<?php

namespace App\Interface;

interface CreatedDateEntityInterface
{
    public function setCreationDate(\DateTimeInterface $creationDate): CreatedDateEntityInterface;
}
