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

    public function getApi(string $var)
    {
        $response = $this->client->request(
            'GET',
            'https://coronavirusapi-france.now.sh/' . $var
        );
        return $response->toArray();
    }

    public function getDataFrance(): array
    {
        //https://coronavirusapi-france.now.sh/FranceLiveGlobalData
        return $this->getApi('FranceLiveGlobalData');
    }

    public function getAllData(): array
    {
        //https://coronavirusapi-france.now.sh/AllLiveData
        return $this->getApi('AllLiveData');
    }

    public function getDepartmentData($department): array
    {   //https://coronavirusapi-france.now.sh/LiveDataByDepartement?Departement=departement
        return $this->getApi('LiveDataByDepartement?Departement=' . $department);
    }

    public function getAllDataByDate($date): array
    {
        //https://coronavirusapi-france.now.sh/AllDataByDate?date=2020-04-19
        return $this->getApi('AllDataByDate?date=' . $date);
    }
}
