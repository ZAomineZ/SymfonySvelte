<?php

namespace App\Tests\Helper\Entity;

use App\DataFixtures\Project\ProjectFixtures;
use App\DataFixtures\Project\ProjectsFixtures;
use App\Entity\Project;
use App\Helper\Entity\ProjectHelper;
use App\Repository\ProjectRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class ProjectHelperTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var ProjectRepository|null
     */
    private ?ProjectRepository $projectRepository;

    /**
     * ProjectHelperTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();

        self::createClient();
        $this->projectRepository = self::$container->get(ProjectRepository::class);

        // Clear entity
        $this->clearDatabase();
    }

    public function testToArrayProjectEntity()
    {
        // Load Fixture
        $this->loadFixtures([ProjectsFixtures::class]);

        $projects = $this->projectRepository->findAll();
        $projectHelper = $this->getMockBuilder(ProjectHelper::class)->getMock();
        $new_projects = $projectHelper->toArray($projects);

        // Assertion
        $this->assertArrayHasKey('id', $new_projects[0]);
        $this->assertArrayHasKey('title', $new_projects[0]);
        $this->assertArrayHasKey('slug', $new_projects[0]);
        $this->assertArrayHasKey('content', $new_projects[0]);
        $this->assertArrayHasKey('category', $new_projects[0]);
        $this->assertArrayHasKey('tags', $new_projects[0]);
        $this->assertArrayHasKey('validate', $new_projects[0]);
        $this->assertArrayHasKey('created_at', $new_projects[0]);
    }

    public function testToObjectProjectEntity()
    {
        // Load Fixture
        $this->loadFixtures([ProjectFixtures::class]);

        $project = $this->getLastProject();
        $projectHelper = $this->getMockBuilder(ProjectHelper::class)->getMock();
        $new_project = $projectHelper->toObject($project);

        // Assertion
        $this->assertArrayHasKey('id', $new_project[0]);
        $this->assertArrayHasKey('title', $new_project[0]);
        $this->assertArrayHasKey('slug', $new_project[0]);
        $this->assertArrayHasKey('content', $new_project[0]);
        $this->assertArrayHasKey('category', $new_project[0]);
        $this->assertArrayHasKey('tags', $new_project[0]);
        $this->assertArrayHasKey('validate', $new_project[0]);
        $this->assertArrayHasKey('created_at', $new_project[0]);
    }

    /**
     * @return Project|null
     */
    private function getLastProject(): ?Project
    {
        return $this->getLastEntity(ProjectRepository::class);
    }
}
