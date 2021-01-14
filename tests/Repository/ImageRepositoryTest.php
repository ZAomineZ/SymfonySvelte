<?php

namespace App\Tests\Repository;

use App\DataFixtures\Image\ImageFixtures;
use App\DataFixtures\Image\ImagesFixtures;
use App\Repository\ImageRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ImageRepositoryTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;
    /**
     * @var ImageRepository|null
     */
    private ?ImageRepository $imageRepository;

    /**
     * ImageRepositoryTest constructor.
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
        $this->imageRepository = self::$container->get(ImageRepository::class);

        // Clear database
        $this->clearDatabase();
    }

    public function testFindByTitleWithValueNull()
    {
        // Load fixture
        $this->loadFixtures([ImageFixtures::class]);

        $image = $this->imageRepository->findByTitle('Image title bad');
        $this->assertNull($image);
    }

    public function testFindByTitle()
    {
        // Load fixture
        $this->loadFixtures([ImageFixtures::class]);

        $image = $this->imageRepository->findByTitle('Image test');
        $this->assertNotNull($image);
    }

    public function testFindBySlugWithValueNull()
    {
        // Load fixture
        $this->loadFixtures([ImageFixtures::class]);

        $image = $this->imageRepository->findBySlug('image-slug-bad');
        $this->assertNull($image);
    }

    public function testFindBySlug()
    {
        // Load fixture
        $this->loadFixtures([ImageFixtures::class]);

        $image = $this->imageRepository->findBySlug('image-test');
        $this->assertNotNull($image);
    }

    public function testFindAll()
    {
        // Load fixture
        $this->loadFixtures([ImagesFixtures::class]);

        $images = $this->imageRepository->findAll();
        $this->assertEquals(3, count($images));
    }
}
