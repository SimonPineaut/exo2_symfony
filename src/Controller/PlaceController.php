<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\Picture;
use App\Form\PlaceType;
use App\Service\FileUploader;
use App\Repository\PlaceRepository;
use App\Repository\PictureRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

#[Route('/place')]
#[IsGranted('ROLE_USER')]
class PlaceController extends AbstractController
{
    #[Route('/', name: 'app_place_index', methods: ['GET'])]
    public function index(PlaceRepository $placeRepository): Response
    {
        $user = $this->getUser();
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findBy(['user' => $user->getId()]),
        ]);
    }

    #[Route('/new', name: 'app_place_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlaceRepository $placeRepository, SluggerInterface $sluggerInterface, FileUploader $fileUploader): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $place->setCreatedAt(new \DateTimeImmutable());
            $place->setUser($this->getUser());
            $place->setSlug($sluggerInterface->slug($place->getName())->lower());

            $pictures = $place->getPictures();
            foreach ($pictures as $picture) {
                $pictureFile = $picture->getPictureFile();
                $file = $fileUploader->upload($pictureFile, 'place');
                $picture->setFile($file);
                $picture->setCreatedAt(new DateTimeImmutable());
                $place->addPicture($picture);
            }
            $placeRepository->save($place, true);

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('place/new.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_place_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Place $place, SluggerInterface $sluggerInterface, PlaceRepository $placeRepository): Response
    {

        // Si l'utilisateur n'est pas le bon
        if ($this->getUser() != $place->getUser())
            throw new AccessDeniedException('Accès non autorisé !');

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $place->setModifiedAt(new \DateTimeImmutable());
            $place->setSlug($sluggerInterface->slug($place->getName())->lower());

            $placeRepository->save($place, true);

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_place_delete', methods: ['POST'])]
    public function delete(Request $request, Place $place, PlaceRepository $placeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $place->getId(), $request->request->get('_token'))) {
            $placeRepository->remove($place, true);
        }

        return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
    }
}
