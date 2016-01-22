<?

	header('Content-type: application/json; charset=utf-8');
	include './config.php';
	include './classes/vkapi.class.php';
	include './classes/checkInstall.class.php';

	$CheckInstall = new CheckInstall( 
		array(
			'countRounds' => 60,
			'intervalRounds' => 2000
		)
	);
	$CheckInstall -> start();
	
?>
