<?php
/**
* @package ami.php
* @version $Id: ami.php,v 0.0.1 2010/03/13 13:49 gareth Exp $
*/
/**
* ami.php
* Simple framework for simple solution
* @access public
* @package ami.php
*/

class ami {	
	static private $instance = NULL ;
	public $db_conf ='';
	public static function getInstance()
    {
        if(self::$instance == NULL)
        {
            self::$instance = new ami();
        }
        return self::$instance;
    }   

	/**
    * Initialise ami.php, mainly dealing with routing requests
    * @param array the routing table for this application
    * @param array parameters for initialising a database connection i required
    * @return string
    * @access public
    */
	 public static function run($urls,$db_parameters='') {
		
		$ami = self::getInstance();
		$c=new AmiContainer();
		
		$ami->db_conf=$db_parameters;
		
		require('Inspekt.php');
		$input = Inspekt::makeSuperCage();  
		
		require('pathvars.class.php');
		
		
		$method = $input->server->getAlpha('REQUEST_METHOD');
		if (!in_array($method, array('GET', 'POST', 'PUT', 'DELETE'))) {
			$method = 'GET';
		}
		
		$printpath='';
		
		$path = new PathVars($input->server->getRaw('SCRIPT_NAME'));
		$fullpath = $path->fetchAll();

		if (count($fullpath) > 1) {
		    array_pop($fullpath);
		    $fullpath[] = '*';
        }
        foreach ($fullpath as $segment) {
            $printpath .= '/'.$segment;              
        }
		
		
		$c->path = $path;
		$c->ami = $ami;
		$c->input = $input;
		
		if (array_key_exists($printpath,$urls)) {
			if (class_exists($urls[$printpath])) {
				$loaded_class = new $urls[$printpath];
				$loaded_class->$method($c);   
			} 
			else 
			{
				$ami->render('error.html'); 
			}
		} 
		else {
			$loaded_class = new $urls['/'];
			$loaded_class->$method($c);     
		}	
		
        return true;
    }

	/**
    * Prints the page based on a data array and a template file
    * @param string the name of the template to use
    * @param array an array of data for the template engine
    * @return string
    * @access public
    */
	function render($file,$data='') {
		if (file_exists($file)) {
			if(is_array($data))
			{
				extract($data);
			}
			ob_start();
			include($file);
			$buffer = ob_get_contents();
			ob_end_clean();
			
			echo $buffer;
        } else {
            return false;
        }			
		return true;
	}
	
	/**
    * returns the loaded view after parsing it.
    * @param string the name of the template to use
    * @param array an array of data for the template engine
    * @return string
    * @access public
    */
	function loadView($file,$data='') {
		if (file_exists($file)) {
			if(is_array($data))
			{
				extract($data);
			}
			ob_start();
			include($file);
			$buffer = ob_get_contents();
			ob_end_clean();
			
			return $buffer;
		} else {
			return false;
		}			
	}
	
	/**
    * establishes a connection to database
    * @param array database connectivity parameters
    * @return database object
    * @access public
    */
	public function getDB()
    {   
	
         if (is_array($this->db_conf)) { 
		    require('ez_sql.php');
			if($this->db_conf['type']=='mysql')
				$db = new ezSQL_mysql($this->db_conf['user'],$this->db_conf['password'],$this->db_conf['dbname'],$this->db_conf['host']);
			else if($this->db_conf['type']=='sqlite')
				$db = new ezSQL_sqlite($this->db_conf['host'],$this->db_conf['dbname']); 
				
			return $db;	
		} 
		return false;
    }
	
	/**
    * Redirect the request to the URL
    * @param string URL to rediret to
    * @return string
    * @access public
    */
	function redirect($location, $status=303)
    {   
        $this->httpHeader($status);
        header("Location: $location");
    }
    
    /**
     * send header code to browser
     *
     * @param string $code 
     * @return void
     * @author Kenrick Buchanan
     */
    
    public function httpHeader($code)
    {
        $http = array (
               100 => "HTTP/1.1 100 Continue",
               101 => "HTTP/1.1 101 Switching Protocols",
               200 => "HTTP/1.1 200 OK",
               201 => "HTTP/1.1 201 Created",
               202 => "HTTP/1.1 202 Accepted",
               203 => "HTTP/1.1 203 Non-Authoritative Information",
               204 => "HTTP/1.1 204 No Content",
               205 => "HTTP/1.1 205 Reset Content",
               206 => "HTTP/1.1 206 Partial Content",
               300 => "HTTP/1.1 300 Multiple Choices",
               301 => "HTTP/1.1 301 Moved Permanently",
               302 => "HTTP/1.1 302 Found",
               303 => "HTTP/1.1 303 See Other",
               304 => "HTTP/1.1 304 Not Modified",
               305 => "HTTP/1.1 305 Use Proxy",
               307 => "HTTP/1.1 307 Temporary Redirect",
               400 => "HTTP/1.1 400 Bad Request",
               401 => "HTTP/1.1 401 Unauthorized",
               402 => "HTTP/1.1 402 Payment Required",
               403 => "HTTP/1.1 403 Forbidden",
               404 => "HTTP/1.1 404 Not Found",
               405 => "HTTP/1.1 405 Method Not Allowed",
               406 => "HTTP/1.1 406 Not Acceptable",
               407 => "HTTP/1.1 407 Proxy Authentication Required",
               408 => "HTTP/1.1 408 Request Time-out",
               409 => "HTTP/1.1 409 Conflict",
               410 => "HTTP/1.1 410 Gone",
               411 => "HTTP/1.1 411 Length Required",
               412 => "HTTP/1.1 412 Precondition Failed",
               413 => "HTTP/1.1 413 Request Entity Too Large",
               414 => "HTTP/1.1 414 Request-URI Too Large",
               415 => "HTTP/1.1 415 Unsupported Media Type",
               416 => "HTTP/1.1 416 Requested range not satisfiable",
               417 => "HTTP/1.1 417 Expectation Failed",
               500 => "HTTP/1.1 500 Internal Server Error",
               501 => "HTTP/1.1 501 Not Implemented",
               502 => "HTTP/1.1 502 Bad Gateway",
               503 => "HTTP/1.1 503 Service Unavailable",
               504 => "HTTP/1.1 504 Gateway Time-out"       
           );
        header($http[$code]);
    }
}

class AmiContainer {
 protected $s=array();
 function __set($k, $c) { $this->s[$k]=$c; }
 function __get($k) { return $this->s[$k]; }
}