<?php

namespace HospitalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HospitalBundle\Controller\ConfiguracionController;
use Mpociot\BotMan\BotManFactory;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Cache\DoctrineCache;

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
}
