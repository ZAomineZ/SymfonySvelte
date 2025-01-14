<?php

namespace App\Controller\API\Admin;

use App\Controller\API\Admin\Module\UploadImageFile;
use App\Entity\Image;
use App\Helper\Entity\ImageHelper;
use App\Helper\Environment\Environment;
use App\Helper\Response\ResponseJson;
use App\Repository\ImageRepository;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api")]
class ImageController extends AbstractController
{

    use UploadImageFile;

    /**
     * @var ImageRepository
     */
    private ImageRepository $imageRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ImageHelper
     */
    private ImageHelper $imageHelper;
    /**
     * @var ResponseJson
     */
    private ResponseJson $responseJson;
    /**
     * @var Validator
     */
    private Validator $validator;
    /**
     * @var Environment
     */
    private Environment $environment;

    /**
     * ImageController constructor.
     * @param ImageRepository $imageRepository
     * @param EntityManagerInterface $entityManager
     * @param Environment $environment
     */
    #[Pure] public function __construct(
        ImageRepository $imageRepository,
        EntityManagerInterface $entityManager,
        Environment $environment
    )
    {
        $this->imageRepository = $imageRepository;
        $this->entityManager = $entityManager;
        $this->environment = $environment;

        $this->imageHelper = new ImageHelper();
        $this->responseJson = new ResponseJson();
        $this->validator = new Validator();
    }

    /**
     * @return JsonResponse
     */
    #[Route("/admin/images", methods: ["GET", "HEAD"])]
    public function index(): JsonResponse
    {
        $images = $this->imageRepository->findAll();
        return new JsonResponse([
            'success' => true,
            'data' => [
                'images' => $this->imageHelper->toArray($images)
            ]
        ], 302);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    #[Route("/admin/image/create", methods: ["POST"])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Get body and file data to request
        $body = json_decode($request->request->get('body'));
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        // Check if title field exist in our database
        $image_by_title = $this->imageRepository->findByTitle($body->title);
        if ($image_by_title) {
            return $this->responseJson->message(false, 'This title is already associate to the image.');
        }

        // Set properties image entity
        $image = (new Image())
            ->setTitle($body->title)
            ->setSlug($body->slug)
            ->setFile($file);
        $this->setFileImageEntity($image, $file);

        // Validate entity
        $errors = $validator->validate($image);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }

        // Treatment upload Image file
        $this->uploadFile($file);

        // Persist entity
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return $this->responseJson->message(true, 'You are created your image with success !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    #[Route("/admin/image/edit/{slug}")]
    public function edit(string $slug): JsonResponse
    {
        $image = $this->imageRepository->findBySlug($slug);
        if (!$image) {
            return $this->responseJson->message(false, 'This slug don\'t associate to image to our database !');
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'image' => $this->imageHelper->toObject($image)
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
     * @throws OptimisticLockException
     */
    #[Route("/admin/image/update/{slug}", methods: ["POST"])]
    public function update(Request $request, ValidatorInterface $validator, string $slug): JsonResponse
    {
        // Get body and file data to request
        $body = json_decode($request->request->get('body'));
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        // Check image entity exist
        $image = $this->imageRepository->findBySlug($slug);
        if (!$image) {
            return $this->responseJson->message(false, 'This slug don\'t associate to image to our database !');
        }

        // Check if title field exist in our database
        $image_by_title = $this->imageRepository->findByTitle($body->title);
        if ($image_by_title && $image->getSlug() !== $slug) {
            return $this->responseJson->message(false, 'This title is already associate to the image.');
        }

        // Set properties image entity
        $path_old_file = $image->getPath();
        $image = $image
            ->setTitle($body->title)
            ->setSlug($body->slug);
        if (!is_null($file)) {
            $image->setFile($file);
            $this->setFileImageEntity($image, $file);
        }

        // Validate entity
        $errors = $validator->validate($image);
        if ($this->validator->hasError($errors)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error validation, check your incorrect fields !',
                'errors' => $this->validator->getMessage($errors)
            ], 302);
        }

        // Treatment upload Image file
        if (!is_null($file)) {
            $this->uploadFile($file);
            $this->removeFile($path_old_file);
        }

        // Persist entity
        $this->imageRepository->update($image);

        return $this->responseJson->message(true, 'You are updated your image with success !');
    }

    /**
     * @param string $slug
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route("/admin/image/delete/{slug}", methods: ["GET"])]
    public function delete(string $slug): JsonResponse
    {
        $image = $this->imageRepository->findBySlug($slug);
        if (!$image) {
            return $this->responseJson->message(false, 'This slug don\'t associate to image to our database !');
        }

        // Deleted image path and entity
        $this->removeFile($image->getPath());
        $this->imageRepository->delete($image);

        return $this->responseJson->message(true, 'You are deleted your image with success !');
    }
}