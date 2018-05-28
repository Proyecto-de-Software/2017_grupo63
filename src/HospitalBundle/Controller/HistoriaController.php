<?php

namespace HospitalBundle\Controller;

use HospitalBundle\Entity\Historia;
use HospitalBundle\Entity\Paciente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Historium controller.
 *
 * @Route("historia")
 */
class HistoriaController extends Controller
{
    /**
     * Lists all historium entities.
     *
     * @Route("/idPac/{paciente}", name="historia_index")
     * @Method("GET")
     */
    public function indexAction(Paciente $paciente)
    {
        $dt = $this->container->get('hospital.datos_twig');
        $elXpag = $dt->getPaginado();
        $em = $this->getDoctrine()->getManager();
        //$historias = $em->getRepository('HospitalBundle:Historia')->findAll();
        $historias = $paciente->getHistorias()->getValues();
        $this->get('session')->set('paciente', $paciente);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $historias,
            $this->get('request')->query->get('page', 1),
            $elXpag
        );
        $delete_forms = array();
        foreach($pagination as $historia)
        {
            $delete_forms[$historia->getId()] = $this->createDeleteForm($historia)->createView();
        }

        return $this->render('historia/index.html.twig', array(
            'pagination' => $pagination,
            'deleteForms' => $delete_forms,
            'paciente' => $paciente
        ));
    }

    /**
     * Creates a new historium entity.
     * @Security("has_role('ROLE_PED')")
     * @Route("/new", name="historia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $historium = new Historia();
        $form = $this->createForm('HospitalBundle\Form\HistoriaType', $historium);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $paciente = $this->get('session')->get('paciente');
            $em = $this->getDoctrine()->getManager();
            $historium->setPaciente($paciente);
            $historium->setUsuario($this->getUser());
            $em->merge($historium);
            $em->flush();            
            $this->get('session')->getFlashBag()->add(
                'notice',
                'El informe medico ha sido creado exitosamente!'
            );
            return $this->redirectToRoute('historia_index', array('paciente' => $paciente->getId()));
        }

        return $this->render('historia/new.html.twig', array(
            'historium' => $historium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a historium entity.
     * @Security("has_role('ROLE_PED')")
     * @Route("/{id}", name="historia_show")
     * @Method("GET")
     */
    public function showAction(Historia $historium)
    {
        

        return $this->render('historia/show.html.twig', array(
            'historium' => $historium,
        ));
    }

    /**
     * Displays a form to edit an existing historium entity.
     * @Security("has_role('ROLE_PED')")
     * @Route("/{id}/edit", name="historia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Historia $historium)
    {
        $deleteForm = $this->createDeleteForm($historium);
        $editForm = $this->createForm('HospitalBundle\Form\HistoriaType', $historium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Se ha modificado el control de salud de la hisotria clinica'
                );
            return $this->redirectToRoute('historia_index', array('paciente' => $historium->getPaciente()->getId()));
        }

        return $this->render('historia/edit.html.twig', array(
            'historium' => $historium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a historium entity.
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{id}", name="historia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Historia $historium)
    {
        $form = $this->createDeleteForm($historium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paciente = $historium->getPaciente();
            $em = $this->getDoctrine()->getManager();
            $em->remove($historium);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Se ha borrado el control de salud de la hisotria clinica'
                );
        }
        return $this->redirectToRoute('historia_index', array('paciente' => $paciente->getId()));
    }

    /**
     * Creates a form to delete a historium entity.
     *
     * @param Historia $historium The historium entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Historia $historium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('historia_delete', array('id' => $historium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
