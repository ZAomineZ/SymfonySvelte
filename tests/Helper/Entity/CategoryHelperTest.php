<?php

namespace App\Tests\Helper\Entity;

use App\DataFixtures\Category\CategoriesFixtures;
use App\DataFixtures\Category\CategoryFixtures;
use App\Entity\Category;
use App\Helper\Entity\CategoryHelper;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class CategoryHelperTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var CategoryRepository|null
     */
    private ?CategoryRepository $categoryRepository;

    /**
     * ProjectHelperTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();

        self::createClient();
        $this->categoryRepository = self::$container->get(CategoryRepository::class);

        // Clear entity
        $this->clearDatabase();
    }

    public function testToArrayProjectEntity()
    {
        // Load Fixture
        $this->loadFixtures([CategoriesFixtures::class]);

        $categories = $this->categoryRepository->findAll();
        $categoryHelper = $this->getMockBuilder(CategoryHelper::class)->getMock();
        $new_categories = $categoryHelper->toArray($categories);

        // Assertion
        $this->assertArrayHasKey('id', $new_categories[0]);
        $this->assertArrayHasKey('name', $new_categories[0]);
        $this->assertArrayHasKey('slug', $new_categories[0]);
        $this->assertArrayHasKey('content', $new_categories[0]);
        $this->assertArrayHasKey('created_at', $new_categories[0]);
    }

    public function testToObjectProjectEntity()
    {
        // Load Fixture
        $this->loadFixtures([CategoryFixtures::class]);

        $category = $this->getLastCategory();
        $categoryHelper = $this->getMockBuilder(CategoryHelper::class)->getMock();
        $new_category = $categoryHelper->toObject($category);

        // Assertion
        $this->assertArrayHasKey('id', $new_category);
        $this->assertArrayHasKey('name', $new_category);
        $this->assertArrayHasKey('slug', $new_category);
        $this->assertArrayHasKey('content', $new_category);
        $this->assertArrayHasKey('created_at', $new_category);
    }

    /**
     * @return Category|null
     */
    private function getLastCategory(): ?Category
    {
        return $this->getLastEntity(CategoryRepository::class);
    }
}
