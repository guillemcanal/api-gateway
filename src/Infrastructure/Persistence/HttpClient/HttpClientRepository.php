<?php

namespace Infrastructure\Persistence\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\UriTemplate;

abstract class HttpClientRepository
{
    private $client;

    private $baseUri;

    public function __construct(Client $client, $baseUri)
    {
        $this->client = $client;
        $this->baseUri = $baseUri;
    }

    public function getAsync($path, array $options = [])
    {
        return $this->client->getAsync($this->buildUri($path, $options), $options);
    }

    public function get($path, array $options = [])
    {
        return $this->client->get($this->buildUri($path, $options), $options);
    }

    private function buildUri($path, array $options = [])
    {
        if (!isset($options['parameters'])) {
            $options['parameters'] = [];
        }

        return (new UriTemplate())->expand($this->baseUri . $path, $options['parameters']);
    }

}