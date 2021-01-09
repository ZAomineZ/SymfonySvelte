<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    /**
     * ProjectRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @param string $title
     * @return array
     */
    public function findByTitle(string $title): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $slug
     * @return array
     */
    public function findBySlug(string $slug): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAllValidate(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.validate = :validate')
            ->setParameter('validate', 1)
            ->getQuery()
            ->getResult();
    }
}
