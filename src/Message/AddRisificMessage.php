<?php


namespace App\Message;


class AddRisificMessage
{

    private $risificUrl;

    public function __construct(string $risificUrl)
    {
        $this->risificUrl = $risificUrl;
    }

    public function getRisificUrl(): string
    {
        return $this->risificUrl;
    }

}