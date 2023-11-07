<?php

declare(strict_types=1);

namespace App;

class Response
{
    private string $viewName;
    private array $data;

    // TODO: Set response type. view ir redirect. or make separate file
    // Maybe -> if type "redirect" look for a redirect property in data
    // and then redirect to that :)

    public function __construct(string $viewName, array $data)
    {
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function getViewName(): string
    {
        return $this->viewName;
    }

    public function getData(): array
    {
        return $this->data;
    }

}