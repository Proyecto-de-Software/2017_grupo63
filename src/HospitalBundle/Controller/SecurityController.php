<?php

namespace HospitalBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use HospitalBundle\Controller\ConfiguracionController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class SecurityController extends BaseController
{
	
	/**
     * @Route("/login")
     * @param Request $request
     *
     * @return Response	
     */
	public function loginAction(Request $request)
    {
    	$response = parent::loginAction($request);
    	return $response;
    }

	/**
     * @Route("/check")	
     */
	public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
    /**
     * @Route("/logout")
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

	protected function renderLogin(array $data)
    {
        return $this->render('HospitalBundle:Default:login.twig.html', $data);
    }
}	