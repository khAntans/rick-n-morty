<?php

declare(strict_types=1);

namespace App\Apis;

use App\Collections\EpisodeCollection;
use App\Models\Episode;
use GuzzleHttp\Client;

class RickAndMorty
{
    private Client $client;
    private const BASE_URL = "https://rickandmortyapi.com/api/episode";

    public function __construct()
    {
        $this->client = new Client();
    }


    public function fetch(): EpisodeCollection
    {
        $data = json_decode(($this->client->get(self::BASE_URL))->getBody()->getContents());
        $pages = $data->info->pages;
        $episodes = new EpisodeCollection();
        for ($i = 1; $i <= $pages; $i++) {
            $data = json_decode(($this->client->get(self::BASE_URL . "?page=" . $i))->getBody()->getContents());
            foreach ($data->results as $episode) {
                $id = $episode->id;
                $name = $episode->name;
                $airDate = $episode->air_date;
                $episodeCode = $episode->episode;
                $characterUrls = $episode->characters;
                $created = $episode->created;

                $episodes->add(new Episode($id, $name, $airDate, $episodeCode, $characterUrls, $created));
            }
        }
        return $episodes;
    }


}
