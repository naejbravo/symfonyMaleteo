<?php

namespace App\Controller;

use App\Entity\Demo;
use App\Entity\Opinion;
use App\Form\DemoFormType;
use App\Form\OpinionFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for all the actions of this controller
 */
#[IsGranted('ROLE_ADMIN')]
class MaleteoController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $doctrine, Request $request )
    {
        $repository = $doctrine->getRepository(Opinion::class);
        $opinions=$repository->findBy(array(),array(),3);


        $form = $this->createForm(DemoFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demo = $form->getData();
            $doctrine->persist($demo);
            $doctrine->flush();
            $this->addFlash('exito', 'Demo insertada correctamente');
            return $this->redirect($this->generateUrl('home')."#demo_form");
        }
        return $this->renderForm('maleteo/index.html.twig', 
        ["opinions"=>$opinions, "formDemo"=>$form]);
    }

    #[Route('/solicitudes', name: 'solicitudes')]
    public function showRequestDemo(EntityManagerInterface $doctrine)
    {
        $repository = $doctrine->getRepository(Demo::class);
        $demo=$repository->findBy(array(),array());
        return $this->render('maleteo/demo.html.twig', 
        ["demos"=>$demo]);
    }

    #[Route('/createOpinion', name: 'createOpinion')]
    public function createProduct(EntityManagerInterface $doctrine)
    {
        $opinion = new Opinion();
        $opinion->setComment('Tras el enorme éxito internacional de su primera colaboración, "Bailar", que ganó un galardón en los Premios.');
        $opinion->setAuthor("Sergio Garnacho");
        $opinion->setCity('Tetuán, Madrid');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $doctrine->persist($opinion);

        // actually executes the queries (i.e. the INSERT query)
        $doctrine->flush();
        return $this->redirectToRoute('home');
        // return $this->render("base.html.twig");
    }

    #[Route('/opiniones', name: 'opiniones')]
    public function createOpinion(EntityManagerInterface $doctrine, Request $request )
    {
        $form = $this->createForm(OpinionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinion = $form->getData();
            $doctrine->persist($opinion);
            $doctrine->flush();
            $this->addFlash('exito', 'Opinion insertada correctamente');
            return $this->redirect($this->generateUrl('opiniones')."#demo_form");
        }
        return $this->renderForm('maleteo/opinion.html.twig', 
        ["formOpinion"=>$form]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

}
