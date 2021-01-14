<?php

namespace App\Tests\Controller;

use App\DataFixtures\Image\ImageFixtures;
use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class ImageControllerTest extends WebApplicationTestCase
{
    use FixturesTrait;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

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

        $this->clearDatabase();
    }

    public function testIndexPage()
    {
        $client = $this->client;
        $client->request('GET', '/admin/images');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Images');
    }

    public function testCreatePage()
    {
        $client = $this->client;
        $client->request('GET', '/admin/image/create');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Create Image Page');
    }

    public function testEditPage()
    {
        // Load category fixture
        $this->loadFixtures([ImageFixtures::class]);
        // Get last image
        $image = $this->getLastImage();

        $client = $this->client;
        $client->request('GET', '/admin/image/edit/' . $image->getSlug());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Admin Image Edit Page');
    }

    /**
     * @return Image|null
     */
    private function getLastImage(): ?Image
    {
        return $this->getLastEntity(ImageRepository::class);
    }
}
