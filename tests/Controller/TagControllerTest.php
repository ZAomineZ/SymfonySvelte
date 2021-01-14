<?php

namespace App\Tests\Controller;

use App\DataFixtures\Tag\TagFixtures;
use App\Entity\Tag;
use App\Repository\ProjectRepository;
use App\Repository\TagRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class TagControllerTest extends WebApplicationTestCase
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
        $client->request('GET', '/admin/tags');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Tags');
    }

    public function testCreatePageProject()
    {
        $client = $this->client;
        $client->request('GET', '/admin/tag/create');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Tag Create Page');
    }

    public function testEditPageProject()
    {
        // Tag fixture
        $this->loadFixtures([TagFixtures::class]);
        // Get last tag
        $tag = $this->getLastTag();

        $client = $this->client;
        $client->request('GET', '/admin/tag/edit/' . $tag->getSlug());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Tag Edit Page');
    }

    /**
     * @return Tag|null
     */
    private function getLastTag(): ?Tag
    {
        /** @var ProjectRepository $repository */
        return $this->getLastEntity(TagRepository::class);
    }
}