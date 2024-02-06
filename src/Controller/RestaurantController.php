<?php

namespace App\Controller;


use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class RestaurantController extends AbstractController
{
    /**
     * This controller display all restaurants
     *
     * @param RestaurantRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/restaurant', name: 'restaurant.index', methods: ['GET'])]
    public function index(
        RestaurantRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // Déterminer si l'utilisateur est connecté
        $user = $this->getUser();

        // Si un utilisateur est connecté, afficher tous ses restaurants (publics ou non) et les autres restaurants publics
        // Sinon, afficher uniquement les restaurants publics
        // Le premier paramètre peut être utilisé pour limiter le nombre de résultats, si nécessaire
        // Le second paramètre est l'utilisateur actuellement connecté (ou null si aucun utilisateur n'est connecté)
        $restaurantsQuery = $repository->findRestaurantsForUserOrPublic(null, $user);

        $restaurants = $paginator->paginate(
            $restaurantsQuery, // Ceci est maintenant un tableau d'objets Restaurant ou une query adaptée à Doctrine Paginator
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/restaurant/index.html.twig', [
            'restaurants' => $restaurants,
        ]);
}

    #[Route('/restaurant/communaute', 'restaurant.community', methods: ['GET'])]
    public function indexPublic(
        RestaurantRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $cache = new FilesystemAdapter();
        $data = $cache->get('restaurants', function (ItemInterface $item) use ($repository) {
            $item->expiresAfter(15);
            return $repository->findByIsPublic(null);
        });

        $restaurants = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/restaurant/community.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    /**
     * This controller allow us to create a new restaurant
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/restaurant/creation', 'restaurant.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant = $form->getData();
            $restaurant->setUser($this->getUser());

            $manager->persist($restaurant);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre restaurant a été créé avec succès !'
            );

            return $this->redirectToRoute('restaurant.index');
        }

        return $this->render('pages/restaurant/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow us to edit a restaurant
     *
     * @param Restaurant $restaurant
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === restaurant.getUser()")]
    #[Route('/restaurant/edition/{id}', 'restaurant.edit', methods: ['GET', 'POST'])]
    public function edit(
        Restaurant $restaurant,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant = $form->getData();

            $manager->persist($restaurant);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre restaurant a été modifié avec succès !'
            );

            return $this->redirectToRoute('restaurant.index');
        }

        return $this->render('pages/restaurant/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow us to delete a restaurant
     *
     * @param EntityManagerInterface $manager
     * @param Restaurant $restaurant
     * @return Response
     */
    #[Route('/restaurant/suppression/{id}', 'restaurant.delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_USER') and user === restaurant.getUser()")]
    public function delete(
        EntityManagerInterface $manager,
        Restaurant $restaurant
    ): Response {
        $manager->remove($restaurant);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre restaurant a été supprimé avec succès !'
        );

        return $this->redirectToRoute('restaurant.index');
    }

    /**
     * This controller allow us to see a restaurant if this one is public
     *
     * @param Restaurant $restaurant
     * @return Response
     */
    #[Security("is_granted(restaurant.getIsPublic() === true || user === restaurant.getUser())")]
    #[Route('/restaurant/{id}', 'restaurant.show', methods: ['GET', 'POST'])]
    public function show(
        Restaurant $restaurant,
    ): Response {
        return $this->render('pages/restaurant/show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }
}
