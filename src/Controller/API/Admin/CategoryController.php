<?php

namespace App\Controller\API\Admin;

use App\Entity\Category;
use App\Helper\CategoryHelper;
use App\Helper\Response\ResponseJson;
use App\Repository\CategoryRepository;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api")]
class CategoryController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;
    /**
     * @var CategoryHelper
     */
    private CategoryHelper $categoryHelper;
    /**
     * @var Validator
     */
    private Validator $validator;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ResponseJson
     */
    private ResponseJson $responseJson;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $entityManager
     */
    #[Pure] public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;

        $this->categoryHelper = new CategoryHelper();
        $this->validator = new Validator();
        $this->responseJson = new ResponseJson();
    }

    /**
     * @return JsonResponse
     */
    #[Route("/admin/categories", methods: ["GET"])]
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->findAll();
        return new JsonResponse([
            'success' => true,
            'data' => [
                'categories' => $this->categoryHelper->toArray($categories)
            ]
        ], 302);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    #[Route("/admin/category/create", methods: ["POST"])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Get body data JSON
        $body = json_decode($request->request->get('body'));

        // Check if name exist in our database
        $category_by_name = $this->categoryRepository->findByName($body->name);
        if ($category_by_name) {
            return $this->responseJson->message(false, 'This name is already exist on a category !');
        }

        // Create new category
        $category = (new Category())
            ->setName($body->name)
            ->setSlug($body->slug)
            ->setContent($body->content);

        // Validate entity
        $errors = $validator->validate($category);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }
        // if valid data entity
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->responseJson->message(true, 'You are created your category with success !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    #[Route("/admin/category/edit/{slug}", methods: ["GET"])]
    public function edit(string $slug): JsonResponse
    {
        // Check if slug param exist in our database
        $category = $this->categoryRepository->findBySlug($slug);
        if (!$category) {
            return $this->responseJson->message(false, "The slug bad-slug category don't exist in our database !");
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'category' => $this->categoryHelper->toObject($category)
            ]
        ], 302);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    #[Route("/admin/category/update/{slug}", methods: ["POST"])]
    public function update(Request $request, ValidatorInterface $validator, string $slug): JsonResponse
    {
        // Get body data JSON
        $body = json_decode($request->request->get('body'));

        // Get current category entity
        $category_current = $this->categoryRepository->findBySlug($slug);

        // Check if name exist in our database
        $category_by_name = $this->categoryRepository->findByName($body->name);
        if ($category_by_name && $category_by_name->getSlug() !== $slug) {
            return $this->responseJson->message(false, 'This name is already exist on a category !');
        }

        // Create new category
        $category = $category_current
            ->setName($body->name)
            ->setSlug($body->slug)
            ->setContent($body->content);

        // Validate entity
        $errors = $validator->validate($category);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }
        // if valid data entity
        $this->categoryRepository->update($category);

        return $this->responseJson->message(true, 'You are updated your category with success !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException|ORMException
     */
    #[Route("/admin/category/delete/{slug}", methods: ["DELETE"])]
    public function delete(string $slug): JsonResponse
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if (!$category) {
            return $this->responseJson->message(false, 'You try to delete a category who associate a bad slug !');
        }

        // Delete category current
        $this->categoryRepository->delete($category);
        return $this->responseJson->message(true, 'You are deleted your category with success !');
    }
}