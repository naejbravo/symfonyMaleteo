<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Form\DemoFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            return $this->redirectToRoute('home');
        }
        return $this->renderForm('maleteo/index.html.twig', 
        ["opinions"=>$opinions, "formDemo"=>$form]);
    }

    #[Route('/createOpinion', name: 'createOpinion')]


    public function createProduct(EntityManagerInterface $doctrine):void
    {
        $opinion = new Opinion();
        $opinion->setComment('Tras el enorme éxito internacional de su primera colaboración, "Bailar", que ganó un galardón en los Premios.');
        $opinion->setAuthor("Sergio Garnacho");
        $opinion->setCity('Tetuán, Madrid');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $doctrine->persist($opinion);

        // actually executes the queries (i.e. the INSERT query)
        $doctrine->flush();

        // return $this->render("base.html.twig");
    }
}
