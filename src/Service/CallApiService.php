<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDataFrance(): array
    {
        $response = $this->client->request(
            'GET',
            'https://coronavirusapi-france.now.sh/FranceLiveGlobalData'
        );

        return $response->toArray();
    }
}
