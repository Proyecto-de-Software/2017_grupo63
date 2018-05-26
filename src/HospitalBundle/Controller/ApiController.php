<?php

namespace HospitalBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use HospitalBundle\Entity\Turno;
use HospitalBundle\Entity\Paciente;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\View;

/*
 * 
*/
class ApiController extends FOSRestController
{
	
    /**
     * GET Route annotation.
     * @Get("/turnos/{fecha}", defaults={"_format"="json"})
     */
    public function getTurnosAction($fecha)
    {
        if (!$this->verificarFecha($fecha)) {
            $view = $this->view("Formato de fecha no valido.El formato de fecha debe ser dd-mm-aaaa", 400);
            return $view;    
        }
        $em = $this->getDoctrine()->getManager();
        $turnos = $em->getRepository(Turno::class)->getTurnos($fecha);
        $view = $this->view($turnos, 200);
        return $view;
    }

    /**
     * 
    ¨* 
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="dni", nullable=false, strict=true, description="dni.")
     * @RequestParam(name="fecha", nullable=false, strict=true, description="Fecha.")
     * @RequestParam(name="hora", nullable=false, strict=true, description="Hora.")
     *
     * @Post("/turnos")
     *
     */
    public function postReservarTurnoAction(ParamFetcher $paramFetcher)
    {
        $dni = $paramFetcher->get('dni');
        $fecha = $paramFetcher->get('fecha');
        $hora = $paramFetcher->get('hora');
        if (!$this->verificarFecha($fecha)) {
            $view = $this->view("Formato de fecha no valido.El formato de fecha debe ser dd-mm-aaaa", 400);
            return $view;    
        }
        if (!$this->verificarHora($hora)) {
            $view = $this->view("Formato de hora no valido.El formato de fecha debe ser hh:mm, mm debe ser 30 o 00 y la hora entre 8 y 19", 400);
            return $view;    
        }
        if ($this->yaPaso($fecha)) {
            $view = $this->view("La fecha del turno no puede ser anterior al dia actual.", 400);
            return $view;    
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Turno::class);
        if ($repository->estaOcupado($fecha, $hora)) {
            $view = $this->view("Ese turno no se encuntra disponible.", 400);
            return $view;
        }
        $turno = new Turno();
        $fechaSql = new \DateTime($fecha . " " . $hora);
        //var_dump($fechaSql);
        //$fechaSql = $fechaSql->format('Y-m-d H:i:s');
        $turno->setFecha($fechaSql);
        $turno->setDni($dni);
        $em->persist($turno);
        $em->flush();
        $view = $this->view("Su identificacion de turno es " . $turno->getId(), 200);
        return $view;
    }

    /**
     * GET Route annotation.
     * @Get("/curvaPeso/{paciente}", defaults={"_format"="json"})
     */
    public function getCurvaPesoAction($paciente)
    {
        $em = $this->getDoctrine()->getManager();
        $dataCurva = $em->getRepository(Paciente::class)->curvaPeso($paciente);
        $view = $this->view($dataCurva, 200);
        return $view;
    }


    public function verificarFecha($date)
	{
		$fecha = explode("-", $date);
		if (count($fecha) != 3) {
			return false;
		}
		return checkdate($fecha[1], $fecha[0], $fecha[2]);
	}

    private function verificarHora($time)
        {
            $time = explode(":", $time);
            if (count($time) != 2) {
                echo "hola";
                return false;
            }
            $hora = $time[0];
            $minuto = $time[1];
            if ($hora <= 7 || $hora >= 20 ) {
                return false;
            }
            if ($minuto != '00' && $minuto != '30') {
                return false;
            }
            return true;
        }

	private function acomodarFecha($fecha)
	{
		$a = explode('-',$fecha);
		$my_new_date = $a[2].'-'.$a[1].'-'.$a[0];
		return $my_new_date;
	}

    private function yaPaso($fecha)
    {
            $hoy = Date("d-m-Y");
            $fecha = Date($fecha);
            return strtotime($hoy) >= strtotime($fecha);
    }
} 
?>