<?php

namespace App\Command;


use App\Factory\ElasticSearchClientFactory;
use App\Repository\PostRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateIndexCommand extends Command
{
    protected static $defaultName = 'app:create-post-index';

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var ElasticSearchClientFactory
     */
    private $elasticSearchClientFactory;

    /**
     * IndexPosts constructor.
     * @param PostRepository $postRepository
     * @param ElasticSearchClientFactory $elasticSearchClientFactory
     */
    public function __construct(
        PostRepository $postRepository,
        ElasticSearchClientFactory $elasticSearchClientFactory
    )
    {
        parent::__construct();

        $this->postRepository = $postRepository;
        $this->elasticSearchClientFactory = $elasticSearchClientFactory;
    }

    protected function configure()
    {
        $this->setDescription('Creating index for posts');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = [
            'index' => 'articles',
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    'article' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'long'
                            ],
                            'title' => [
                                'type' => 'text'
                            ],
                            'slug' => [
                                'type' => 'text'
                            ],
                            'summary' => [
                                'type' => 'text'
                            ],
                            'author' => [
                                'type' => 'text'
                            ],
                            'content' => [
                                'type' => 'text'
                            ],
                            'date' => [
                                'type' => 'text'
                            ],
                            'raw_date' => [
                                'type' => 'keyword'
                            ],
                            'url' => [
                                'type' => 'text'
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->elasticSearchClientFactory->getClient()->indices()->create($params);

        $output->writeln('Indexing completed');
    }
}