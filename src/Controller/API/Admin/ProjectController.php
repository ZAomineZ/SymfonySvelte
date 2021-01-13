<?php

namespace App\Controller\API\Admin;

use App\Controller\API\Admin\Module\Taggable;
use App\Entity\Project;
use App\Helper\Entity\ProjectHelper;
use App\Helper\Response\ResponseJson;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\TagRepository;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class ProjectController extends AbstractController
{

    use Taggable;

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
     * @var ResponseJson
     */
    private ResponseJson $responseJson;
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;
    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository;

    /**
     * ProjectController constructor.
     * @param ProjectRepository $projectRepository
     * @param CategoryRepository $categoryRepository
     * @param TagRepository $tagRepository
     * @param EntityManagerInterface $entityManager
     */
    #[Pure] public function __construct(
        ProjectRepository $projectRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->projectRepository = $projectRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->entityManager = $entityManager;

        $this->projectHelper = new ProjectHelper();
        $this->validator = new Validator();
        $this->responseJson = new ResponseJson();
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
     * @throws NonUniqueResultException
     */
    #[Route('/admin/project/create', name: 'api.project.store', methods: ["POST"])]
    public function store(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Set data json body
        $body = json_decode($request->request->get('body'));

        // Check if the title already exist
        $project_exist = $this->projectRepository->findByTitle($body->title);
        if ($project_exist) {
            return $this->responseJson->message(false, 'This title is already exist on the project !');
        }

        // Check if the category exist
        $category = $this->categoryRepository->findBySlug($body->category);
        if (!$category) {
            return $this->responseJson->message(false, 'You can\'t associate your project to category don\'t exist.');
        }

        // Check if the fields tags don't empty
        $tags_field = $body->tags ?: '';
        if (empty($tags_field)) {
            return $this->responseJson->message(false, 'Your field tags don\'t must blank !');
        }

        // Set properties to project entity
        $project = (new Project())
            ->setTitle($body->title)
            ->setSlug($body->slug)
            ->setContent($body->content)
            ->setCategory($category)
            ->setValidate($body->validate);
        $project->setTagsText($tags_field);


        // Validate entity
        $errors = $validator->validate($project);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }

        // Check if the tags exist in our database
        $tags = $project->getTagNames();
        if (!$this->allTagsExist($tags)) {
            return $this->responseJson->message(false, 'You can\'t associate your project to tag don\'t exist. !');
        }

        // Add tag to projects
        $this->addAllTags($project, $tags);

        // if valid data entity
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        // Return response format json success
        return $this->responseJson->message(true, 'You are created your project with success !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    #[Route('/admin/project/edit/{slug}', name: 'api.project.edit', methods: ["GET"])]
    public function edit(string $slug): JsonResponse
    {
        $project = $this->projectRepository->findBySlug($slug);
        if (!$project) {
            return $this->responseJson->message(false, "The slug $slug project don't exist in our database !");
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'project' => $this->projectHelper->toObject($project)
            ]
        ], 302);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param string $slug
     * @return JsonResponse
     * @throws ORMException
     */
    #[Route('/admin/project/update/{slug}', name: 'api.project.update', methods: ["POST"])]
    public function update(Request $request, ValidatorInterface $validator, string $slug): JsonResponse
    {
        // Set data json body
        $body = json_decode($request->request->get('body'));

        // Check if the title already exist
        $project_exist = $this->projectRepository->findByTitle($body->title);
        if ($project_exist && $project_exist->getSlug() !== $slug) {
            return $this->responseJson->message(false, 'This title is already exist on the project !');
        }

        // Check if the category exist
        $category = $this->categoryRepository->findBySlug($body->category);
        if (!$category) {
            return $this->responseJson->message(false, 'You can\'t associate your project to category don\'t exist.');
        }

        // Check if the fields tags don't empty
        $tags_field = $body->tags ?: '';
        if (empty($tags_field)) {
            return $this->responseJson->message(false, 'Your field tags don\'t must blank !');
        }

        // Set project entity
        $project = $this->projectRepository->findBySlug($slug);
        $project->setTitle($body->title)
            ->setSlug($body->slug)
            ->setContent($body->content)
            ->setCategory($category)
            ->setValidate($body->validate);
        $project->setTagsText($tags_field);

        // Validate entity
        $errors = $validator->validate($project);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }

        // Check if the tags exist in our database
        $tags = $project->getTagNames();
        if (!$this->allTagsExist($tags)) {
            return $this->responseJson->message(false, 'You can\'t associate your project to tag don\'t exist. !');
        }

        // Add tag to projects
        $this->addAllTags($project, $tags);

        // if valid data entity
        $this->projectRepository->update($project);

        // Return response format json success
        return $this->responseJson->message(true, 'You are updated your project with success !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws ORMException
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    #[Route('/admin/project/delete/{slug}', name: 'api.project.delete', methods: ["DELETE"])]
    public function delete(string $slug): JsonResponse
    {
        $project = $this->projectRepository->findBySlug($slug);
        if (!$project) {
            return $this->responseJson->message(false, 'You try to delete a project who associate a bad slug !');
        }

        // Delete project current
        $this->projectRepository->delete(($project));
        return $this->responseJson->message(true, 'You are deleted your project with success !');
    }
}