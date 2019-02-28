<?php

namespace App\Factory;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticSearchClientFactory
{
    const HOSTS = [
        'elasticsearch:9200'
    ];

    /**
     * @var Client
     */
    private $client;

    /**
     * ElasticSearchClientFactory constructor.
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(self::HOSTS)
            ->build();
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
