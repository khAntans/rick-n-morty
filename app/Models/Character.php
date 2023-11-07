<?php

declare(strict_types=1);

namespace App\Models;

class Character
{
    private int $id;
    private string $name;
    private string $imageUrl;

    public function __construct(int $id, string $name, string $imageUrl)
    {
        $this->id = $id;
        $this->name = $name;
        $this->imageUrl = $imageUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

}