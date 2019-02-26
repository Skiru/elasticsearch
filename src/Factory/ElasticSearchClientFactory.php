<?php

namespace App\Factory;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticSearchClientFactory
{
    /**
     * @var Client
     */
    private $client;

    /**
     * ElasticSearchClientFactory constructor.
     */
    public function __construct()
    {
        $hosts = [
            'elasticsearch:9200'
        ];
        $this->client = ClientBuilder::create()
            ->setHosts($hosts)
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
