<?php
error_reporting(E_STRICT);

require('amiphp/ami.php');

$urls = array(
	'/' => 'index',
    '/index/test/*' => 'hello',
);

class index {
    function GET($obj) {
		$obj->ami->render('views/index.php');
    }
}

class hello {
	function GET($obj) {
		$data['body']=ami::loadView('views/body.php',array('name'=>'Amit','likes'=>'Only To Work'));
		$obj->ami->render('views/index2.php',$data);
    }
}

ami::run($urls);

?>