<?php

namespace App\Tests\Controller\API;

use App\DataFixtures\Image\ImageFixtures;
use App\DataFixtures\Image\ImagesFixtures;
use App\DataFixtures\Image\ImageWithPathValidFixtures;
use App\DataFixtures\Image\ImageDeleteFixtures;
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

    public function testStoreActionWithSlugEmpty()
    {
        $client = $this->client;

        $file = (__DIR__ . '/../../files/images/test.jpg');
        $uploadFile = new UploadedFile($file, 'test.jpg');
        $data = [
            'title' => 'Image test',
            'slug' => ''
        ];
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($data)
        ], ['file' => $uploadFile]);

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your image with success !', $response->message);

        // Assertion Image entity
        $image = $this->getLastImage();
        $this->assertEquals('Image test', $image->getTitle());
        $this->assertEquals('image-test', $image->getSlug());
        $this->assertContains('test', $image->getPath());
        // Assertion upload file
        $this->assertionUploadFile($image);
    }

    public function testStoreActionWithSuccess()
    {
        $client = $this->client;

        $file = (__DIR__ . '/../../files/images/test-2.jpg');
        $uploadFile = new UploadedFile($file, 'test-2.jpg');
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

        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your image with success !', $response->message);

        // Assertion Image entity
        $image = $this->getLastImage();
        $this->assertEquals('Image test', $image->getTitle());
        $this->assertEquals('image-test', $image->getSlug());
        $this->assertContains('test-2', $image->getPath());
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
        $this->loadFixtures([ImageFixtures::class]);
        // Get last image entity
        $image = $this->getLastImage();

        $client = $this->client;
        $client->request('GET', '/api/admin/image/edit/' . $image->getSlug());

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals($image->getSlug(), $response->data->image->slug);
    }

    public function testUpdateActionWithBadSlug()
    {
        $client = $this->client;
        $data = [
            'title' => 'Image test',
            'slug' => 'image-test'
        ];
        $client->request('POST', '/api/admin/image/update/' . 'bad-slug', [
            'body' => json_encode($data)
        ]);

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('This slug don\'t associate to image to our database !', $response->message);
    }

    public function testUpdateActionWithTitleIdentical()
    {
        // Load fixture
        $this->loadFixtures([ImageWithPathValidFixtures::class]);
        // Get last image entity
        $image = $this->getLastImage();
        $file_path = $image->getPath();

        $client = $this->client;
        $data = [
            'title' => 'Image valid',
            'slug' => 'image-valid'
        ];
        $file = (__DIR__ . '/../../files/images/test-update.jpg');
        $uploadFile = new UploadedFile($file, 'test-update.jpg');
        $client->request('POST', '/api/admin/image/update/' . $image->getSlug(), [
            'body' => json_encode($data)
        ], ['file' => $uploadFile]);

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assertion Image entity
        $image = $this->getLastImage();
        $this->assertEquals('Image valid', $image->getTitle());
        $this->assertEquals('image-valid', $image->getSlug());
        $this->assertContains('test-update', $image->getPath());

        // Assertion upload file
        $this->assertionUploadFile($image, $file_path);

        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are updated your image with success !', $response->message);
    }

    public function testUpdateActionSuccess()
    {
        // Load fixture
        $this->loadFixtures([ImageWithPathValidFixtures::class]);
        // Get last image entity
        $image = $this->getLastImage();
        $file_path = $image->getPath();

        $client = $this->client;
        $data = [
            'title' => 'Image valid test',
            'slug' => 'image-valid-test'
        ];
        $file = (__DIR__ . '/../../files/images/test-update.jpg');
        $uploadFile = new UploadedFile($file, 'test-update.jpg');
        $client->request('POST', '/api/admin/image/update/' . $image->getSlug(), [
            'body' => json_encode($data)
        ], ['file' => $uploadFile]);

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assertion Image entity
        $image = $this->getLastImage();
        $this->assertEquals('Image valid test', $image->getTitle());
        $this->assertEquals('image-valid-tes', $image->getSlug());
        $this->assertContains('test-update', $image->getPath());

        // Assertion upload file
        $this->assertionUploadFile($image, $file_path);

        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are updated your image with success !', $response->message);
    }

    public function testDeleteActionWithBadSlug()
    {
        // Load fixture
        $this->loadFixtures([ImageDeleteFixtures::class]);
        // Get last image entity
        $image = $this->getLastImage();
        $file_path = $image->getPath();

        $client = $this->client;
        $client->request('GET', '/api/admin/image/delete/' . 'bad-slug');

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assertion deleted file
        $this->assertionDeletedFile($file_path, true);

        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(false, $response->success);
        $this->assertEquals('This slug don\'t associate to image to our database !', $response->message);

        $this->assertEquals(1, count($this->imageRepository->findAll()));
    }

    public function testDeleteActionSuccess()
    {
        // Load fixture
        $this->loadFixtures([ImageDeleteFixtures::class]);
        // Get last image entity
        $image = $this->getLastImage();
        $file_path = $image->getPath();

        $client = $this->client;
        $client->request('GET', '/api/admin/image/delete/' . $image->getSlug());

        // Assertion request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Assertion deleted file
        $this->assertionDeletedFile($file_path);

        // Assertion response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are deleted your image with success !', $response->message);

        $this->assertEquals(0, count($this->imageRepository->findAll()));
    }

    /**
     * @param Image $image
     * @param string|null $path_old_file
     */
    protected function assertionUploadFile(Image $image, ?string $path_old_file = null)
    {
        $file_upload = file_exists(__DIR__ . '/../../public/uploads/images/' . $image->getPath());
        $this->assertTrue($file_upload);

        if (!is_null($path_old_file)) {
            $file_upload = file_exists(__DIR__ . '/../../public/uploads/images/' . $path_old_file);
            $this->assertFalse($file_upload);
        }
    }

    /**
     * @param string|null $path_old_file
     * @param bool $is_exist
     */
    protected function assertionDeletedFile(?string $path_old_file = null, bool $is_exist = false)
    {
        $file_upload = file_exists($path_old_file);
        $is_exist ? $this->assertTrue($file_upload) : $this->assertFalse($file_upload);
    }

    /**
     * @return Image|null
     */
    private function getLastImage(): ?Image
    {
        return $this->getLastEntity(ImageRepository::class);
    }
}