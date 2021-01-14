<?php

namespace App\Tests\Controller\API;

use App\DataFixtures\Category\CategoryFixtures;
use App\DataFixtures\Tag\TagFixtures;
use App\DataFixtures\Tag\TagsFixtures;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class TagControllerTest extends WebApplicationTestCase
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
     * CategoryControllerTest constructor.
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

    public function testIndexAction()
    {
        // LOAD FIXTURES
        $this->loadFixtures([TagsFixtures::class]);

        $client = $this->client;
        $client->request('GET', '/api/admin/tags');
        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response projects
        $response = $this->getResponse($client, true);
        $tags = $response['data']['tags'] ?: [];
        $this->assertEquals(true, $response['success']);
        $this->assertEquals(3, count($tags));
    }

    public function testPostCreateTag()
    {
        $client = $this->client;
        $data = [
            'name' => 'Manga',
            'slug' => 'manga'
        ];
        $client->request('POST', '/api/admin/tag/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Tag
        $tag = $this->getLastTag();
        $this->assertEquals('Manga', $tag->getName());
        $this->assertEquals('manga', $tag->getSlug());
        $this->assertEquals(1, count($this->tagRepository->findAll()));
        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your new tag !', $response->message);
    }

    public function testPostCreateTagWithPropertyNameAlreadyExist()
    {
        // Fixture
        $this->loadFixtures([TagFixtures::class]);

        $client = $this->client;
        $data = [
            'name' => 'Manga',
            'slug' => 'manga'
        ];
        $client->request('POST', '/api/admin/tag/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Tag
        $response = $client->getResponse()->getContent();
        $responseBody = json_decode($response);
        $this->assertEquals('This name is already exist on a tag !', $responseBody->message);
        $this->assertEquals(false, $responseBody->success);
        $this->assertEquals(1, count($this->tagRepository->findAll()));
    }

    public function testPostCreateTagWithSlugEmpty()
    {
        $client = $this->client;
        $data = [
            'name' => 'Manga',
            'slug' => ''
        ];
        $client->request('POST', '/api/admin/tag/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Tag
        $tag = $this->getLastTag();

        $this->assertEquals('Manga', $tag->getName());
        $this->assertEquals('manga', $tag->getSlug());
        $this->assertEquals(1, count($this->tagRepository->findAll()));
        // Assertion Response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your new tag !', $response->message);
    }

    public function testActionEditGetTagEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([TagFixtures::class]);
        // GET LAST TAG
        $tag = $this->getLastTag();

        $client = $this->client;
        $client->request('GET', '/api/admin/tag/edit/' . $tag->getSlug());
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Category
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals($tag->getSlug(), $response->data->tag->slug);
    }

    public function testActionEditGetTagEntityWithBadSlug()
    {
        $client = $this->client;
        $client->request('GET', '/api/admin/tag/edit/' . 'bad-slug');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Category
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals("The slug bad-slug tag don't exist in our database !", $response->message);
    }

    public function testActionUpdateSuccessWithNameIdentical()
    {
        // LOAD FIXTURE
        $this->loadFixtures([TagFixtures::class]);
        // GET LAST TAG
        $tag = $this->getLastTag();

        $client = $this->client;
        $data = [
            'name' => 'Manga',
            'slug' => 'manga'
        ];
        $client->request('POST', '/api/admin/tag/update/' . $tag->getSlug(), [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are updated your tag with success !", $response->message);
        // Assertion Project
        $tag_new = $this->getLastTag();

        $this->assertEquals('Manga', $tag_new->getName());
        $this->assertEquals('manga', $tag_new->getSlug());
        $this->assertEquals(1, count($this->tagRepository->findAll()));
    }

    public function testActionUpdateSuccess()
    {
        // LOAD FIXTURE
        $this->loadFixtures([TagFixtures::class]);
        // GET LAST TAG
        $tag = $this->getLastTag();

        $client = $this->client;
        $data = [
            'name' => 'Anime',
            'slug' => 'anime'
        ];
        $client->request('POST', '/api/admin/tag/update/' . $tag->getSlug(), [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are updated your tag with success !", $response->message);
        // Assertion Category
        $tag_new = $this->getLastTag();

        $this->assertEquals('Anime', $tag_new->getName());
        $this->assertEquals('anime', $tag_new->getSlug());
        $this->assertEquals(1, count($this->tagRepository->findAll()));
    }

    public function testDeleteActionWithBadSlug()
    {
        // LOAD FIXTURE
        $this->loadFixtures([TagFixtures::class]);

        $client = $this->client;
        $client->request('DELETE', '/api/admin/tag/delete/' . 'bad-slug');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals("You try to delete a tag who associate a bad slug !", $response->message);
        // Assertion Category
        $this->assertEquals(1, count($this->tagRepository->findAll()));
    }

    public function testDeleteActionSuccess()
    {
        // LOAD FIXTURE
        $this->loadFixtures([TagFixtures::class]);
        // Get last tag
        $category = $this->getLastTag();

        $client = $this->client;
        $client->request('DELETE', '/api/admin/tag/delete/' . $category->getSlug());

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are deleted your tag with success !", $response->message);
        // Assertion Category
        $this->assertEquals(0, count($this->tagRepository->findAll()));
    }

    /**
     * @return Tag|null
     */
    private function getLastTag(): ?Tag
    {
        return $this->getLastEntity(TagRepository::class);
    }
}