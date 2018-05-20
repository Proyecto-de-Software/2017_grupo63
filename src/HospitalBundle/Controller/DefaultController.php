<?php

namespace HospitalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HospitalBundle\Controller\ConfiguracionController;

class DefaultController extends Controller
{
  
    /**
     * @Route("/", name="default_index")
     */
    public function indexAction()
    {
        $usuario = $this->getUser();
        if (!is_null($usuario)) {
            $datos['usuario'] = $usuario;
            //dump($usuario);
        }
        return $this->render('HospitalBundle:Default:frontHabilitado.twig.html');
    }

    /**
     * @Route("/loginH")
     */
    public function loginAction()
    {
        return $this->render('HospitalBundle:Default:login.twig.html');
    }


    public function getExistingRoles()
    {
        // sintaxis dentro de admin class:
        //$roleHierarchy = $this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles');
        // sintaxis dentro de un controlador:
        $roleHierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);
        $theRoles = array();
 
        foreach ($roles as $role) {
            $theRoles[$role] = $role;
        }
        return $theRoles;
    }
}
