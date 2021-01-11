<?php

namespace App\Tests\Validation;

use App\Tests\WebApplicationTestCase;
use App\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ProjectValidationTest extends WebApplicationTestCase
{

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

        self::bootKernel();
        $this->clearDatabase();
    }

    public function testCallMockSuccessValidatorWithMethods()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => '',
            'slug' => 'test-de-test',
            'content' => 'Description de test',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
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

    public function testFailureFieldTitleBlank()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => '',
            'slug' => 'test-de-test',
            'content' => 'Description de test',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($body)
        ]);
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client, true);
        $errors = $response['errors'] ?: [];
        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Error validation, check your incorrect fields !', $response['message']);
        $this->assertIsBool(in_array('This value should not be blank.', $errors));
    }

    public function testFailureFieldTitleTooShort()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => 'test',
            'slug' => 'test-de-test',
            'content' => 'Description de test',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($body)
        ]);
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client, true);
        $errors = $response['errors'] ?: [];
        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Error validation, check your incorrect fields !', $response['message']);
        $this->assertIsBool(in_array('This value is too short. It should have 5 characters or more.', $errors));
    }

    public function testFailureFieldTitleTooLong()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => "Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie d'entre elles a été altérée par l'addition d'humour ou de mots aléatoires qui ne ressemblent pas une seconde à du texte standard. Si vous voulez utiliser un passage du Lorem Ipsum, vous devez être sûr qu'il n'y a rien d'embarrassant caché dans le texte. Tous les générateurs de Lorem Ipsum sur Internet tendent à reproduire le même extrait sans fin, ce qui fait de lipsum.com le seul vrai générateur de Lorem Ipsum. Iil utilise un dictionnaire de plus de 200 mots latins, en combinaison de plusieurs structures de phrases, pour générer un Lorem Ipsum irréprochable. Le Lorem Ipsum ainsi obtenu ne contient aucune répétition, ni ne contient des mots farfelus, ou des touches d'humour.",
            'slug' => 'test-de-test',
            'content' => 'Description de test',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($body)
        ]);
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client, true);
        $errors = $response['errors'] ?: [];
        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Error validation, check your incorrect fields !', $response['message']);
        $this->assertIsBool(in_array('This value is too long. It should have 255 characters or less.', $errors));
    }

    public function testFailureFieldContentBlank()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => "Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie d'entre elles a été altérée par l'addition d'humour ou de mots aléatoires qui ne ressemblent pas une seconde à du texte standard. Si vous voulez utiliser un passage du Lorem Ipsum, vous devez être sûr qu'il n'y a rien d'embarrassant caché dans le texte. Tous les générateurs de Lorem Ipsum sur Internet tendent à reproduire le même extrait sans fin, ce qui fait de lipsum.com le seul vrai générateur de Lorem Ipsum. Iil utilise un dictionnaire de plus de 200 mots latins, en combinaison de plusieurs structures de phrases, pour générer un Lorem Ipsum irréprochable. Le Lorem Ipsum ainsi obtenu ne contient aucune répétition, ni ne contient des mots farfelus, ou des touches d'humour.",
            'slug' => 'test-de-test',
            'content' => '',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($body)
        ]);
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client, true);
        $errors = $response['errors'] ?: [];
        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Error validation, check your incorrect fields !', $response['message']);
        $this->assertIsBool(in_array('This value should not be blank.', $errors));
    }

    public function testFailureFieldContentTooShort()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => "Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie d'entre elles a été altérée par l'addition d'humour ou de mots aléatoires qui ne ressemblent pas une seconde à du texte standard. Si vous voulez utiliser un passage du Lorem Ipsum, vous devez être sûr qu'il n'y a rien d'embarrassant caché dans le texte. Tous les générateurs de Lorem Ipsum sur Internet tendent à reproduire le même extrait sans fin, ce qui fait de lipsum.com le seul vrai générateur de Lorem Ipsum. Iil utilise un dictionnaire de plus de 200 mots latins, en combinaison de plusieurs structures de phrases, pour générer un Lorem Ipsum irréprochable. Le Lorem Ipsum ainsi obtenu ne contient aucune répétition, ni ne contient des mots farfelus, ou des touches d'humour.",
            'slug' => 'test-de-test',
            'content' => 'test',
            'validate' => 0
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($body)
        ]);
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client, true);
        $errors = $response['errors'] ?: [];
        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Error validation, check your incorrect fields !', $response['message']);
        $this->assertIsBool(in_array('This value is too short. It should have 5 characters or more.', $errors));
    }

    public function testFailureFieldValidateNull()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => "Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie d'entre elles a été altérée par l'addition d'humour ou de mots aléatoires qui ne ressemblent pas une seconde à du texte standard. Si vous voulez utiliser un passage du Lorem Ipsum, vous devez être sûr qu'il n'y a rien d'embarrassant caché dans le texte. Tous les générateurs de Lorem Ipsum sur Internet tendent à reproduire le même extrait sans fin, ce qui fait de lipsum.com le seul vrai générateur de Lorem Ipsum. Iil utilise un dictionnaire de plus de 200 mots latins, en combinaison de plusieurs structures de phrases, pour générer un Lorem Ipsum irréprochable. Le Lorem Ipsum ainsi obtenu ne contient aucune répétition, ni ne contient des mots farfelus, ou des touches d'humour.",
            'slug' => 'test-de-test',
            'content' => 'test',
            'validate' => null
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($body)
        ]);
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client, true);
        $errors = $response['errors'] ?: [];
        $this->assertEquals(false, $response['success']);
        $this->assertEquals('Error validation, check your incorrect fields !', $response['message']);
        $this->assertIsBool(in_array('This value should not be blank.', $errors));
    }

    public function testValidate()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'title' => 'Test de test',
            'slug' => 'test-de-test',
            'content' => 'Description de test !',
            'validate' => false
        ];
        $client->request('POST', '/api/project/create', [
            'body' => json_encode($body)
        ]);
        // Assertion Request
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        // Assertion Response
        $response = $this->getResponse($client);
        $this->assertEquals(true, $response->success);
        $this->assertEquals('You are created your project with success !', $response->message);
    }
}