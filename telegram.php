
<?php
switch ($_GET["action"]) {
			case 'setTelegram':
				# code...

				require_once "controllers/set.php";
				//$historiaController = HistoriaController::getInstance();
				//$historiaController->trabajar($action);
				//exit();
				break;
			case 'hookTelegram':
				# code...
				require_once "controllers/hook.php";
				//$historiaController = HistoriaController::getInstance();
				//$historiaController->trabajar($action);
				break;

			case 'update':
				require_once "controllers/pruebaTelegram.php";
				break;

}

?>


