<?php

namespace App\Interface;

interface CreatedDateEntityInterface
{
    public function setCreatedAt(\DateTime $createdAt): CreatedDateEntityInterface;
}
