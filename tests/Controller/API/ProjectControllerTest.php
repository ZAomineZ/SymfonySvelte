<?php

namespace App\Tests\Controller\API;

use App\DataFixtures\ProjectFixtures;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use stdClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
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
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

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

    protected function clearDatabase()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        //In case leftover entries exist
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        // Drop and recreate tables for all entities
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
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
        $client->request('POST', '/project/create', [
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
        $client->request('POST', '/project/create', [
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
        $client->request('POST', '/project/create', [
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

    /**
     * @param KernelBrowser $client
     * @return stdClass
     */
    private function getResponse(KernelBrowser $client): stdClass
    {
        $response = $client->getResponse()->getContent();
        return json_decode($response);
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