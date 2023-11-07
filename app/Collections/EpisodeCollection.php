<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\Episode;

class EpisodeCollection
{
    private array $episodes;

    public function add(Episode $episode)
    {
        $this->episodes[$episode->getId()] = $episode;
    }

    public function getEpisodes(): array
    {
        return $this->episodes;
    }

    public function getEpisodeById(int $id): Episode
    {
        return $this->episodes[$id];
    }

    public function getEpisodeCount(): int
    {
        return count($this->episodes);
    }

    public function getEpisodeByEpisodeCode(string $episodeCode): ?Episode
    {
        $episodeCode = strtoupper($episodeCode);
        $seasonNumber = substr($episodeCode, 1, strpos($episodeCode, 'E') - 1);
        $episodeNumber = substr($episodeCode, strpos($episodeCode, 'E') + 1);

        if (strlen($seasonNumber) < 2 || strlen($episodeNumber) < 2) {
            if (strlen($seasonNumber) < 2) $seasonNumber = 0 . $seasonNumber;
            if (strlen($episodeNumber) < 2) $episodeNumber = 0 . $episodeNumber;
            $episodeCode = 'S' . $seasonNumber . 'E' . $episodeNumber;
        }


        foreach ($this->episodes as $episode) {
            if ($episode->getEpisode() === $episodeCode) {
                var_dump(substr($episodeCode, strpos($episodeCode, 'E')));
                return $episode;
            }
        }
        return null;
    }

    public function getSeason(string $input): array
    {
        $episodesInSeason = [];
        foreach ($this->episodes as $episode) {
            if (strpos($episode->getEpisode(), $input) !== false) {
                $episodesInSeason[] = $episode;
            }
        }
        return $episodesInSeason;
    }

    public function searchEpisodes(string $input): array
    {
        $matches = [];
        foreach ($this->episodes as $episode) {
            if (stripos($episode->getName(), $input) !== false) {
                $matches[] = $episode;
            }
        }
        return $matches;
    }
}