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
        $client->request('GET', '/api/admin/projects');

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
        $client->request('POST', '/api/admin/project/create', [
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
        $client->request('POST', '/api/admin/project/create', [
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
        $client->request('POST', '/api/admin/project/create', [
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
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);
        // GET LAST PROJECT
        $project = $this->getLastProject();

        $client = $this->client;
        $client->request('GET', '/api/admin/project/edit/' . $project->getSlug());
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Project
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals($project->getSlug(), $response->data->project->slug);
    }

    public function testActionEditGetProjectEntityWithBadSlug()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);

        $client = $this->client;
        $client->request('GET', '/api/admin/project/edit/' . 'bad-slug');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Project
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals("The slug bad-slug project don't exist in our database !", $response->message);
    }

    public function testActionUpdateSuccessWithTitleIdentical()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);
        // GET LAST PROJECT
        $project = $this->getLastProject();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo-test',
            'content' => 'Je vous propose un content sur le seo de mon entreprise "test" !',
            'validate' => 1
        ];
        $client->request('POST', '/api/admin/project/update/' . $project->getSlug(), [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are updated your project with success !", $response->message);
        // Assertion Project
        $project_new = $this->getLastProject();

        $this->assertEquals('Project SEO', $project_new->getTitle());
        $this->assertEquals('project-seo-test', $project_new->getSlug());
        $this->assertEquals('Je vous propose un content sur le seo de mon entreprise test !', $project_new->getContent());
        $this->assertEquals(1, $project_new->getValidate());
        $this->assertEquals(1, count($this->projectRepository->findAll()));
    }

    public function testActionUpdateSuccess()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);
        // GET LAST PROJECT
        $project = $this->getLastProject();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO test',
            'slug' => 'project-seo-test',
            'content' => 'Je vous propose un content sur le seo de mon entreprise "test" !',
            'validate' => 1
        ];
        $client->request('POST', '/api/admin/project/update/' . $project->getSlug(), [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are updated your project with success !", $response->message);
        // Assertion Project
        $project_new = $this->getLastProject();

        $this->assertEquals('Project SEO test', $project_new->getTitle());
        $this->assertEquals('project-seo-test', $project_new->getSlug());
        $this->assertEquals('Je vous propose un content sur le seo de mon entreprise test !', $project_new->getContent());
        $this->assertEquals(1, $project_new->getValidate());
        $this->assertEquals(1, count($this->projectRepository->findAll()));
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