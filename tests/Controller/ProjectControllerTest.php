<?php

namespace App\Tests\Controller;

use App\Tests\WebApplicationTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends WebApplicationTestCase
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

        $this->clearDatabase();
    }

    public function testIndexPage()
    {
        $client = $this->client;
        $client->request('GET', '/admin/projects');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Projects');
    }

    public function testCreatePageProject()
    {
        $client = $this->client;
        $client->request('GET', '/admin/project/create');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('title', 'Create project');
    }
}