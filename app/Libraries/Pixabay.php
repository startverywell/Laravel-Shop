<?php

namespace App\Libraries;

use GuzzleHttp\Client;

class Pixabay
{
    protected $client;

    protected $apiKey;

    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        
    }

    public function getImage($query)
    {
        $response = $this->client->request('GET', '', [
            'query' => [
                'key' => $this->apiKey,
                'q' => $query,
                'image_type' => 'photo',
                'per_page' => 1
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody()->getContents(), true);
            return $body['hits'][0]['webformatURL'];
        }
        return null;
    }

}
