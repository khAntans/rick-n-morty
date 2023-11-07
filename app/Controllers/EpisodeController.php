<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Collections\EpisodeCollection;
use App\Response;

class EpisodeController
{

    public function index(EpisodeCollection $episodes, array $vars): Response
    {
        $itemsPerPage = 6;
        $episodes = $episodes->getEpisodes();
        $page = $vars["page"] ?? 1;
        $episodesInPage = array_slice($episodes, $itemsPerPage * ($page - 1), $itemsPerPage, true);

        return new Response("episode/index", [
            "episodes" => $episodesInPage,
            "page" => $page,
            "totalEpisodeCount" => count($episodes),
            "itemsPerPage" => $itemsPerPage
        ]);
    }

    public function show(EpisodeCollection $episodes, array $vars): Response
    {
        $episodeId = (int)$vars["id"];
        $episode = $episodes->getEpisodeById($episodeId);
        $episodeCount = $episodes->getEpisodeCount();
        //$characters = $episode->getCharacters();
        $characters = $episode->buildCharacterCollection()->getCharacters();
        return new Response("episode/show", [
            "episode" => $episode,
            "id" => $episodeId,
            "totalEpisodeCount" => $episodeCount,
            "characters" => $characters
        ]);
    }

    public function search(EpisodeCollection $episodes, array $vars): ?Response
    {
        // TODO: search for the episode. if correct format- not found else 404
        $input = trim(strtoupper($vars['input']));
        if (preg_match("/S\d{1,2}E\d{1,2}/", $input)) {
            $episodeId = $episodes->getEpisodeByEpisodeCode($input)->getId();
            header('Location: /Episode/' . $episodeId);
            exit();
        }
        if (preg_match("/S\d{1,2}/", $input) && strlen($input) <= 3) {
            // TODO : list the season (need new view)
            $episodes = $episodes->getSeason($input);
            $season = (int)preg_replace("/[^0-9]/", "", $input);
            return new Response("episode/season", [
                'season' => $season,
                'episodes' => $episodes,
            ]);
        }
        if (is_numeric($input)) {
            if ($input > 0 && $input <= $episodes->getEpisodeCount()) {
                header('Location: /Episode/' . $input);
            }
        }

        $matches = $episodes->searchEpisodes($input);
        return new Response("episode/index", [
            "episodes" => $matches,
            "input" => $input
        ]);
    }

    public function seasonShow(EpisodeCollection $episodes, array $vars): Response
    {
        $season = $vars['id'];
        $input = $season;
        if (strlen($input) == 1) {
            $input = "S0" . $input[0];
        } elseif (strlen($input) == 2) $input = $input[0] . '0' . $input[1];
        $episodesInSeason = $episodes->getSeason($input);
        return new Response("episode/season", [
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    public function seasonIndex(EpisodeCollection $episodes): Response
    {
        $lastEpisode = $episodes->getEpisodeById($episodes->getEpisodeCount())->getEpisode();
        $lastSeason = (int)substr($lastEpisode, 1, 2);
        $seasons = [];
        for ($i = 1; $i <= $lastSeason; $i++) $seasons[] = $i;
        return new Response("episode/seasons", [
            "seasons" => $seasons,
        ]);
    }

}