<?php

namespace App\Tests\Controller\Module;

use App\DataFixtures\Project\ProjectFixtures;
use App\DataFixtures\Tag\TagsFixtures;
use App\Entity\Project;
use App\Entity\Tag;
use App\Repository\ProjectRepository;
use App\Repository\TagRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class TaggableTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * TaggableTest constructor.
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

        $this->clearDatabase();
    }

    public function testAddTagProjectEntity()
    {
        // Load fixture
        $this->loadFixtures([ProjectFixtures::class, TagsFixtures::class]);

        // Get last project and tag entity
        $project = $this->getLastProject();
        $tag = $this->getFirstTag();

        $project->addTag($tag);
        $tags = $project->getTags();
        $tag_project = $tags->get(0);
        $this->assertEquals(1, count($tags));
        $this->assertEquals('Tag 1', $tag_project->getName());
    }

    public function testHasTagProjectEntity()
    {
        // Load fixture
        $this->loadFixtures([ProjectFixtures::class, TagsFixtures::class]);

        // Get last project and tag entity
        $project = $this->getLastProject();
        $tag = $this->getFirstTag();
        $tag_ = $this->getEntityById(TagRepository::class, 2);

        $project->addTag($tag);
        $this->assertTrue($project->hasTag($tag));
        $this->assertFalse($project->hasTag($tag_));
    }

    public function testGetTagsProjectEntity()
    {
        // Load fixture
        $this->loadFixtures([ProjectFixtures::class, TagsFixtures::class]);

        // Get last project and tag entity
        $project = $this->getLastProject();
        $tag = $this->getFirstTag();
        $tag_ = $this->getEntityById(TagRepository::class, 2);

        $project->addTag($tag);
        $project->addTag($tag_);

        $this->assertEquals(2, count($project->getTags()));
    }

    public function testRemoveTagProjectEntity()
    {
        // Load fixture
        $this->loadFixtures([ProjectFixtures::class, TagsFixtures::class]);

        // Get last project and tag entity
        $project = $this->getLastProject();
        $tag = $this->getFirstTag();
        $tag_ = $this->getEntityById(TagRepository::class, 2);

        $project->addTag($tag);
        $project->addTag($tag_);
        $this->assertEquals(2, count($project->getTags()));

        // Remove a tag to project entity
        $project->removeTag($tag);
        $this->assertEquals(1, count($project->getTags()));
        $tag = $project->getTags()->get(1);
        $this->assertEquals('Tag 2', $tag->getName());
    }

    public function testGetTagsTextProjectEntity()
    {
        // Load fixture
        $this->loadFixtures([ProjectFixtures::class, TagsFixtures::class]);

        // Get last project and tag entity
        $project = $this->getLastProject();
        $tag = $this->getFirstTag();
        $tag_ = $this->getEntityById(TagRepository::class, 2);

        $project->addTag($tag);
        $project->addTag($tag_);
        $this->assertEquals(2, count($project->getTags()));

        // Assertion on tags text getter
        $tagsText = $project->getTagsText();
        $this->assertEquals('Tag 1, Tag 2', $tagsText);
    }

    public function testGetTagNamesTextProjectEntity()
    {
        // Load fixture
        $this->loadFixtures([ProjectFixtures::class, TagsFixtures::class]);

        // Get last project and tag entity
        $project = $this->getLastProject();

        // Assertion tags name getter
        $project->setTagsText('Tag 1, Tag 2');

        $tagsName = $project->getTagNames();
        $this->assertTrue(in_array('Tag 1', $tagsName));
        $this->assertTrue(in_array('Tag 2', $tagsName));
    }

    /**
     * @return Project|null
     */
    protected function getLastProject(): ?Project
    {
        return $this->getLastEntity(ProjectRepository::class);
    }

    /**
     * @return Tag|null
     */
    protected function getFirstTag(): ?Tag
    {
        return $this->getFirstEntity(TagRepository::class);
    }
}