<?

	header('Content-type: application/json; charset=utf-8');
	include './config.php';
	include './classes/vkapi.class.php';
	include './classes/checkInstall.class.php';

	$counter = new checkInstall( array('setCounter' => 1) );
	$counter -> start();
	
?>
