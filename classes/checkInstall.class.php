<?
class CheckInstall {
	
	protected $options = array(
		'checkRegisterUsersCount'	=> 300,
		'checkInstallUsersCount'	=> 200,
		'countRounds'	 			=> 30,
		'intervalRounds' 			=> 10000, 	// 0.01 * 1000000 int micro_seconds
		'update' 					=> false,
		'updatePeriod'	 			=> 86400, 	// 1 * 24 * 60 * 60  
		'message'					=> 'Test Message'
	);
	
	public function __construct($options=null, $show_result = true) {
		if ($options) {
			$this -> options = $options + $this -> options;
        }
		$this -> show_result= $show_result;
		$this -> last_id 	= 0;
		$this -> all		= 0;
		$this -> allAdd		= 0;
		$this -> round 		= 0;
		$this -> vk = new vkapi(APP_ID, APP_HASH);
	}

    public function start(){
		$startTime = microtime(true);
	    for ( $i = 1; $i < $this -> options['countRounds'] + 1; $i++ ) { 
			$this -> roundStart($i);
			usleep( $this -> options['intervalRounds'] );
		}

		if( $this -> show_result ){
			echo "<br>All round: added -  ".$this -> allAdd.", All - ".$this -> all.", lastID - ".$this -> last_id." <br>";		
			$time = microtime(true) - $startTime;
			printf('Скрипт выполнялся %.4F сек. <br>', $time);
			echo 'Циков: ', $this -> options['countRounds'], ',';
			printf(' интервал %.4F сек. ', $this -> options['intervalRounds']/1000000);
		}
    }

    public function roundStart( $i = 1 ){
    	$this -> round = $i;
		$result = mysql_query("SELECT * FROM  `check_install` LIMIT 1");
		$data = mysql_fetch_assoc($result);
		$this -> start_id = (int)$data['start_id'];
		$this -> start_time = (int)$data['start_time'];
		$this -> message = ($data['message']) ? $data['message'] : $this -> options['message'];
	
		if( $this -> options['update'] && $this -> start_time < time() - $this -> options['updatePeriod'] ){
			$this -> startOver();
		}
	
		$users = $this -> getUsers();
		$data = $this -> getRequestData($users);
	
		if( !empty($data) ) {
			$resp = $this -> vk -> api('secure.sendNotification', array( 'user_ids' => $data,'message' => $this -> message ) );
			if( !empty($resp['response']) ){
				$uids = explode(',',$resp['response']);
				$add = count($uids);
				$this -> addUsers( $uids );
				$this -> saveResult( $add );
				$this -> all += count($users);
				$this -> allAdd += $add;
			} else {
				$this -> saveResult();
			}
		} else if( $this -> show_result ){ 
			die("Round ".$this -> round." : no users <br>");
		}
    }

    public function addUsers( $uids ){
    	$sql = '';
    	foreach ($uids as $k => $v) {
			$sql .= "INSERT INTO `check_users` (`user_id`) VALUES ('$v');";
    	}
    	$result = mysql_query($sql);
    }

    public function saveResult( $add = 0 ){
    	$result = mysql_query("UPDATE `check_install` SET  `added` = `added` + '". $add ."' ,  `start_id` = ". $this -> last_id ."  WHERE  `id` = 1");
    	if( $this -> show_result ){
			echo "Round ".$this -> round." : added - ".$add.", lastID - ". $this -> last_id ." <br>";
		}
    }

    public function genNextUids( $last_user_id = 0, $count = 1000 ){
		$uids = array();
		$last = $last_user_id + $count;
    	for ( $i = $last_user_id; $i < $last; $i++ ) { 
    		$uids[] = $i;
    	} 
    	return $uids;
    }

    public function getUsers(){
    	$uids = $this -> genNextUids( $this -> start_id, $this -> options['checkRegisterUsersCount'] );
    	$data = $this -> getRequestData( $uids );
    	$resp = $this -> vk -> api('users.get', array( 'user_ids' => $data ) );
    	if( !empty($resp['response']) ){
    		$uids = array();
			foreach ($resp['response'] as $k => $v) {
				if( empty($v['deactivated']) ){
					$uids[] = $v['uid'];
					$this -> last_id = $v['uid'];
					if( count($uids) == $this -> options['checkInstallUsersCount'] ){
						break;
					}
				}
			}
			return $uids;
    	} else {
			if( $this -> show_result ){
				print_r($resp['error']);
			}
			die();
		}
    }

    public function getRequestData($users){
    	return implode($users, "," );
    }

    public function startOver(){
		$result = mysql_query("UPDATE `counter` SET `added` =  '0', `start_id` =  '0', `start_time` =  '". time() ."' WHERE `id` = 1");
		die('startOver');
    }
}
?>
