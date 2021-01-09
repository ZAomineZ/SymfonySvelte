<?php

namespace App\Controller\API\Admin;

use App\Entity\Project;
use App\Helper\ProjectHelper;
use App\Repository\ProjectRepository;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var Validator
     */
    private Validator $validator;
    /**
     * @var ProjectHelper
     */
    private ProjectHelper $projectHelper;

    /**
     * ProjectController constructor.
     * @param ProjectRepository $projectRepository
     * @param EntityManagerInterface $entityManager
     */
    #[Pure] public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $entityManager)
    {
        $this->projectRepository = $projectRepository;
        $this->entityManager = $entityManager;
        $this->projectHelper = new ProjectHelper();
        $this->validator = new Validator();
    }

    /**
     * @return JsonResponse
     */
    #[Route('/admin/projects', name: 'api.project.index', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $data = $this->projectRepository->findAllValidate();
        $projects = $this->projectHelper->toArray($data);
        return new JsonResponse([
            'success' => true,
            'data' => [
                'projects' => $projects
            ]
        ], 302);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    #[Route('/admin/project/create', name: 'api.project.store', methods: ["POST"])]
    public function store(Request $request, ValidatorInterface $validator): JsonResponse
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

        // Validate entity
        $errors = $validator->validate($project);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }
        // if valid data entity
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        // Return response format json success
        return new JsonResponse([
            'success' => true,
            'message' => 'You are created your project with success !'
        ], 302);
    }

    /**
     * @param string $slug
     * @return JsonResponse
     */
    #[Route('/admin/project/edit/{slug}', name: 'api.project.edit', methods: ["GET"])]
    public function edit(string $slug): JsonResponse
    {
        $project = $this->projectRepository->findBySlug($slug) ?
            $this->projectRepository->findBySlug($slug)[0] : null;
        if (!$project) {
            return new JsonResponse([
                'success' => false,
                'message' => "The slug $slug project don't exist in our database !"
            ], 302);
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'project' => $this->projectHelper->toObject($project)
            ]
        ], 302);
    }

    public function update()
    {

    }
}