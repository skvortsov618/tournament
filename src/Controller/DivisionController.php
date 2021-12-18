<?php

namespace App\Controller;

use App\Entity\Division;
use App\Form\DivisionType;
use App\Repository\DivisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/division");
 */
class DivisionController extends AbstractController
{
    /**
     * @Route("/", name="division_index", methods={"GET"});
     */
    public function index(DivisionRepository $divisionRepository): Response
    {
        return $this->render('division/index.html.twig', [
            'divisions' => $divisionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="division_new", methods={"GET","POST"});
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $division = new Division();
        $form = $this->createForm(DivisionType::class, $division);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($division);
            $entityManager->flush();

            return $this->redirectToRoute('division_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('division/new.html.twig', [
            'division' => $division,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="division_show", methods={"GET"});
     */
    public function show(Division $division): Response
    {
        return $this->render('division/show.html.twig', [
            'division' => $division,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="division_edit", methods={"GET","POST"});
     */
    public function edit(Request $request, Division $division, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DivisionType::class, $division);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('division_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('division/edit.html.twig', [
            'division' => $division,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="division_delete", methods={"POST"});
     */
    public function delete(Request $request, Division $division, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$division->getId(), $request->request->get('_token'))) {
            $entityManager->remove($division);
            $entityManager->flush();
        }

        return $this->redirectToRoute('division_index', [], Response::HTTP_SEE_OTHER);
    }
}
