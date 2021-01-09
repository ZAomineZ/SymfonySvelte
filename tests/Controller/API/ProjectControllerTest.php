<?php

namespace App\Tests\Controller\API;

use App\DataFixtures\ProjectFixtures;
use App\DataFixtures\ProjectValidateFixtures;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProjectControllerTest extends WebApplicationTestCase
{

    use FixturesTrait;

    /**
     * @var KernelBrowser $client
     */
    private KernelBrowser $client;
    /**
     * @var ProjectRepository|null
     */
    private ?ProjectRepository $projectRepository;

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
        $this->projectRepository = self::$container->get(ProjectRepository::class);
        // Clear entity
        $this->clearDatabase();
    }

    public function testAllProjectsValidate()
    {
        // Fixture projects
        $this->loadFixtures([ProjectValidateFixtures::class]);

        $client = $this->client;
        $client->request('GET', '/api/projects');

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response projects
        $response = $this->getResponse($client, true);
        $projects = $response['data']['projects'] ?: [];
        $this->assertEquals(true, $response['success']);
        $this->assertEquals(2, count($projects));
    }

    public function testSuccessPostCreateProjectApi()
    {
        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je vous propose un content sur le seo de mon entreprise !',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Project
        $project = $this->getLastProject();
        $this->assertEquals('Project SEO', $project->getTitle());
        $this->assertEquals('project-seo', $project->getSlug());
        $this->assertEquals('Je vous propose un content sur le seo de mon entreprise !', $project->getContent());
        $this->assertEquals(0, $project->getValidate());
        $this->assertEquals(1, count($this->projectRepository->findAll()));
    }

    public function testActionStoreProjectWithTitleExist()
    {
        // Fixture
        $this->loadFixtures([ProjectFixtures::class]);

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je vous propose un content sur le seo de mon entreprise !',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Project
        $response = $client->getResponse()->getContent();
        $responseBody = json_decode($response);
        $this->assertEquals('This title is already exist on the project !', $responseBody->message);
        $this->assertEquals(false, $responseBody->success);
        $this->assertEquals(1, count($this->projectRepository->findAll()));
    }

    public function testActionStoreProjectWithSlugEmpty()
    {
        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => '',
            'content' => 'Je vous propose un content sur le seo de mon entreprise !',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Project
        $project = $this->getLastProject();

        $this->assertEquals('Project SEO', $project->getTitle());
        $this->assertEquals('project-seo', $project->getSlug());
        $this->assertEquals('Je vous propose un content sur le seo de mon entreprise !', $project->getContent());
        $this->assertEquals(0, $project->getValidate());
        $this->assertEquals(1, count($this->projectRepository->findAll()));

        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your project with success !', $response->message);
    }

    public function testActionEditGetProjectEntity()
    {

    }

    /**
     * @return Project|null
     */
    private function getLastProject(): ?Project
    {
        /** @var ProjectRepository $repository */
        $repository = self::$container->get(ProjectRepository::class);
        return $repository->find(1);
    }
}