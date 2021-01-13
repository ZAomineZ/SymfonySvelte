<?php

namespace App\Tests\Controller\API;

use App\Repository\ImageRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class ImageControllerTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * ImageControllerTest constructor.
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
        $this->imageRepository = self::$container->get(ImageRepository::class);

        $this->clearDatabase();
    }

    public function testIndexAction()
    {

    }
}