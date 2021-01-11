<?php

namespace App\Tests;

use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use stdClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebApplicationTestCase extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param KernelBrowser $client
     * @param bool $associative
     * @return stdClass|array|null
     */
    public function getResponse(KernelBrowser $client, bool $associative = false): array|stdClass|null
    {
        $response = $client->getResponse()->getContent();
        return json_decode($response, $associative);
    }

    /**
     * @param string $class
     * @return mixed
     */
    public function getLastEntity(string $class): mixed
    {
        $repository = self::$container->get($class);
        return $repository->find(1);
    }

    protected function clearDatabase()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        //In case leftover entries exist
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        // Drop and recreate tables for all entities
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
}