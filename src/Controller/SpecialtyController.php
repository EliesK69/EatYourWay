<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialtyController extends AbstractController
{
    #[Route('/specialty', name: 'app_specialty')]
    public function index(): Response
    {
        return $this->render('specialty/index.html.twig', [
            'controller_name' => 'SpecialtyController',
        ]);
    }
}
