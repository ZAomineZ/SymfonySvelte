<?php

namespace App\Tests\Repository;

use App\DataFixtures\Project\ProjectFixtures;
use App\DataFixtures\Project\ProjectsFixtures;
use App\DataFixtures\Project\ProjectValidateFixtures;
use App\Repository\ProjectRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProjectRepositoryTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * TagRepositoryTest constructor.
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

    public function testFindByTitleEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);

        $project = $this->projectRepository->findByTitle('Project SEO');
        $this->assertNotNull($project);
    }

    public function testFindByTitleEntityWithValueNull()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);

        $project = $this->projectRepository->findByTitle('Project SEO test');
        $this->assertNull($project);
    }

    public function testFindBySlugEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);

        $project = $this->projectRepository->findBySlug('project-seo');
        $this->assertNotNull($project);
    }

    public function testFindBySlugEntityWithValueNull()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectFixtures::class]);

        $project = $this->projectRepository->findBySlug('project-seo-test');
        $this->assertNull($project);
    }

    public function testFindAllEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectsFixtures::class]);

        $projects = $this->projectRepository->findAll();
        $this->assertEquals(5, count($projects));
    }

    public function testFindAllValidateEntity()
    {
        // LOAD FIXTURE
        $this->loadFixtures([ProjectValidateFixtures::class]);

        $projects = $this->projectRepository->findAllValidate();
        $this->assertEquals(2, count($projects));
    }

}
