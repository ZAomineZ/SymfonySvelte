<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @return Project|null
     * @throws NonUniqueResultException
     */
    public function findByTitle(string $title): ?Project
    {
        return $this->createQueryBuilder('p')
            ->where('p.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $slug
     * @return Project|null
     * @throws NonUniqueResultException
     */
    public function findBySlug(string $slug): ?Project
    {
        return $this->createQueryBuilder('p')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
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

    /**
     * Update entity project
     *
     * @param Project $project
     * @throws ORMException
     */
    public function update(Project $project)
    {
        $this->getEntityManager()->persist($project);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Project $project
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Project $project)
    {
        $this->getEntityManager()->remove($project);
        $this->getEntityManager()->flush();
    }
}
