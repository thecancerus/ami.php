<?php
error_reporting(E_ALL);

require('amiphp/ami.php');

$urls = array(
	'/' => 'index',
    '/index/test/*' => 'hello',
);
$db_config =array(
	'type' => 'mysql',
	'host' => 'localhost',
	'user' => 'root',
	'password' => '',
	'dbname' => 'blog'
);
class index {
    function GET($obj) {
		$obj->ami->render('views/index.php');
    }
}

class hello {
	function GET($obj) {
	 	$db=$obj->ami->getDB();
		if($db!==false)
		{	
		
			$user=$db->get_row("SELECT * FROM `users` where userid = 1");
			//$db->debug();
		}
		else
			echo 'err';/**/
		
		$data['body']=$obj->ami->loadView('views/body.php',array('name'=>$user->name,'likes'=>$user->password)); 
		//$data['body']=$obj->ami->loadView('views/body.php',array('name'=>'Ami','likes'=>'fun')); 
		$obj->ami->render('views/index2.php',$data);
    }
}

ami::run($urls,$db_config);

?>