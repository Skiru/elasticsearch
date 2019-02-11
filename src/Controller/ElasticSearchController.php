<?php

namespace App\Controller;

use Elasticsearch\ClientBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ElasticSearchController extends AbstractController
{
    /**
     * @Route("/blog/elasticSearch")
     * @return JsonResponse
     */
    public function elasticSearch(): JsonResponse
    {
        $hosts = [
            'elasticsearch:9200'
        ];

        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();

//        Index an article
//        $params = [
//            'index' => 'articles',
//            'type' => 'article',
//            'body' => ['testField' => 'asfsafasfasfasfsa']
//        ];
//        $response = $client->index($params);


        //Get a document
//        $params = [
//            'index' => 'articles',
//            'type' => 'article',
//            'id' => '1'
//        ];
//        $response = $client->get($params);

        $q = 'a';
//        //Search for a document
//        $params = [
//            'index' => 'articles',
//            'type' => 'article',
//            'body' => [
//                'query' => [
//                    'match' => [
//                        'title' => $q,
//                        'slug' => $q,
//                        'summary' => $q,
//                        'content' => $q,
//                        'publishedAt' => $q
//                    ]
//                ]
//            ]
//        ];

        $params = [
            'index' => 'articles',
            'type' => 'article',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [ 'match' => [ 'title' => $q ] ],
                            [ 'match' => [ 'slug' => $q ] ],
                            [ 'match' => [ 'summary' => $q ] ],
                            [ 'match' => [ 'content' => $q ] ],
                            [ 'match' => [ 'publishedAt' => $q ] ],
                        ]
                    ]
                ]
            ]
        ];

        $response = $client->search($params);

        return $this->json($response);
    }

}