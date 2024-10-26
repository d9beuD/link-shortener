<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);
        $shortenedUrl = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($link);
            $entityManager->flush();

            $shortenedUrl = $this->generateUrl('app_redirect', [
                'base36Id' => base_convert($link->getId(), 10, 36),
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'form' => $form,
            'shortenedUrl' => $shortenedUrl,
        ]);
    }

    #[Route('/{base36Id}', name: 'app_redirect')]
    public function redirectToDestination(string $base36Id, LinkRepository $linkRepository): Response
    {
        $id = base_convert($base36Id, 36, 10);
        $link = $linkRepository->find($id);

        if (!$link) {
            throw $this->createNotFoundException();
        }

        return $this->redirect($link->getDestinationUrl());
    }
}
