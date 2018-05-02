<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Utilisateur controller.
 *
 */
class UtilisateurController extends Controller
{

    /**
     * Creates a new utilisateur entity.
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm('AppBundle\Form\UtilisateurType', $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $password = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($password);
            $utilisateur->setStatus(false);

            $em->persist($utilisateur);
            $em->flush();


            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('alicesimon.1992@gmail.com')
                ->setTo($utilisateur->getEmail())
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'Emails/registration.html.twig',
                        array('utilisateur' => $utilisateur)
                    ),
                    'text/html'
                );
            $mailer->send($message);
            return $this->redirectToRoute('_new');
        }

        return $this->render('utilisateur/new.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/{id}/status", name="status")
     *
     */
    public function statusUserAction($id){
        $utilisateur = $this->getDoctrine()
            ->getRepository(Utilisateur::Class)
            ->find($id);
        $utilisateur->setStatus(TRUE);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('security_login');
    }

    /**
     * Finds and displays a utilisateur entity.
     *
     */
    public function showAction(Request $request)
    {
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);
        return $this->render('utilisateur/show.html.twig', array('delete_form' => $form->createView()));
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     */
    public function editAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $deleteForm = $this->createDeleteForm($utilisateur);
        $editForm = $this->createForm('AppBundle\Form\UtilisateurType', $utilisateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $password = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_show');
        }

        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a utilisateur entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $utilisateur = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();

            $this->get('security.token_storage')->setToken(null);

        }
        return $this->redirectToRoute('index');

    }

    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur){

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('_delete', array('id' => $utilisateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
