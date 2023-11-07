<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\CharacterCollection;
use Carbon\Carbon;
use DateTime;

class Episode
{
    private int $id;
    private string $name;
    private Carbon $airDate;
    private string $episode;
    private array $characterUrls;
    private Carbon $created;

    public function __construct(int    $id,
                                string $name,
                                string $airDate,
                                string $episode,
                                array  $characterUrls,
                                string $created)
    {
        $this->id = $id;
        $this->name = $name;
        $this->airDate = new Carbon(new DateTime($airDate));
        $this->episode = $episode;
        $this->characterUrls = $characterUrls;
        $this->created = new Carbon(new DateTime($created));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAirDate(): Carbon
    {
        return $this->airDate;
    }

    public function getEpisode(): string
    {
        return $this->episode;
    }

    public function getCreated(): Carbon
    {
        return $this->created;
    }

    public function getCharacterUrls(): array
    {
        return $this->characterUrls;
    }

    public function buildCharacterCollection(): CharacterCollection
    {
        $characterCollection = new CharacterCollection;
        foreach ($this->getCharacterUrls() as $characterData) {
            $data = json_decode(file_get_contents($characterData));
            $character = new Character(
                $data->id,
                $data->name,
                $data->image
            );
            $characterCollection->add($character);
        }

        return $characterCollection;

    }


}