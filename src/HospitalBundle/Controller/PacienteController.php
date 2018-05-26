<?php

namespace HospitalBundle\Controller;

use HospitalBundle\Entity\Paciente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use HospitalBundle\Model\CurlClase;
 
/**
 * Paciente controller.
 *
 * @Route("paciente")
 */
class PacienteController extends Controller
{
    /**
     * Lists all paciente entities.
     *
     * @Route("/", name="paciente_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $dt = $this->container->get('hospital.datos_twig');
        $elXpag = $dt->getPaginado();
        $em = $this->getDoctrine()->getManager();
        $nombre = null !== ($request->query->get('filtro')) ? $request->query->get('filtro') : '';
        $tipoDoc = null !== ($request->query->get('tipoDocFiltro')) ? $request->query->get('tipoDocFiltro') : '';
        $doc = null !== ($request->query->get('tipoDocFiltro')) ? $request->query->get('documento') : '';
        $pacientes = $em->getRepository('HospitalBundle:Paciente')->filtrado($nombre , $tipoDoc, $doc);
        $curlc = new CurlClase();
        $obras = $curlc->obtenerDatos("obra-social");
        $docs = $curlc->obtenerDatos("tipo-documento");
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $pacientes,
            $this->get('request')->query->get('page', 1),
            $elXpag
        );
        $delete_forms = array();
        foreach($pagination as $paciente)
        {
            $delete_forms[$paciente->getId()] = $this->createDeleteForm($paciente)->createView();
        }
        return $this->render('paciente/index.html.twig', array(
            'pagination' => $pagination,
             'obras' => $obras,
             'docs' => $docs,
             'deleteForms' => $delete_forms 
        ));
    }

    /**
     * Creates a new paciente entity.
     *
     * @Route("/new", name="paciente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $paciente = new Paciente();
        $form = $this->createForm('HospitalBundle\Form\PacienteType', $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paciente);
            $em->flush();

            return $this->redirectToRoute('paciente_show', array('id' => $paciente->getId()));
        }

        return $this->render('paciente/new.html.twig', array(
            'paciente' => $paciente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a paciente entity.
     *
     * @Route("/{id}", name="paciente_show")
     * @Method("GET")
     */
    public function showAction(Paciente $paciente)
    {
        $deleteForm = $this->createDeleteForm($paciente);
        $curlc = new CurlClase(); 
        $paciente->SetObraSocial($curlc->obtenerDato("obra-social/" . $paciente->getObraSocial()));
        $paciente->SetTipoDoc($curlc->obtenerDato("tipo-documento/" . $paciente->getTipoDoc()));
        return $this->render('paciente/show.html.twig', array(
            'paciente' => $paciente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing paciente entity.
     *
     * @Route("/{id}/edit", name="paciente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Paciente $paciente)
    {
        $deleteForm = $this->createDeleteForm($paciente);
        $editForm = $this->createForm('HospitalBundle\Form\PacienteType', $paciente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('paciente_index');
        }

        return $this->render('paciente/edit.html.twig', array(
            'paciente' => $paciente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a paciente entity.
     *
     * @Route("/{id}", name="paciente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Paciente $paciente)
    {
        $form = $this->createDeleteForm($paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paciente);
            $em->flush();
        }

        return $this->redirectToRoute('paciente_index');
    }

    /**
     * Creates a form to delete a paciente entity.
     *
     * @param Paciente $paciente The paciente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Paciente $paciente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('paciente_delete', array('id' => $paciente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * 
     *
     * @Route("/curvaPeso/{id}", name="paciente_curva_peso")
     * @Method("GET")
     */
    public function curvaPesoAction(Paciente $paciente)
    {
         return $this->render('paciente/curvaPeso.html.twig', array(
            'paciente' => $paciente,
        ));
    }

}
