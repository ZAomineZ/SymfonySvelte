<?php

namespace App\Tests\Helper\Entity;

use App\DataFixtures\Tag\TagFixtures;
use App\DataFixtures\Tag\TagsFixtures;
use App\Entity\Tag;
use App\Helper\Entity\TagHelper;
use App\Repository\TagRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class TagHelperTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var TagRepository|null
     */
    private ?TagRepository $tagRepository;

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
        $this->tagRepository = self::$container->get(TagRepository::class);

        // Clear entity
        $this->clearDatabase();
    }

    public function testToArrayTagEntity()
    {
        // Load Fixture
        $this->loadFixtures([TagsFixtures::class]);

        $tags = $this->tagRepository->findAll();
        $tagHelper = $this->getMockBuilder(TagHelper::class)->getMock();
        $new_tags = $tagHelper->toArray($tags);

        // Assertion
        $this->assertArrayHasKey('id', $new_tags[0]);
        $this->assertArrayHasKey('name', $new_tags[0]);
        $this->assertArrayHasKey('slug', $new_tags[0]);
        $this->assertArrayHasKey('created_at', $new_tags[0]);
    }

    public function testToObjectTagEntity()
    {
        // Load Fixture
        $this->loadFixtures([TagFixtures::class]);

        $tag = $this->getLastTag();
        $tagHelper = $this->getMockBuilder(TagHelper::class)->getMock();
        $new_tag = $tagHelper->toObject($tag);

        // Assertion
        $this->assertArrayHasKey('id', $new_tag);
        $this->assertArrayHasKey('name', $new_tag);
        $this->assertArrayHasKey('slug', $new_tag);
        $this->assertArrayHasKey('created_at', $new_tag);
    }

    /**
     * @return Tag|null
     */
    private function getLastTag(): ?Tag
    {
        return $this->getLastEntity(TagRepository::class);
    }
}
