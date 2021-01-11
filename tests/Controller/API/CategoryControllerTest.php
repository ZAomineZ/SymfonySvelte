<?php

namespace App\Tests\Controller\API;

use App\DataFixtures\Category\CategoriesFixtures;
use App\DataFixtures\Category\CategoryFixtures;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class CategoryControllerTest extends WebApplicationTestCase
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
     * CategoryControllerTest constructor.
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
        $this->categoryRepository = self::$container->get(CategoryRepository::class);
        // Clear entity
        $this->clearDatabase();
    }

    public function testFetchAllCategories()
    {
        // Fixture categories
        $this->loadFixtures([CategoriesFixtures::class]);

        $client = $this->client;
        $client->request('GET', '/api/admin/categories');

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response projects
        $response = $this->getResponse($client, true);
        $categories = $response['data']['categories'] ?: [];
        $this->assertEquals(true, $response['success']);
        $this->assertEquals(5, count($categories));
    }

    public function testSuccessPostCreateCategoryApi()
    {
        $client = $this->client;
        $data = [
            'name' => 'Anime',
            'slug' => 'anime',
            'content' => 'Je vous propose un content sur ma catégorie !'
        ];
        $client->request('POST', '/api/admin/category/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Category
        $category = $this->getLastCategory();
        $this->assertEquals('Anime', $category->getName());
        $this->assertEquals('anime', $category->getSlug());
        $this->assertEquals('Je vous propose un content sur ma catégorie !', $category->getContent());
        $this->assertEquals(1, count($this->categoryRepository->findAll()));
    }

    public function testActionStoreCategoryWithNameExist()
    {
        // Fixture
        $this->loadFixtures([CategoryFixtures::class]);

        $client = $this->client;
        $data = [
            'name' => 'Anime',
            'slug' => 'anime',
            'content' => 'Je vous propose un content sur ma catégorie !'
        ];
        $client->request('POST', '/api/admin/category/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Category
        $response = $client->getResponse()->getContent();
        $responseBody = json_decode($response);
        $this->assertEquals('This name is already exist on a category !', $responseBody->message);
        $this->assertEquals(false, $responseBody->success);
        $this->assertEquals(1, count($this->categoryRepository->findAll()));
    }

    public function testActionStoreCategoryWithSlugEmpty()
    {
        $client = $this->client;
        $data = [
            'name' => 'Anime',
            'slug' => '',
            'content' => 'Je vous propose un content sur ma catégorie !'
        ];
        $client->request('POST', '/api/admin/category/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Category
        $category = $this->getLastCategory();

        $this->assertEquals('Anime', $category->getName());
        $this->assertEquals('anime', $category->getSlug());
        $this->assertEquals('Je vous propose un content sur ma catégorie !', $category->getContent());
        $this->assertEquals(1, count($this->categoryRepository->findAll()));

        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your category with success !', $response->message);
    }

    public function testActionEditGetProjectEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);
        // GET LAST CATEGORY
        $category = $this->getLastCategory();

        $client = $this->client;
        $client->request('GET', '/api/admin/category/edit/' . $category->getSlug());
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Category
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals($category->getSlug(), $response->data->category->slug);
    }

    public function testActionEditGetCategoryEntityWithBadSlug()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);

        $client = $this->client;
        $client->request('GET', '/api/admin/category/edit/' . 'bad-slug');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Category
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals("The slug bad-slug category don't exist in our database !", $response->message);
    }

    public function testActionUpdateSuccessWithTitleIdentical()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);
        // GET LAST CATEGORY
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'name' => 'Anime',
            'slug' => 'animed',
            'content' => 'Je vous propose un content sur ma catégorie "animed" !'
        ];
        $client->request('POST', '/api/admin/category/update/' . $category->getSlug(), [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are updated your category with success !", $response->message);
        // Assertion Project
        $category_new = $this->getLastCategory();

        $this->assertEquals('Anime', $category_new->getName());
        $this->assertEquals('animed', $category_new->getSlug());
        $this->assertEquals('Je vous propose un content sur ma catégorie "animed" !', $category_new->getContent());
        $this->assertEquals(1, count($this->categoryRepository->findAll()));
    }

    public function testActionUpdateSuccess()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);
        // GET LAST CATEGORY
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'name' => 'Manga',
            'slug' => 'manga',
            'content' => 'Je vous propose un content sur ma catégorie "manga" !'
        ];
        $client->request('POST', '/api/admin/category/update/' . $category->getSlug(), [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are updated your category with success !", $response->message);
        // Assertion Category
        $category_new = $this->getLastCategory();

        $this->assertEquals('Manga', $category_new->getName());
        $this->assertEquals('manga', $category_new->getSlug());
        $this->assertEquals('Je vous propose un content sur ma catégorie "manga" !', $category_new->getContent());
        $this->assertEquals(1, count($this->categoryRepository->findAll()));
    }

    public function testDeleteActionWithBadSlug()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);

        $client = $this->client;
        $client->request('DELETE', '/api/admin/category/update/' . 'bad-slug');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals("You try to delete a category who associate a bad slug !", $response->message);
        // Assertion Category
        $this->assertEquals(1, count($this->categoryRepository->findAll()));
    }

    public function testDeleteActionSuccess()
    {
        // LOAD FIXTURE
        $this->loadFixtures([CategoryFixtures::class]);
        // Get last category
        $category = $this->getLastCategory();

        $client = $this->client;
        $client->request('DELETE', '/api/admin/category/update/' . $category->getSlug());

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are deleted your category with success !", $response->message);
        // Assertion Category
        $this->assertEquals(0, count($this->categoryRepository->findAll()));
    }

    /**
     * @return Category
     */
    private function getLastCategory(): Category
    {
        return $this->getLastEntity(CategoryRepository::class);
    }
}