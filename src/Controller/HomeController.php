<?php

namespace App\Controller;

use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(
        RestaurantRepository $restaurantRepository
    ): Response {
        return $this->render('pages/home.html.twig', [
            'restaurants' => $restaurantRepository->findByIsPublic(3)
        ]);
    }
}
