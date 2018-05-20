<?php

namespace HospitalBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;


class MaintenanceListener
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // This will get the value of our maintenance parameter
        //$maintenance = $this->container->hasParameter('maintenance') ? $this->container->getParameter('maintenance') : false;

        // This will detect if we are in dev environment (app_dev.php)
        //$debug = in_array($this->container->get('kernel')->getEnvironment(), ['dev']);

        // If maintenance is active and in prod environment
        $config = $this->container->get('hospital.datos_twig');
        $user = null;
        $token = $this->container->get('security.token_storage')->getToken();
        if (!is_null($token) && $this->container->get("security.authorization_checker")->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $token->getUser();
        }
        
        if ((!$config->getHabilitado()) AND ((is_null($user)) or (!$user->hasRole('ROLE_ADMIN'))) ) {
            // We load our maintenance template
            $engine = $this->container->get('templating');
            $template = $engine->render('HospitalBundle:Default:mantenimiento.html.twig');

            // We send our response with a 503 response code (service unavailable)
            $event->setResponse(new Response($template, 503));
            $event->stopPropagation();
        }
    }
}