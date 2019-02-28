<?php

namespace App\Command;

use App\Entity\Post;
use App\Factory\ElasticSearchClientFactory;
use App\Repository\PostRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexPosts extends Command
{
    protected static $defaultName = 'app:index-posts';

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var ElasticSearchClientFactory
     */
    private $elasticSearchClientFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * IndexPosts constructor.
     * @param PostRepository $postRepository
     * @param ElasticSearchClientFactory $elasticSearchClientFactory
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        PostRepository $postRepository,
        ElasticSearchClientFactory $elasticSearchClientFactory,
        UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct();

        $this->postRepository = $postRepository;
        $this->elasticSearchClientFactory = $elasticSearchClientFactory;
        $this->urlGenerator = $urlGenerator;
    }

    protected function configure()
    {
        $this->setDescription('Indexing all posts');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var Post $post
         */
        foreach ($this->postRepository->findAll() as $post) {
            $params = [
                'index' => 'articles',
                'type' => 'article',
                'id' => $post->getId(),
                'body' => [
                    'id' => $post->getId(),
                    'title' => $post->getTitle(),
                    'slug' => $post->getSlug(),
                    'summary' => $post->getSummary(),
                    'author' => $post->getAuthor()->getFullName(),
                    'content' => $post->getContent(),
                    'date' => $post->getPublishedAt()->format('m/d/Y'),
                    'raw_date' => $post->getPublishedAt()->format('m/d/Y'),
                    'url' => $this
                        ->urlGenerator
                        ->generate(
                            'blog_post',
                            ['slug' => $post->getSlug()],
                            UrlGeneratorInterface::ABSOLUTE_PATH
                        )
                ]
            ];
            $response = $this->elasticSearchClientFactory->getClient()->index($params);

            if ($response['result'] !== "created") {
                $output->writeln('Error during indexing.  Closing...');
                break;
            }
        }
        $output->writeln('Indexing completed');
    }
}