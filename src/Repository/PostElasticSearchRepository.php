<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Factory\ElasticSearchClientFactory;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class PostElasticSearchRepository
{
    /**
     * @var ElasticSearchClientFactory
     */
    private $elasticSearchClientFactory;

    /**
     * PostElasticSearchRepository constructor.
     * @param ElasticSearchClientFactory $elasticSearchClientFactory
     */
    public function __construct(ElasticSearchClientFactory $elasticSearchClientFactory)
    {
        $this->elasticSearchClientFactory = $elasticSearchClientFactory;
    }

    public function findBySearchQuery(string $query, int $limit): array
    {
        $posts = [];
        $params = [
            'index' => 'articles',
            'type' => 'article',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            ['match' => ['title' => $query]],
                            ['match' => ['content' => $query]]
                        ]
                    ]
                ],
                "sort" => [
                    ["raw_date" => ["order" => "desc"]]
                ],
            ],
            'size' => $limit
        ];

        $results = $this->elasticSearchClientFactory->getClient()->search($params);

        return array_column($results['hits']['hits'], '_source');
    }
}