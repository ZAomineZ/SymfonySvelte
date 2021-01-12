<?php

namespace App\Tests\Validation;

use App\Tests\WebApplicationTestCase;
use App\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class TagValidationTest extends WebApplicationTestCase
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
            'name' => '',
            'slug' => 'test-de-test'
        ];
        $client->request('POST', '/api/admin/tag/create', [
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

    public function testFailureFieldNameBlank()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'name' => '',
            'slug' => 'test-de-test'
        ];
        $client->request('POST', '/api/admin/tag/create', [
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

    public function testFailureFieldNameTooShort()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'name' => 'aa',
            'slug' => 'test-de-test'
        ];
        $client->request('POST', '/api/admin/tag/create', [
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
        $this->assertIsBool(in_array('This value is too short. It should have 3 characters or more.', $errors));
    }

    public function testFailureFieldNameTooLong()
    {
        $client = $this->client;
        // BODY request
        $body = [
            'name' => "Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie d'entre elles a été altérée par l'addition d'humour ou de mots aléatoires qui ne ressemblent pas une seconde à du texte standard. Si vous voulez utiliser un passage du Lorem Ipsum, vous devez être sûr qu'il n'y a rien d'embarrassant caché dans le texte. Tous les générateurs de Lorem Ipsum sur Internet tendent à reproduire le même extrait sans fin, ce qui fait de lipsum.com le seul vrai générateur de Lorem Ipsum. Iil utilise un dictionnaire de plus de 200 mots latins, en combinaison de plusieurs structures de phrases, pour générer un Lorem Ipsum irréprochable. Le Lorem Ipsum ainsi obtenu ne contient aucune répétition, ni ne contient des mots farfelus, ou des touches d'humour.",
            'slug' => 'test-de-test'
        ];
        $client->request('POST', '/api/admin/tag/create', [
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
        $this->assertIsBool(in_array('This value is too long. It should have 60 characters or less.', $errors));
    }

}