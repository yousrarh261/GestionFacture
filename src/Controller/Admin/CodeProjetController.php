<?php


namespace App\Controller\Admin;

use App\Entity\CodeProjet;
use App\Form\CodeProjetType;
use App\Repository\CodeProjetRepository;
use App\Repository\PrioriteTicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin", name="admin_")
 */
class CodeProjetController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(CodeProjetRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/codeProjet", name="codeProjet")
     */
    public function index(): Response
    {
        return $this->render('admin/Projets/index.html.twig', [
            "codeProjet" => $this->repository->findAll()
        ]);
    }

    /**
     * @Route("/codeProjet/new", name="codeProjet_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $codeProjet = new CodeProjet();
        $form = $this->createForm(CodeProjetType::class, $codeProjet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($codeProjet);
            $entityManager->flush();

            return $this->redirectToRoute('admin_codeProjet');
        }

        return $this->render('admin/Projets/new.html.twig', [
            'codeProjet' => $codeProjet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("codeProjet/edit/{id}", name="codeProjet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CodeProjet $codeProjet): Response
    {
        $form = $this->createForm(CodeProjetType::class, $codeProjet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_codeProjet');
        }

        return $this->render('admin/Projets/edit.html.twig', [
            'codeProjet' => $codeProjet,
            'form' => $form->createView(),
        ]);
    }

}
