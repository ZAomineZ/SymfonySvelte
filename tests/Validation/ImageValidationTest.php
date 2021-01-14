<?php

namespace App\Tests\Validation;

use App\Tests\WebApplicationTestCase;
use App\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageValidationTest extends WebApplicationTestCase
{

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * ImageValidationTest constructor.
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

    public function testCallMockSuccessValidatorWithMethods()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => 'Image test',
            'slug' => ''
        ];
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($body)
        ]);
        // Mock validator
        $validator = $this->getMockBuilder(Validator::class)->getMock();
        $validator
            ->expects($this->once())
            ->method('hasError')
            ->willReturn(true);
        $validator
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn([]);
    }

    public function testValidateFieldTitleBlank()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => '',
            'slug' => 'test-de-test'
        ];
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($body)
        ]);
        $this->assertionValidate($client, 'This value should not be blank.');
    }

    public function testValidateFieldTitleTooShort()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => 'test',
            'slug' => 'test-de-test'
        ];
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($body)
        ]);
        $this->assertionValidate($client, 'This value is too short. It should have 5 characters or more.');
    }

    public function testValidateFieldTitleTooLong()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => "Contrairement à la croyance populaire, Lorem Ipsum n'est pas simplement un texte aléatoire. Il a ses racines dans un morceau de littérature latine classique de 45 avant JC, ce qui en fait plus de 2000 ans. Richard McClintock, professeur de latin au Hampden-Sydney College en Virginie, a recherché l'un des mots latins les plus obscurs, consectetur, d'un passage du Lorem Ipsum, et en parcourant les citations du mot dans la littérature classique, a découvert la source incontestable. Lorem Ipsum provient des sections 1.10.32 et 1.10.33 de de Finibus Bonorum et Malorum (Les Extrêmes du Bien et du Mal) de Cicéron, écrit en 45 av. Ce livre est un traité sur la théorie de l'éthique, très populaire à la Renaissance. La première ligne du Lorem Ipsum, provient d'une ligne de la section 1.10.32.",
            'slug' => 'test-de-test'
        ];
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($body)
        ]);
        $this->assertionValidate($client, 'This value is too long. It should have 255 characters or less.');
    }

    public function testValidateFieldFileWithExtensionJPEG()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => 'Image de test',
            'slug' => 'image-de-test'
        ];
        $path = (__DIR__ . '/../files/images/test.jpg');
        $file = new UploadedFile($path, 'test.jpg', 'image/jpeg');
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($body)
        ], ['file' => $file]);
        $this->assertionResponseSuccess($client);
    }

    public function testValidateFieldFileWithExtensionPNG()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => 'Image de test',
            'slug' => 'image-de-test'
        ];
        $path = (__DIR__ . '/../files/images/test.png');
        $file = new UploadedFile($path, 'test.png', 'image/png');
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($body)
        ], ['file' => $file]);
        $this->assertionResponseSuccess($client);
    }

    public function testValidateFieldFileWithExtensionGIF()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => 'Image de test',
            'slug' => 'image-de-test'
        ];
        $path = (__DIR__ . '/../files/images/test.gif');
        $file = new UploadedFile($path, 'test.gif');
        $client->request('POST', '/api/admin/image/create', [
            'body' => json_encode($body)
        ], ['file' => $file]);
        $this->assertionValidate($client, 'This file is not a valid image.');
    }

    /**
     * @param KernelBrowser $client
     * @param string $message
     */
    protected function assertionValidate(KernelBrowser $client, string $message)
    {
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client, true);
        $errors = $response['errors'] ?: [];
        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Error validation, check your incorrect fields !', $response['message']);
        $this->assertIsBool(in_array($message, $errors));
    }

    /**
     * @param KernelBrowser $client
     */
    protected function assertionResponseSuccess(KernelBrowser $client)
    {
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your image with success !', $response->message);
    }

}