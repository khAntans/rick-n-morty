<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\Character;

class CharacterCollection
{
    private array $characters;

    public function add(Character $character)
    {
        $this->characters[] = $character;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

}