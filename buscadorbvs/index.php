<?php
    session_start();
    
    require_once("Dia.php");
    
    $site="bvs";//default
    $col="local";//default
    $proxy_port = "" ;
    $proxy_url = "";
    $proxy_user = ""; 
    $proxy_password = "";
    $conn = (int)$num_conn;

    
    $path_config_xml=dirname(__FILE__);
    if (file_exists($path_config_xml.'/config/config.xml'))
    {
         $config = simplexml_load_file($path_config_xml.'/config/config.xml');
         $site = trim($config->coneccion[$conn]->site);
         $col = trim($config->coneccion[$conn]->col); 
         $url_controler = trim($config->coneccion[$conn]->bvs_url_controler);
         $have_proxy = trim($config->coneccion[$conn]->proxy);
         if($have_proxy=="yes")
         {
             $proxy_port = trim($config->coneccion[$conn]->proxy_port);
             $proxy_url = trim($config->coneccion[$conn]->proxy_url);
             $proxy_user = trim($config->coneccion[$conn]->proxy_user); 
             $proxy_password = trim($config->coneccion[$conn]->proxy_password);
         }    
    }
    
    
    $count=$cant_res_pag;//documentos por paginas
    $filter="";
    $filterLabel="";
    $qt="standard";//tipo de query
    $fmt="";//formato a mostrar
    $sort=urlencode("score desc");//ordenamiento por defecto
    $pageFrom=0;
    $from=( $offset ? $offset+1 : 0 );
    $addfilter=$addfilter1;
    $backfilter="";
    $printMode="";
    $output="json";
    $fb=$fb1;// facet browse   //para las revistas(ta_cluster:valor)   para los años(year_cluster:valor)
    $debug=1;                //aumenta de 20 en 20
    $lang= $lang_web;
    $fl="20";//limite de elementos del cluster
    $op="search";
    $where="";//Por defecto busca en todas las fuentes
    $index=$index_search;// ""-todos los indices  au-autor ti-titulo mh-asunto 
    $q=$bvs_search;  //el texto que el usuario desea buscar
    
    $dia = new Dia($site, $col, $count, $output, $lang, $url_controler);
    $dia->setProxy($proxy_url, $proxy_port, $proxy_user, $proxy_password);
    
    // set additiona parameters
    $dia->setParam('fb',$fb);
    $dia->setParam('fl',$fl);
    $dia->setParam('qt',$qt);
    $dia->setParam('sort',$sort);
    
    //$initial_restricion = html_entity_decode($colectionData->restriction);
    // filtro de pesquisa = restricao inicial  E filtro where  E filtro externo E filtro(s) selecionados
    //$filterSearch = array_merge((array)$initial_restricion,(array)$whereFilter, (array)$filter,(array)$filter_chain);
    $filterSearch = array();
    if ( $addfilter != "" )
    {
        $filterSearch[] = $addfilter;
    }
    
    $diaResponse = $dia->search($q, $index, $filterSearch, $from);
    $result = json_decode($diaResponse);
    
    $num_found=$result->diaServerResponse[0]->response->numFound;//Total de resultados encontrados
    if($num_found)
        $found_request=true;
    else
       $found_request=false;
        
    $_SESSION["result_search"]=$result;
    $_SESSION["facet_fields"]=$result->diaServerResponse[0]->facet_counts->facet_fields;

?>
