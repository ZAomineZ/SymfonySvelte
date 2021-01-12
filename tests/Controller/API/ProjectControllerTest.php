<?php

namespace App\Tests\Controller\API;

use App\DataFixtures\Category\CategoryFixtures;
use App\DataFixtures\Project\ProjectFixtures;
use App\DataFixtures\Project\ProjectValidateFixtures;
use App\Entity\Category;
use App\Entity\Project;
use App\Repository\CategoryRepository;
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

    protected function setUp(): void
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
        // Load Fixture Category
        $this->loadFixtures([CategoryFixtures::class]);
        // Get last category
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je vous propose un content sur le seo de mon entreprise !',
            'category' => $category->getSlug(),
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
        // Load Fixture Category
        $this->loadFixtures([CategoryFixtures::class]);
        // Get last category
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => '',
            'content' => 'Je vous propose un content sur le seo de mon entreprise !',
            'category' => $category->getSlug(),
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

    public function testActionStoreProjectWithCategoryInvalid()
    {
        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je vous propose un content sur le seo de mon entreprise !',
            'category' => 'bad-slug-category',
            'validate' => 0
        ];
        $client->request('POST', '/api/admin/project/create', [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Project
        $this->assertEquals(0, count($this->projectRepository->findAll()));
        // Assertions Response
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('You can\'t associate your project to category don\'t exist.', $response->message);
    }

    public function testActionStoreProjectWithCategoryValid()
    {
        // Load Fixture Category
        $this->loadFixtures([CategoryFixtures::class]);
        // Get last category
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je vous propose un content sur le seo de mon entreprise !',
            'category' => $category->getSlug(),
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
        $this->assertEquals('Anime', $project->getCategory()->getName());
        $this->assertEquals(0, $project->getValidate());
        $this->assertEquals(1, count($this->projectRepository->findAll()));
        // Assertions Response
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
        $this->loadFixtures([ProjectFixtures::class, CategoryFixtures::class]);
        // GET LAST PROJECT AND CATEGORY
        $project = $this->getLastProject();
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo-test',
            'content' => 'Je vous propose un content sur le seo de mon entreprise "test" !',
            'category' => $category->getSlug(),
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
        $this->loadFixtures([ProjectFixtures::class, CategoryFixtures::class]);
        // GET LAST PROJECT AND CATEGORY
        $project = $this->getLastProject();
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO test',
            'slug' => 'project-seo-test',
            'content' => 'Je vous propose un content sur le seo de mon entreprise "test" !',
            'category' => $category->getSlug(),
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

    public function testActionUpdateWithCategoryInvalid()
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
            'category' => 'bad-slug-category',
            'validate' => 1
        ];
        $client->request('POST', '/api/admin/project/update/' . $project->getSlug(), [
            'body' => json_encode($data)
        ]);
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals("You can\'t associate your project to category don\'t exist.", $response->message);
    }

    public function testActionUpdateWithCategoryValid()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class, CategoryFixtures::class]);
        // GET LAST PROJECT AND CATEGORY
        $project = $this->getLastProject();
        $category = $this->getLastCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO test',
            'slug' => 'project-seo-test',
            'content' => 'Je vous propose un content sur le seo de mon entreprise "test" !',
            'category' => $category->getSlug(),
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
        $this->assertEquals('Anime', $project_new->getCategory()->getName());
        $this->assertEquals(1, $project_new->getValidate());
        $this->assertEquals(1, count($this->projectRepository->findAll()));
    }

    public function testActionDeleteWithBadSlug()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);

        $client = $this->client;
        $client->request('DELETE', '/api/admin/project/delete/' . 'bad-slug');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals("You try to delete a project who associate a bad slug !", $response->message);
        // Assertion project
        $this->assertEquals(1, count($this->projectRepository->findAll()));
    }

    public function testActionDeleteSuccess()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);
        // Get last project
        $project = $this->getLastProject();

        $client = $this->client;
        $client->request('DELETE', '/api/admin/project/delete/' . $project->getSlug());
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Request
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals("You are deleted your project with success !", $response->message);
        // Assertion project
        $this->assertEquals(0, count($this->projectRepository->findAll()));
    }

    /**
     * Recuperate last row to project entity
     *
     * @return Project|null
     */
    private function getLastProject(): ?Project
    {
        /** @var ProjectRepository $repository */
        $repository = self::$container->get(ProjectRepository::class);
        return $repository->find(1);
    }

    /**
     * Recuperate last row to category entity
     *
     * @return Category|null
     */
    private function getLastCategory(): ?Category
    {
        /** @var CategoryRepository $repository */
        $repository = self::$container->get(CategoryRepository::class);
        return $repository->find(1);
    }
}