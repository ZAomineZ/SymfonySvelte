<?php

namespace App\Tests\Repository;

use App\DataFixtures\Tag\TagFixtures;
use App\DataFixtures\Tag\TagsFixtures;
use App\Repository\TagRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class TagRepositoryTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * TagRepositoryTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->tagRepository = self::$container->get(TagRepository::class);
        // Clear entity
        $this->clearDatabase();
    }

    public function testFindEntityByName()
    {
        // Load Fixture
        $this->loadFixtures([TagFixtures::class]);

        $tag = $this->tagRepository->findByName('Manga');
        $this->assertNotNull($tag);
    }

    public function testFindEntityByNameWithReturnNullEntity()
    {
        // Load Fixture
        $this->loadFixtures([TagFixtures::class]);

        $tag = $this->tagRepository->findByName('Bad Name');
        $this->assertNull($tag);
    }

    public function testFindEntityBySlug()
    {
        // Load Fixture
        $this->loadFixtures([TagFixtures::class]);

        $tag = $this->tagRepository->findBySlug('manga');
        $this->assertNotNull($tag);
    }

    public function testFindEntityBySlugWithReturnNullEntity()
    {
        // Load Fixture
        $this->loadFixtures([TagFixtures::class]);

        $tag = $this->tagRepository->findBySlug('bad-slug');
        $this->assertNull($tag);
    }

    public function testFindAllToEntity()
    {
        // Load Fixture
        $this->loadFixtures([TagsFixtures::class]);

        $tags = $this->tagRepository->findAll();
        $this->assertEquals(3, count($tags));
    }
}
