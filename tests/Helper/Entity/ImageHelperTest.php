<?php

namespace App\Tests\Helper\Entity;

use App\DataFixtures\Image\ImagesFixtures;
use App\DataFixtures\Image\ImageFixtures;
use App\Entity\Image;
use App\Helper\Entity\ImageHelper;
use App\Repository\ImageRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class ImageHelperTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var ImageRepository|null
     */
    private ?ImageRepository $imageRepository;

    /**
     * ImageHelperTest constructor.
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
        $this->imageRepository = self::$container->get(ImageRepository::class);

        // Clear entity
        $this->clearDatabase();
    }

    public function testToArrayImageEntity()
    {
        // Load Fixture
        $this->loadFixtures([ImagesFixtures::class]);

        $images = $this->imageRepository->findAll();
        $imageHelper = $this->getMockBuilder(ImageHelper::class)->getMock();
        $new_images = $imageHelper->toArray($images);

        // Assertion
        $this->assertArrayHasKey('id', $new_images[0]);
        $this->assertArrayHasKey('title', $new_images[0]);
        $this->assertArrayHasKey('slug', $new_images[0]);
        $this->assertArrayHasKey('path', $new_images[0]);
        $this->assertArrayHasKey('created_at', $new_images[0]);
    }

    public function testToObjectImageEntity()
    {
        // Load Fixture
        $this->loadFixtures([ImageFixtures::class]);

        $image = $this->getLastImage();
        $imageHelper = $this->getMockBuilder(ImageHelper::class)->getMock();
        $new_image = $imageHelper->toObject($image);

        // Assertion
        $this->assertArrayHasKey('id', $new_image);
        $this->assertArrayHasKey('title', $new_image);
        $this->assertArrayHasKey('slug', $new_image);
        $this->assertArrayHasKey('path', $new_image);
        $this->assertArrayHasKey('created_at', $new_image);
    }

    /**
     * @return Image|null
     */
    private function getLastImage(): ?Image
    {
        return $this->getLastEntity(ImageRepository::class);
    }
}
