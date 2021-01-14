<?php

namespace App\Tests\Controller\API;

use App\DataFixtures\Image\ImageFixtures;
use App\DataFixtures\Image\ImagesFixtures;
use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Tests\WebApplicationTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageControllerTest extends WebApplicationTestCase
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
        // Load fixture
        $this->loadFixtures([ImagesFixtures::class]);

        $client = $this->client;
        $client->request('GET', '/api/admin/images');

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response projects
        $response = $this->getResponse($client, true);
        $images = $response['data']['images'] ?: [];
        $this->assertEquals(true, $response['success']);
        $this->assertEquals(3, count($images));
    }

    public function testStoreActionWithTitleExist()
    {
        // Load fixture
        $this->loadFixtures([ImageFixtures::class]);

        $client = $this->client;

        $file = (__DIR__ . '/../../files/images/test.jpg');
        $uploadFile = new UploadedFile($file, 'test.jpg');
        $data = [
            'title' => 'Image test',
            'slug' => 'image-test'
        ];
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($data)
        ], ['file' => $uploadFile]);

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response images
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('This title is already associate to the image.', $response->message);
    }

    public function testStoreActionWithSuccess()
    {
        $client = $this->client;

        $file = (__DIR__ . '/../../files/images/test.jpg');
        $uploadFile = new UploadedFile($file, 'test.jpg');
        $data = [
            'title' => 'Image test',
            'slug' => 'image-test'
        ];
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($data)
        ], ['file' => $uploadFile]);

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assertion Image entity
        $image = $this->getLastImage();
        $this->assertEquals('Image test', $image->getTitle());
        $this->assertEquals('image-test', $image->getSlug());
        $this->assertContains('test', $image->getPath());
        // Assertion upload file
        $this->assertionUploadFile($image);
    }

    public function testEditActionWithBadSlug()
    {
        $client = $this->client;
        $client->request('GET', '/api/admin/image/edit/bad-slug');

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('This slug don\'t associate to image to our database !', $response->message);
    }

    public function testEditActionWithSuccess()
    {
        // Load fixture
        $this->loadFixtures([]);

        $client = $this->client;
        $client->request('GET', '/api/admin/image/edit/bad-slug');

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('This slug don\'t associate to image to our database !', $response->message);
    }

    /**
     * @param Image $image
     */
    protected function assertionUploadFile(Image $image)
    {
        $file_upload = file_exists(__DIR__ . '/../../public/uploads/images/' . $image->getPath());
        $this->assertTrue($file_upload);
    }

    /**
     * @return Image|null
     */
    private function getLastImage(): ?Image
    {
        return $this->getLastEntity(ImageRepository::class);
    }
}