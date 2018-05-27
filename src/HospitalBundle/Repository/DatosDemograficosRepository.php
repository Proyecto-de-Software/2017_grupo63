<?php

namespace HospitalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DatosDemograficosRepository extends EntityRepository
{
	public function estadistica(){
		$todos = array();
		$todos[] = $this->electricidad();
		$todos[] = $this->mascota();
		$todos[] = $this->heladera();
		return $todos;
	}

	private function porcentajes($colum)
	{
		$em = $this->getEntityManager();
		$dql = "SELECT CASE WHEN d.$colum <> 0 THEN 'si' ELSE 'no' END as tiene, COUNT(d.$colum) as cant 
		FROM HospitalBundle:DatosDemograficos AS d GROUP BY d.$colum";
		$query = $em->createQuery($dql);
		$result = $query->getArrayResult();
		$final = array();
		foreach ($result as $value) {
			$aux = array();
			$aux['name'] = $value['tiene'];
			$aux['y'] = (int)$value['cant']; 
			$data[] = $aux;
		}
		$final["colorByPoint"] = true;
		$final['data'] = $data;
		$final['name'] = $colum;
       	return $final;
	}

	private function porcentajesCurl($colum, $name, $tipos)
	{
		$em = $this->getEntityManager();
		$dql2 = "SELECT d.$colum as tipo, COUNT(d.$colum) as cant FROM HospitalBundle:DatosDemograficos AS d GROUP BY d.$colum"; 
		$query = $em->createQuery($dql2);
		$result = $query->getArrayResult();
		$final = array();
		foreach ($result as $value) {
			$aux = array();
			$aux['name'] = $tipos[$value['tipo']];
			$aux['y'] = (int)$value['cant']; 
			$data[] = $aux;
		}
		$final["colorByPoint"] = true;
		$final['data'] = $data;
		$final['name'] = $name;
       	return $final;
		}

	public function electricidad()
	{
		$datos = $this->porcentajes("electricidad"); 
		$aux = array();
		$aux[] = "14%"; 
		$aux[] = "22%"; //eje y 
		$datos['center'] = $aux;
		$datos['size'] = 160;
		return $datos ;
	}

	public function mascota()
	{
		$datos = $this->porcentajes("mascota"); 
		$aux = array();
		$aux[] = "50%"; //eje x
		$aux[] = "22%"; //eje y 
		$datos['center'] = $aux;
		$datos['size'] = 160;
		return $datos ;
	}
		
	public function heladera()
	{
		$datos = $this->porcentajes("heladera"); 
		$aux = array();
		$aux[] = "85%";
		$aux[] = "22%"; //eje y
		$datos['center'] = $aux;
		$datos['size'] = 160;
		return  $datos;
	}

	public function vivienda($tipos)
	{
		$datos = $this->porcentajesCurl("tipoViviendaId", "Tipo de vivienda", $tipos); 
		$aux = array();
		$aux[] = "14%";
		$aux[] = "75%"; //eje y
		$datos['center'] = $aux;
		$datos['size'] = 160;
		return  $datos;
	}

	public function calefaccion($tipos)
	{
		$datos = $this->porcentajesCurl("tipoCalefaccionId", "Tipo de calefaccion", $tipos); 
		$aux = array();
		$aux[] = "50%";
		$aux[] = "75%"; //eje y
		$datos['center'] = $aux;
		$datos['size'] = 160;
		return  $datos;
	}

	public function agua($tipos)
	{
		$datos = $this->porcentajesCurl("tipoAguaId", "Tipo de agua", $tipos); 
		$aux = array();
		$aux[] = "85%";
		$aux[] = "75%"; //eje y
		$datos['center'] = $aux;
		$datos['size'] = 160;
		return  $datos;
	}

}