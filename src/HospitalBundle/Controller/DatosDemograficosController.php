<?php

namespace HospitalBundle\Controller;

use HospitalBundle\Entity\DatosDemograficos;
use HospitalBundle\Entity\Paciente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use HospitalBundle\Model\CurlClase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * Datosdemografico controller.
 *
 * @Route("datosdemograficos")
 */
class DatosDemograficosController extends Controller
{
    /**
     * 
     *
     * @Route("/estadistica", name="datos_demograficos_estadistica")
     * @Method("GET")
     */
    public function estadisticaAction()
    {
         $em = $this->getDoctrine()->getManager();
         $curlc = new CurlClase();
         $todos = array();
         $repository = $em->getRepository(DatosDemograficos::class); 
         $todos[] = $repository->vivienda($curlc->obtenerDatos("tipo-vivienda"));
         $todos[] = $repository->calefaccion($curlc->obtenerDatos("tipo-calefaccion"));
         $todos[] = $repository->agua($curlc->obtenerDatos("tipo-agua"));
         $todos[] = $repository->electricidad();
         $todos[] = $repository->mascota();
         $todos[] = $repository->heladera();
         return $this->render('datosdemograficos/estadistica.html.twig', array(
            'dataEstadistica' => $todos
        ));
    }

    /**
     * Creates a new datosDemografico entity.
     * @Security("has_role('ROLE_REC') or has_role('ROLE_PED')")
     * @Route("/new/{paciente}", name="datosdemograficos_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Paciente $paciente)
    {
        if (!is_null($paciente->getDatosDemograficos())) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Este paciente ye cuenta con datos demograficos!'
            );  
            return $this->redirectToRoute('paciente_show', array('id' => $paciente->getId()));
        }
        $datosDemografico = new DatosDemograficos();
        $form = $this->createForm('HospitalBundle\Form\DatosDemograficosType', $datosDemografico);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($datosDemografico);
            $paciente->SetDatosDemograficos($datosDemografico);
            $em->flush();

            return $this->redirectToRoute('datosdemograficos_show', array(
                'paciente' => $paciente->getId()
            ));
        }

        return $this->render('datosdemograficos/new.html.twig', array(
            'datosDemografico' => $datosDemografico,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a datosDemografico entity.
     * @Security("has_role('ROLE_REC') or has_role('ROLE_PED')")
     * @Route("/{paciente}", name="datosdemograficos_show")
     * @Method("GET")
     */
    public function showAction(Paciente $paciente)
    {
        $datosDemografico = $paciente->getDatosDemograficos();
        $curlc = new CurlClase();
        $agua = $curlc->obtenerDato("tipo-agua/" . $datosDemografico->getTipoAguaId());
        $calefaccion = $curlc->obtenerDato("tipo-calefaccion/" . $datosDemografico->getTipoCalefaccionId());
        $vivienda = $curlc->obtenerDato("tipo-vivienda/" . $datosDemografico->getTipoViviendaId());
        return $this->render('datosdemograficos/show.html.twig', array(
            'datosDemografico' => $datosDemografico,
            'paciente' => $paciente->getId(),
            'agua' => $agua,
            'vivienda' => $vivienda,
            'calefaccion' => $calefaccion
        ));
    }

    /**
     * Displays a form to edit an existing datosDemografico entity.
     * @Security("has_role('ROLE_REC') or has_role('ROLE_PED')")
     * @Route("/{id}/edit/{paciente}", name="datosdemograficos_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DatosDemograficos $datosDemografico, Paciente $paciente)
    {
        $deleteForm = $this->createDeleteForm($datosDemografico);
        $editForm = $this->createForm('HospitalBundle\Form\DatosDemograficosType', $datosDemografico);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Se han editado los datos demograficos'
            );
            return $this->redirectToRoute('paciente_show', array('id' => $paciente->getId()));
        }

        return $this->render('datosdemograficos/edit.html.twig', array(
            'datosDemografico' => $datosDemografico,
            'edit_form' => $editForm->createView(),
            'paciente' => $paciente->getId()
        ));
    }

    /**
     * Deletes a datosDemografico entity.
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{id}", name="datosdemograficos_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DatosDemograficos $datosDemografico)
    {
        $form = $this->createDeleteForm($datosDemografico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($datosDemografico);
            $em->flush();
        }

        return $this->redirectToRoute('datosdemograficos_index');
    }

    /**
     * Creates a form to delete a datosDemografico entity.
     *
     * @param DatosDemograficos $datosDemografico The datosDemografico entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DatosDemograficos $datosDemografico)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('datosdemograficos_delete', array('id' => $datosDemografico->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
