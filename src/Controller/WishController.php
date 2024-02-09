<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\FormCreateType;
use App\Repository\WishRepository;
use Container8HpaB9U\getFormCreateTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{



    #[Route('/wish', name: 'app_wish')]
    public function index(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy([], ['dateCreated' => 'DESC']);

        return $this->render('wish/list.html.twig', [
            'wish' => $wishes,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements : ['id' => '\d+'])]
    public function detail(int $id, wishRepository $wishRepository): Response{

        $wishesDetails = $wishRepository->find($id);
        return $this->render('wish/detail.html.twig', [
            'wishDetails' => $wishesDetails
        ]);
    }

    #[Route('/wish/new', name: 'app_wish_new')]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response {

        $wish = new Wish();
        $form = $this->createForm(FormCreateType::class, $wish);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($wish);
            $entityManager->flush();
        //    dump($wish);die;
            $this->addFlash('success', 'Le souhait a été enregistré');
            return $this->redirectToRoute('app_wish');
            }

        return $this->render('wish/newWish.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
