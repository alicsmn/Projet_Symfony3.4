<?php
/**
 * Created by PhpStorm.
 * User: alice simon
 * Date: 29/03/2018
 * Time: 07:42
 */

namespace AppBundle\Controller;

use AppBundle\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\AnnonceType;
use AppBundle\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



class AnnonceController extends Controller
{

    /**
     * @Route("/annonce/new", name="annonce_new")
     */
    public function newAction(Request $request){
        $annonce = new Annonce();
        $form = $this->createForm('AppBundle\Form\AnnonceType', $annonce);
        $form->handleRequest($request);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user_id = $user->getId();
        $annonce->setUser_id($user_id);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $annonce->getPhoto();
            $fileName =  md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),$fileName
            );
            $annonce->setPhoto($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('annonce_view');
        }
        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/annonce", name="annonce_view")
     */
    public function showAction(Request $request){
        $annonceslist = $this->getDoctrine()
            ->getRepository(Annonce::class)
            ->findAll();

        $annonce  = $this->get('knp_paginator')->paginate(
            $annonceslist,
            $request->query->get('page', 1),8);
        return $this->render('annonce/show.html.twig', [
            'Annonce' => $annonce,
        ]);
    }
    /**
     * @Route("/annonce/{id}/edit", name="annonce_edit")
     */
    public function editAction(Request $request, $id)
    {
        /*if (isset($_SERVER['HTTP_REFERER'])) {
            $path = parse_url($_SERVER['HTTP_REFERER'])['domain'];
            if($path != "/annonce" ){
                return $this->redirectToRoute('annonce_view');
            }
        }*/

        $annonce = $this->getDoctrine()
            ->getRepository(Annonce::class)
            ->find($id);
        $annonce_user_id = $annonce->getUser_id();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user_id = $user->getId();
        if ($annonce_user_id != $user_id) {
            return $this->redirectToRoute('annonce_view');
        }
        if (!isset($annonce)) {
            return $this->redirectToRoute('annonce_view');
        }

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('annonce_view');
        }
        return $this->render('annonce/edit.html.twig', [
            'Annonce' => $annonce,
            'form' => $form->createView(),

        ]);
    }
    /**
     * @Route("/annonce/{id}/delete", name="annonce_delete")
     */
    public function deleteAction(Request $request, $id){
        /*if (isset($_SERVER['HTTP_SERVER'])) {
            $path = parse_url($_SERVER['HTTP_SERVER'])['domain'];
            if($path != "/annonce"){
                return $this->redirectToRoute('annonce_view');
            }
        }*/
        $annonce = $this->getDoctrine()
            ->getRepository(Annonce::class)
            ->find($id);
        $AnnonceUser_id = $annonce->getUser_id();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $UserUser_id = $user->getId();
        if ($AnnonceUser_id != $UserUser_id) {
            return $this->redirectToRoute('annonce_view');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute('annonce_view');
    }
}
