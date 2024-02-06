<?php

namespace App\Controller;


use App\Entity\Diet;
use App\Form\RestaurantType;
use App\Repository\DietRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DietController extends AbstractController
{
    #[Route('/regime', name: 'app_diet')]
    public function index(): Response
    {
        return $this->render('diet/index.html.twig', [
            'controller_name' => 'DietController',
        ]);
    }
}
