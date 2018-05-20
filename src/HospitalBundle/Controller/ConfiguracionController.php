<?php

namespace HospitalBundle\Controller;

use HospitalBundle\Entity\Configuracion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
/**
 * Configuracion controller.
 *
 * @Route("configuracion")
 */
class ConfiguracionController extends Controller
{
    


    /**
     * Displays a form to edit an existing configuracion entity.
     *
     * @Route("/{id}/edit", name="configuracion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, configuracion $configuracion)
    {
        $editForm = $this->createForm('HospitalBundle\Form\configuracionType', $configuracion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Se ha modificado la configuracion del sitio'
            );    
            return $this->redirectToRoute('default_index');
        }

        return $this->render('configuracion/edit.html.twig', array(
            'configuracion' => $configuracion,
            'edit_form' => $editForm->createView(),
        ));
    }
}
