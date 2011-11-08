<?php

class Dia
{
	var $DIASERVER = "";
	var $param = array();
    var $proxy_port = "" ;
    var $proxy_url = "";
    var $proxy_user = ""; 
    var $proxy_password = "";
	
	function Dia($site, $collection, $count, $output, $lang , $server_url){

		$this->param["site"]  = $site;
		$this->param["col"]   = $collection;
		$this->param["count"] = $count;
		$this->param["output"]= $output;
		$this->param["lang"]  = $lang;
		$this->DIASERVER = $server_url;
		return;
	}

    function setProxy($pproxy_url, $pproxy_port, $pproxy_user, $pproxy_password)
    {
        $this->proxy_port = $pproxy_port;
        $this->proxy_url = $pproxy_url;
        $this->proxy_user = $pproxy_user;
        $this->proxy_password = $pproxy_password;
    }
    
	function setParam($param, $value){
		if ($value != null && $value != ""){
			$this->param[$param] = $value;
		}
		return;
	}

	function search($query, $index, $filter,  $from){
	        
		$this->param["op"] = "search";
		$this->param["q"] = $query;
		$this->param["index"] = $index;

		if ($from != "" && $from > 0){
			$this->param["start"] = ($from - 1);
		}
		
		if ( isset($filter) ){			
			$this->mountFilterParam($filter);
		}
		
		$searchUrl = $this->requestUrl();

		$result = $this->documentPost( $searchUrl );
		return trim($result);
	}	

	function mountFilterParam($filter){		
		$filter = $this->cleanArray($filter);		//remove valores vacios del array
		$fq = join(" AND ",$filter);
				
		$this->param["fq"] = stripslashes($fq);
		
		return;
	}


	function requestUrl()	{
		$urlParam = "";		
		reset($this->param);
		//print_r($this->param); exit;
		while (list($key, $value) = each($this->param))	{
			if ($value != ""){
				$urlParam .= "&" . $key . "=" . urlencode($value);
			}
		}

        $requestUrl = $this->DIASERVER."?" . substr($urlParam,1);

        return $requestUrl;
	}
	
	function documentPost( $url )
	{ 
		global $debug;

		$url_parts = parse_url($url);
		$host = $url_parts["host"];
		$port = ($url_parts["port"] ? $url_parts["port"] : "80");
		$path = $url_parts["path"];
		$query = $url_parts["query"];
		$result = "";
		$timeout = 10;
		$contentLength = strlen($query);
 
        //$debug=1;
		if (isset($debug))
        {
			print "<b>dia-server request:</b> " . $url . "<br/>";	
		}
		
         if($this->proxy_url=="")
         {
               // Generate the request header 
                $ReqHeader =
                      "POST $path HTTP/1.0\r\n". 
                      "Host: $host\r\n". 
                      "User-Agent: PostIt\r\n". 
                      "Content-Type: application/x-www-form-urlencoded; charset=UTF-8\r\n". 
                      "Content-Length: $contentLength\r\n\r\n". 
                      "$query\n"; 
                // Open the connection to the host 
                $fp = fsockopen($host, $port, $errno, $errstr, $timeout);

                if (!$fp) 
                {
                    echo "ERROR: $errno - $errstr <br/>\n";
                } 
                else 
                {
                    fputs( $fp, $ReqHeader );
                    while (!feof($fp))
                    {
                        $result .= fgets($fp, 4096);
                    }
                }
         }
         else
         {
                $proxy_port = $this->proxy_port; 
                // Connect to the proxy:
                $fp = fsockopen($this->proxy_url, "$proxy_port", $errno, $errstr, $timeout)
                    or die("Error: $errno - $errstr <br/>\n");
                    
                $login = $this->proxy_user;
                $passwd =  $this->proxy_password;
                $url_controler = $this->DIASERVER;
                $referer = "";
                // Enviando cabeceras y datos
                fputs($fp, "POST $url_controler HTTP/1.0\r\n");
                fputs($fp, "Host: $host\r\n");
                fputs($fp, "Proxy-Authorization: Basic ".base64_encode("$login:$passwd"));
                fputs($fp, "Referer: $referer\r\n");
                fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
                fputs($fp, "Content-length: ". $contentLength ."\r\n");
                fputs($fp, "Connection: close\r\n\r\n");
                fputs($fp, $query);
                
                // Recogiendo el resultado 
                while(!feof($fp))
                    $result .= fgets($fp, 4096);
                    
                fclose($fp);    
         }
        
        
        $result = substr($result,strpos($result,"\r\n\r\n"));

    	return $result; 
	}

	function cleanArray($array) {
    	foreach ($array as $index => $value) {
        	if (empty($value)) unset($array[$index]);
    	}
    	return $array;
	}

	function getFilterParam(){
		return $this->param["fq"];
	}

}

?>
