<?php

namespace App\Tests\Controller\Module;

use App\Controller\API\Admin\ProjectController;
use App\DataFixtures\Category\CategoryFixtures;
use App\DataFixtures\Project\ProjectFixtures;
use App\DataFixtures\Tag\TagsFixtures;
use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\TagRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProjectWithTagControllerTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;
    /**
     * @var ProjectRepository|null
     */
    private ?ProjectRepository $projectRepository;
    /**
     * @var TagRepository|null
     */
    private ?TagRepository $tagRepository;

    /**
     * ProjectWithTagControllerTest constructor.
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

        $this->client = self::createClient();
        $this->projectRepository = self::$container->get(ProjectRepository::class);
        $this->tagRepository = self::$container->get(TagRepository::class);

        $this->clearDatabase();
    }

    public function testStoreActionWithTagEmpty()
    {
        // Load fixture category
        $this->loadFixtures([CategoryFixtures::class]);

        // Get first Category
        $category = $this->getFirstCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je suis une description pour le project "Project SEO"',
            'category' => $category->getSlug(),
            'tags' => '',
            'validate' => 0
         ];
        $client->request('POST', '/api/admin/project/create', [
            'body' => json_encode($data)
        ]);

        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response projects
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('Your field tags don\'t must blank !', $response->message);
    }

    public function testStoreActionWithTagDontExist()
    {
        // Load fixture category
        $this->loadFixtures([CategoryFixtures::class]);

        // Get first Category
        $category = $this->getFirstCategory();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je suis une description pour le project "Project SEO"',
            'category' => $category->getSlug(),
            'tags' => 'Test de test',
            'validate' => 0
        ];
        $client->request('POST', '/api/admin/project/create', [
            'body' => json_encode($data)
        ]);

        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Mock method allTagsExist
        $projectController = $this->getMockBuilder(ProjectController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $projectController
            ->expects($this->once())
            ->method('allTagsExist')
            ->with([['Test de test']]);

        // Assertion response projects
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('You can\'t associate your project to tag don\'t exist. !', $response->message);
    }

    public function testStoreActionWithTagSuccess()
    {
        // Load fixture category
        $this->loadFixtures([CategoryFixtures::class, TagsFixtures::class]);

        // Get first Category and Tag entity
        $category = $this->getFirstCategory();
        $tag = $this->getFirstTag();
        $_tag = $this->getEntityById(TagRepository::class, 2);
        $tags = $tag->getName() . ', ' . $_tag->getName();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je suis une description pour le project "Project SEO"',
            'category' => $category->getSlug(),
            'tags' => $tags,
            'validate' => 0
        ];
        $client->request('POST', '/api/admin/project/create', [
            'body' => json_encode($data)
        ]);

        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Mock method addAllTags
        $tags_mock_params = explode(', ', $tags);
        $project = $this->getMockBuilder(Project::class)->getMock();
        $projectController = $this->getMockBuilder(ProjectController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $projectController
            ->expects($this->once())
            ->method('allTagsExist')
            ->with([$project, $tags_mock_params]);

        // Assertion response projects
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your project with success !', $response->message);

        // Assertion tags to project
        $project = $this->getLastProject();
        $tags = $project->getTagNames();
        $this->assertTrue(in_array($tag->getName(), $tags));
        $this->assertTrue(in_array($_tag->getName(), $tags));
    }

    public function testUpdateActionWithTagEmpty()
    {
        // Load fixture category
        $this->loadFixtures([ProjectFixtures::class, CategoryFixtures::class]);

        // Get first Category and Project entity
        $category = $this->getFirstCategory();
        $project = $this->getFirstProject();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je suis une description pour le project "Project SEO"',
            'category' => $category->getSlug(),
            'tags' => '',
            'validate' => 0
        ];
        $client->request('POST', '/api/admin/project/update/' . $project->getSlug(), [
            'body' => json_encode($data)
        ]);

        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response projects
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('Your field tags don\'t must blank !', $response->message);
    }

    public function testUpdateActionWithTagDontExist()
    {
        // Load fixture category
        $this->loadFixtures([ProjectFixtures::class, CategoryFixtures::class]);

        // Get first Category and Project entity
        $category = $this->getFirstCategory();
        $project = $this->getFirstProject();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je suis une description pour le project "Project SEO"',
            'category' => $category->getSlug(),
            'tags' => 'Test de test',
            'validate' => 0
        ];
        $client->request('POST', '/api/admin/project/update/' . $project->getSlug(), [
            'body' => json_encode($data)
        ]);

        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Mock method allTagsExist
        $projectController = $this->getMockBuilder(ProjectController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $projectController
            ->expects($this->once())
            ->method('allTagsExist')
            ->with([['Test de test']]);

        // Assertion response projects
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('You can\'t associate your project to tag don\'t exist. !', $response->message);
    }

    public function testUpdateActionWithTagSuccess()
    {
        // Load fixture
        $this->loadFixtures([ProjectFixtures::class, CategoryFixtures::class, TagsFixtures::class]);

        // Get first Category, Tag and Project entity
        $project = $this->getFirstProject();
        $category = $this->getFirstCategory();
        $tag = $this->getFirstTag();
        $_tag = $this->getEntityById(TagRepository::class, 2);
        $tags = $tag->getName() . ', ' . $_tag->getName();

        $client = $this->client;
        $data = [
            'title' => 'Project SEO',
            'slug' => 'project-seo',
            'content' => 'Je suis une description pour le project "Project SEO"',
            'category' => $category->getSlug(),
            'tags' => $tags,
            'validate' => 0
        ];
        $client->request('POST', '/api/admin/project/update/' . $project->getSlug(), [
            'body' => json_encode($data)
        ]);

        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Mock method addAllTags
        $tags_mock_params = explode(', ', $tags);
        $project = $this->getMockBuilder(Project::class)->getMock();
        $projectController = $this->getMockBuilder(ProjectController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $projectController
            ->expects($this->once())
            ->method('allTagsExist')
            ->with([$project, $tags_mock_params]);

        // Assertion response projects
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are updated your project with success !', $response->message);

        // Assertion tags to project
        $project = $this->getLastProject();
        $tags = $project->getTagNames();
        $this->assertTrue(in_array($tag->getName(), $tags));
        $this->assertTrue(in_array($_tag->getName(), $tags));
    }

    /**
     * @return Project|null
     */
    protected function getLastProject(): ?Project
    {
        return $this->getLastEntity(ProjectRepository::class);
    }

    /**
     * @return Project|null
     */
    protected function getFirstProject(): ?Project
    {
        return $this->getFirstEntity(ProjectRepository::class);
    }

    /**
     * @return Category|null
     */
    protected function getFirstCategory(): ?Category
    {
        return $this->getFirstEntity(CategoryRepository::class);
    }

    /**
     * @return Tag|null
     */
    protected function getFirstTag(): ?Tag
    {
        return $this->getFirstEntity(TagRepository::class);
    }
}