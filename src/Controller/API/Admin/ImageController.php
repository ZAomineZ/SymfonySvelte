<?php

namespace App\Controller\API\Admin;

use App\Helper\Entity\ImageHelper;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api")]
class ImageController extends AbstractController
{
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
     * ImageController constructor.
     * @param ImageRepository $imageRepository
     * @param EntityManagerInterface $entityManager
     */
    #[Pure] public function __construct(ImageRepository $imageRepository, EntityManagerInterface $entityManager)
    {
        $this->imageRepository = $imageRepository;
        $this->entityManager = $entityManager;

        $this->imageHelper = new ImageHelper();
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
        ]);
    }
}