<?php

namespace App\Controller\API\Admin;

use App\Entity\Tag;
use App\Helper\Entity\TagHelper;
use App\Helper\Response\ResponseJson;
use App\Repository\TagRepository;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api")]
class TagController extends AbstractController
{
    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var TagHelper
     */
    private TagHelper $tagHelper;
    /**
     * @var Validator
     */
    private Validator $validator;
    /**
     * @var ResponseJson
     */
    private ResponseJson $responseJson;

    /**
     * TagController constructor.
     * @param TagRepository $tagRepository
     * @param EntityManagerInterface $entityManager
     */
    #[Pure] public function __construct(TagRepository $tagRepository, EntityManagerInterface $entityManager)
    {
        $this->tagRepository = $tagRepository;
        $this->entityManager = $entityManager;

        $this->tagHelper = new TagHelper();
        $this->validator = new Validator();
        $this->responseJson = new ResponseJson();
    }

    /**
     * @return JsonResponse
     */
    #[Route("/admin/tags", methods: ["GET"])]
    public function index(): JsonResponse
    {
        $tags = $this->tagRepository->findAll();
        return new JsonResponse([
            'success' => true,
            'data' => [
                'tags' => $this->tagHelper->toArray($tags)
            ]
        ], 302);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    #[Route("/admin/tag/create", methods: ["POST"])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Get body data request
        $body = json_decode($request->request->get('body'));

        // Check the field name is already use for a tag
        $tag_by_name = $this->tagRepository->findByName($body->name);
        if ($tag_by_name) {
            return $this->responseJson->message(false, 'This name is already exist on a tag !');
        }

        // Set properties tag entity
        $tag = new Tag();
        $tag->setName($body->name);
        $tag->setSlug($body->slug);

        // Validate entity
        $errors = $validator->validate($tag);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }

        // create new tag entity if validate credentials
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return $this->responseJson->message(true, 'You are created your new tag !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    #[Route("/admin/tag/edit/{slug}", methods: ["GET"])]
    public function edit(string $slug): JsonResponse
    {
        // Check if slug params exist in our database
        $tag_by_slug = $this->tagRepository->findBySlug($slug);
        if (!$tag_by_slug) {
            return $this->responseJson->message(false, "The slug bad-slug tag don't exist in our database !");
        }

        $tag = $this->tagRepository->findBySlug($slug);
        return new JsonResponse([
            'success' => true,
            'data' => [
                'tag' => $this->tagHelper->toObject($tag)
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
    #[Route("/admin/tag/update/{slug}", methods: ["POST"])]
    public function update(Request $request, ValidatorInterface $validator, string $slug): JsonResponse
    {
        // Get body data request
        $body = json_decode($request->request->get('body'));

        // Check the field name is already use for a tag
        $tag_by_name = $this->tagRepository->findByName($body->name);
        if ($tag_by_name && $tag_by_name->getSlug() !== $slug) {
            return $this->responseJson->message(false, 'This name is already exist on a tag !');
        }

        // Set properties tag entity
        $tag = $this->tagRepository->findBySlug($slug);
        $tag->setName($body->name);
        $tag->setSlug($body->slug);

        // Validate entity
        $errors = $validator->validate($tag);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }

        // create new tag entity if validate credentials
        $this->tagRepository->update($tag);

        return $this->responseJson->message(true, 'You are updated your tag with success !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException|ORMException
     */
    #[Route("/admin/tag/delete/{slug}", methods: ["DELETE"])]
    public function delete(string $slug): JsonResponse
    {
        $tag = $this->tagRepository->findBySlug($slug);
        if (!$tag) {
            return $this->responseJson->message(false, 'You try to delete a tag who associate a bad slug !');
        }

        // Delete current tag entity
        $this->tagRepository->delete($tag);
        return $this->responseJson->message(true, 'You are deleted your tag with success !');
    }
}