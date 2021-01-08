<?php

namespace App\Controller\API;

use App\Entity\Project;
use App\Helper\DataHelper;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProjectController extends AbstractController
{
    /**
     * @var ProjectRepository
     */
    private ProjectRepository $projectRepository;
    /**s
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var DataHelper
     */
    private DataHelper $dataHelper;

    /**
     * ProjectController constructor.
     * @param ProjectRepository $projectRepository
     * @param EntityManagerInterface $entityManager
     */
    #[Pure] public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $entityManager)
    {
        $this->projectRepository = $projectRepository;
        $this->entityManager = $entityManager;
        $this->dataHelper = new DataHelper();
    }

    /**
     * @return JsonResponse
     */
    #[Route('/projects', name: 'api.project.index', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $data = $this->projectRepository->findAllValidate();
        $projects = $this->dataHelper->toArray($data);
        return new JsonResponse([
            'success' => true,
            'data' => [
                'projects' => $projects
            ]
        ], 302);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/project/create', name: 'api.project.store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        // Set data json body
        $body = json_decode($request->request->get('body'));

        // Check if the title already exist
        $project_exist = $this->projectRepository->findByTitle($body->title) ?
            $this->projectRepository->findByTitle($body->title)[0] : null;
        if ($project_exist) {
            return new JsonResponse([
                'success' => false,
                'message' => 'This title is already exist on the project !'
            ], 302);
        }

        // Set project entity
        $project = (new Project())
            ->setTitle($body->title)
            ->setSlug($body->slug)
            ->setContent($body->content)
            ->setValidate($body->validate);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        // Return response format json success
        return new JsonResponse([
            'success' => true,
            'message' => 'You are created your project with success !'
        ], 302);
    }
}