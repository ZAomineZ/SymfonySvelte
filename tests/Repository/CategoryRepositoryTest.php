<?php

namespace App\Tests\Repository;

use App\DataFixtures\Category\CategoriesFixtures;
use App\DataFixtures\Category\CategoryFixtures;
use App\Repository\CategoryRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class CategoryRepositoryTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

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
        $this->categoryRepository = self::$container->get(CategoryRepository::class);
        // Clear entity
        $this->clearDatabase();
    }

    public function testFindByNameEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);

        $category = $this->categoryRepository->findByName('Anime');
        $this->assertNotNull($category);
    }

    public function testFindByNameEntityWithValueNull()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);

        $category = $this->categoryRepository->findByName('Bad Name');
        $this->assertNull($category);
    }

    public function testFindBySlugEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);

        $category = $this->categoryRepository->findBySlug('anime');
        $this->assertNotNull($category);
    }

    public function testFindBySlugEntityWithValueNull()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);

        $category = $this->categoryRepository->findBySlug('bad-slug');
        $this->assertNull($category);
    }

    public function testFindAllEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoriesFixtures::class]);

        $categories = $this->categoryRepository->findAll();
        $this->assertEquals(5, count($categories));
    }
}
