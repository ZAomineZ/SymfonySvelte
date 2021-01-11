<?php

namespace App\Tests\Controller;

use App\DataFixtures\Category\CategoryFixtures;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebApplicationTestCase
{

    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * ProjectControllerTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->client = self::createClient();

        $this->clearDatabase();
    }

    public function testIndexPage()
    {
        $client = $this->client;
        $client->request('GET', '/admin/categories');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Categories');
    }

    public function testCreatePage()
    {
        $client = $this->client;
        $client->request('GET', '/admin/category/create');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Create Category Page');
    }

    public function testEditPage()
    {
        // Load category fixture
        $this->loadFixtures([CategoryFixtures::class]);
        // Get last category
        $category = $this->getLastCategory();

        $client = $this->client;
        $client->request('GET', '/admin/categories/edit/' . $category->getSlug());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Create Category Page');
    }

    /**
     * @return Category
     */
    private function getLastCategory(): Category
    {
        return $this->getLastEntity(CategoryRepository::class);
    }

}