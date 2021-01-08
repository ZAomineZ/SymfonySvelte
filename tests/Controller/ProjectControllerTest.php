<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends WebTestCase
{
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
    }

    public function testIndexPage()
    {
        $client = $this->client;
        $client->request('GET', '/projects');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Projects');
    }

    public function testCreatePageProject()
    {
        $client = $this->client;
        $client->request('GET', '/project/create');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Create project');
    }
}