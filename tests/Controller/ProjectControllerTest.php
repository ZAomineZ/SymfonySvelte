<?php

namespace App\Tests\Controller;

use App\DataFixtures\ProjectFixtures;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends WebApplicationTestCase
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
        $client->request('GET', '/admin/projects');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Projects');
    }

    public function testCreatePageProject()
    {
        $client = $this->client;
        $client->request('GET', '/admin/project/create');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Project Create Page');
    }

    public function testEditPageProject()
    {
        // Project fixture
        $this->loadFixtures([ProjectFixtures::class]);
        // Get last project
        $project = $this->getLastProject();

        $client = $this->client;
        $client->request('GET', '/admin/project/edit/' . $project->getSlug());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Project Edit Page');
    }

    /**
     * @return Project|null
     */
    private function getLastProject(): ?Project
    {
        /** @var ProjectRepository $repository */
        return $this->getLastEntity(ProjectRepository::class);
    }
}