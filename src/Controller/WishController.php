<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Wish;
use App\Form\FormCreateType;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;

use App\Service\censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
class WishController extends AbstractController
{


    #[Route('/wish', name: 'app_wish')]
    public function index(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy([], ['dateCreated' => 'DESC']);

        return $this->render('wish/list.html.twig', [
            'wish' => $wishes,
            'poster_dir' => $this->getParameter('poster_dir'),
        ]);
    }


    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, wishRepository $wishRepository): Response
    {

        $wishesDetails = $wishRepository->find($id);
        return $this->render('wish/detail.html.twig', [
            'wishDetails' => $wishesDetails,
            'poster_dir' => $this->getParameter('poster_dir'),

        ]);
    }

    #[Route('/wish/new', name: 'app_wish_new')]
    #[Route('/wish/update/{id}', name: 'app_wish_update', requirements: ['id' => '\d+'])]
    public function new(?Wish $wish, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $isEditMode = $wish ? true : false;
        if(!$isEditMode) {
            $wish = new Wish();
        }

        $form = $this->createForm(FormCreateType::class, $wish);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dir = $this->getParameter('poster_dir');


            if ($form->get('posterFile')->getData() instanceof UploadedFile) {
                $posterFile = $form->get('posterFile')->getData();
                $fileName = $slugger->slug($wish->getTitle()) . '-' . uniqid() . '.' . $posterFile->guessExtension();
                $posterFile->move($dir, $fileName);
                $wish->setPosterFile($fileName);

            }
            if ($form->get('deleteImg')->getData() === true && $wish->getPosterFile() !== null) {
                $imageTrue = $dir . '/' . $wish->getPosterFile();
                if (\file_exists($imageTrue)) {
                    unlink($imageTrue);
                    $wish->setPosterFile(null);
                }

            }
            $entityManager->persist($wish);
            $entityManager->flush();
            $this->addFlash('success', 'Le souhait a été enregistré');
            return $this->redirectToRoute('app_wish');
        }

        return $this->render('wish/newWish.html.twig', [
            'form' => $form,
            'editMode' => $isEditMode,
            'wish' => $wish
        ]);
    }



//            if($form->get('posterFile')->getData() instanceof UploadedFile){
//                $posterFile = $form->get('posterFile')->getData();
//                $fileName =$slugger->slug($wish->getTitle()) . '-' . uniqid() . '.' . $posterFile->guessExtension();
//                $posterFile->move($dir, $fileName);
//                if($wish->getPosterFile() && \file_exists($dir. '/' . $wish->getPosterFile())) {
//                    unlink($dir . '/' . $wish->getPosterFile());
//                }
//
//                $wish->setPosterFile($fileName);
//            }
//            $entityManager->persist($wish);
//            $entityManager->flush();
//            $this->addFlash('success', 'Le souhait a été enregistrer');
//            return $this->redirectToRoute('app_wish');
//        }
//
//        return $this->render('wish/newWish.html.twig', [
//            'form' => $form,
//            'editMode' => $isEditMode,
//        ]);
//    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/wish/delete/{id}', name: 'app_wish_delete', requirements: ['id' => '\d+'])]
    public function delete(Wish $wish, Request $request, EntityManagerInterface $em): Response
{
    $em->remove($wish);
    $em->flush();
    $this->addFlash('success', 'Le souhait a été supprimer');

        return $this->redirectToRoute('app_wish');
    }
//test
}
