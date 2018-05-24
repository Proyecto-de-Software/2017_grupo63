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

    public function bot()
    {
        $bot_api_key  = '363828343:AAE_DQ4D5Mdcb10UqHWCQ5ZRQiAiILFe2fE';
        $config = [
           'telegram_token' => $bot_api_key,
        ];
        $botman = BotManFactory::create($config, new DoctrineCache($doctrineCacheDriver));

        $botman->hears('turnos {fecha}', function ($bot, $fecha) {
            $options = array(
              'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json\r\n" .
                          "Accept: */*\r\n" .
                          "Connection: Keep-Alive"
              )
            );
            $context = stream_context_create($options);
            $turnos = file_get_contents("https://grupo63.proyecto2017.linti.unlp.edu.ar/turnos/". $fecha, false, $context);
            $temporal = json_decode($turnos);
            if (!is_null($temporal)) {
                 $turnos = json_decode($turnos, true);
                 if (empty($turnos)) {
                        $respuesta = "No contamos con turnos disponibles.";
                } 
                else {
                        $respuesta = "Los turnos disponibles son:";
                         foreach ($turnos as $turno) {
                            $respuesta .=  " ".$turno;
                            if (next($turnos)==true) {$respuesta .= ",";}
                            else{$respuesta .= ".";}    
                         }
                    }
                        
            }
            else{$respuesta = $turnos;}     
            $bot->reply($respuesta);
        });

        $botman->hears('reservar {dni} {fecha} {hora}', function ($bot, $dni, $fecha, $hora) {
            $data = array ('dni' => $dni, 'fecha' => $fecha, 'hora' => $hora);
            $ch = curl_init('https://grupo63.proyecto2017.linti.unlp.edu.ar/turnos');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $respuesta = curl_exec($ch);
            curl_close($ch);
            $bot->reply($respuesta);
        });

        $botman->listen();
    }
}
