<?php

namespace App\Controller\Admin;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin", name="admin_")
 */
class EquipeController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(EquipeRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/equipe", name="app_equipe_index")
     */
    public function index(EquipeRepository $equipeRepository): Response
    {
        return $this->render('admin/equipe/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/equipe/new", name="app_equipe_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($equipe);
            $this->em->flush();

            return $this->redirectToRoute('admin_app_equipe_index');
        }

        return $this->renderForm('admin/equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }
/**
     * @Route("/equipe/show/{id}", name="app_equipe_show")
     */
    public function show(Equipe $equipe,Request $request,$id ): Response
    {
       //$repository=$this->getDoctrine()->getRepository(EquipeRepository::class);
       $equipes=$this->repository->getMyEntityWithRelatedEntity($equipe->getId());
        //$membre= $equipe->getUtilisateurs();

       
      
        //dd($equipes);
        //$utilisateurs= $this->repository->findbyEquipe($id);  
        return $this->render('admin/equipe/show.html.twig', [
            'equipes' => $equipes,
        ]);
    }

   /**
     * @Route("equipe/edit/{id}", name="app_equipe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipeRepository->add($equipe, true);

            return $this->redirectToRoute('admin_app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

  /**
     * @Route("/delete_equipe/{id}", name="app_equipe_delete", methods={"DELETE"})
     */

    public function delete(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $equipeRepository->remove($equipe, true);
        }

        return $this->redirectToRoute('admin_app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }


    
    


}
