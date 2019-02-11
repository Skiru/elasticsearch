<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\PostRepository;
use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateIndexForAllPosts extends Command
{
    protected static $defaultName = 'app:index-posts';

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * CreateIndexForAllPosts constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Indexing all posts')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Instantiate Elasticsearch client');

        $hosts = [
            'elasticsearch:9200'
        ];

        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();

        $output->writeln('Begin indexing all posts');

        $posts = $this->postRepository->findAll();

        /**
         * @var Post $post
         */
        foreach ($posts as $post) {
            $params = [
                'index' => 'articles',
                'type' => 'article',
                'body' => [
                    'title' => $post->getTitle(),
                    'slug' => $post->getSlug(),
                    'summary' => $post->getSummary(),
                    'content' => $post->getContent(),
                    'publishedAt' => $post->getPublishedAt()->format('m/d/Y'),
                ]
            ];
            $response = $client->index($params);

            if ($response['result'] !== "created") {
                $output->writeln('Error during indexing.  Closing...');
                break;
            }
        }
        $output->writeln('Indexing completed');
    }
}