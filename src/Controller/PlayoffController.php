<?php

namespace App\Controller;

use App\Entity\Playoff;
use App\Form\PlayoffType;
use App\Repository\PlayoffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/playoff");
 */
class PlayoffController extends AbstractController
{
    /**
     * @Route("/", name="playoff_index", methods={"GET"});
     */
    public function index(PlayoffRepository $playoffRepository): Response
    {
        return $this->render('playoff/index.html.twig', [
            'playoffs' => $playoffRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="playoff_new", methods={"GET","POST"});
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $playoff = new Playoff();
        $form = $this->createForm(PlayoffType::class, $playoff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playoff);
            $entityManager->flush();

            return $this->redirectToRoute('playoff_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('playoff/new.html.twig', [
            'playoff' => $playoff,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="playoff_show", methods={"GET"});
     */
    public function show(Playoff $playoff): Response
    {
        return $this->render('playoff/show.html.twig', [
            'playoff' => $playoff,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="playoff_edit", methods={"GET", "POST"});
     */
    public function edit(Request $request, Playoff $playoff, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlayoffType::class, $playoff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('playoff_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('playoff/edit.html.twig', [
            'playoff' => $playoff,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="playoff_delete", methods={"POST"});
     */
    public function delete(Request $request, Playoff $playoff, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$playoff->getId(), $request->request->get('_token'))) {
            $entityManager->remove($playoff);
            $entityManager->flush();
        }

        return $this->redirectToRoute('playoff_index', [], Response::HTTP_SEE_OTHER);
    }
}
