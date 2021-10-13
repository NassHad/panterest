<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     */
    public function show(Pin $id, PinRepository $pinRepository): Response
    {
        $pin = $pinRepository->find($id);
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo): Response
    {
        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $userRepo->findOneBy(['email' => 'johndoe@example.com']);
            $pin->setUser($user);
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created!'); // 1er argument = type (ce qu'on veut)
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "PUT", "POST"})
     */
    public function edit(Pin $pin, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(PinType::class, $pin);

        // dd($form, '1');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', 'Pin successfully updated!'); // 1er argument = type (ce qu'on veut)
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'form' => $form->createView(),
            'pin' => $pin
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods={"GET"})
     */
    public function delete(Pin $pin, EntityManagerInterface $em): Response
    {
        $em->remove($pin);
        $em->flush();

        $this->addFlash('info', 'Pin successfully deleted'); // 1er argument = type (ce qu'on veut)
        return $this->redirectToRoute('app_home');
    }
}
