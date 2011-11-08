<?php
  
/*
Plugin Name:    buscadorbvs
Description:    Adiciona un widget que permite buscar utilizando la misma filosofia de la bvs y otro que es opcional de a&ntilde;adir por el usuario el cual le permite filtrar la busqueda por determinadas facetas. Los resultados de la busqueda se muestran en un widget que el usuario puede ubicar donde desee.
Version:        0.2.2
Author:         Ing. Pavel Rivera Abdo
Author Email:   pavel.rivera@infomed.sld.cu
*/

session_start();

define('BUSCADORBVS_PLUGIN_URL', plugin_dir_url( __FILE__ ));

function on__widget_buscadorbvs_style()
{
 
    $urlcss = BUSCADORBVS_PLUGIN_URL;
 
    wp_enqueue_style('screencss', $urlcss.'css/screen.css');
    wp_enqueue_style('jquerytreeviewcss', $urlcss.'css/jquery.treeview.css');
    wp_enqueue_style('styledefault', $urlcss.'css/styledefault.css');
}
add_action('wp_print_styles', 'on__widget_buscadorbvs_style');

function on__widget_buscadorbvs_script()
{

    $urlscript = BUSCADORBVS_PLUGIN_URL;
 
    /*wp_enqueue_script('jquery', $urlscript.'js/jquery.js');
    wp_enqueue_script('jquerycookie', $urlscript.'js/jquery.cookie.js');
    wp_enqueue_script('jquerytreeview', $urlscript.'js/jquery.treeview.js');
    wp_enqueue_script('demo', $urlscript.'js/demo.js');*/ 
}
add_action('wp_print_scripts', 'on__widget_buscadorbvs_script');



add_action('template_redirect', 'bvs_search_template_redirect',1);
function bvs_search_template_redirect() 
{   
    $sidebars_widgets = get_option('sidebars_widgets', array());
    $sidebars_widgets_backups=$sidebars_widgets;//Aqui guardo los widgets que tenia al inicio
    
    if ( empty($GLOBALS['_wp_sidebars_widgets']) ) 
       $GLOBALS['_wp_sidebars_widgets']=$sidebars_widgets_backups; 

    //echo "<link rel=stylesheet href=".BUSCADORBVS_PLUGIN_URL."css/jquery.treeview.css />";
    //echo "<link rel=stylesheet href=".BUSCADORBVS_PLUGIN_URL."css/screen.css />";
    echo "<script type=text/javascript src=".BUSCADORBVS_PLUGIN_URL."js/jquery.js></script>";
    echo "<script type=text/javascript src=".BUSCADORBVS_PLUGIN_URL."js/jquery.cookie.js></script>";
    echo "<script type=text/javascript src=".BUSCADORBVS_PLUGIN_URL."js/jquery.treeview.js></script>";
    echo "<script type=text/javascript src=".BUSCADORBVS_PLUGIN_URL."js/demo.js></script>";
   
    
   if( isset($_POST["gogo"]) && empty($_POST["texto"]) )//Si se oprimio buscar y no hay termino para buscar
   {      
       //Aqui elimino el widget infobuscadorfacet y el infobuscadorresult 
       foreach ( (array) $sidebars_widgets as $index => $sidebar ) 
        { 
            if ( is_array($sidebar) && $index!="wp_inactive_widgets") 
            {
                //echo "nombre sidebar: ". $index."<br>";
                $name_sidebar="";
                foreach ( $sidebar as $i => $name ) 
                {       
                    //echo "widget: ".$sidebars_widgets[$index][$i]."<br>";
                    if( strpos( $sidebars_widgets[$index][$i], "infobuscadorfacet" ) !== false || strpos( $sidebars_widgets[$index][$i], "infobuscadorresult" ) !== false )
                     {
                         unset($sidebars_widgets[$index][$i]);                   
                     }      
                }
            }
        }
       
       $GLOBALS['_wp_sidebars_widgets']=$sidebars_widgets;//con esto cambio el valor que esta en memoria 
       return;//Devuelvo la pagina como estaba al inicio
   }
   else
   if( isset($_POST["gogo"]) || isset($_GET["offset"]) || isset($_GET["read_result"]) || isset($_GET["find_again"])
   || isset($_GET["type"]) || $_GET["fulltext"] || $_GET["ta_cluster"] || $_GET["la_cluster"] || $_GET["year_cluster"]
   || isset($_GET["clinical_aspect"]) || $_GET["type_of_study"] || $_GET["mh_cluster"] || $_GET["limit"]
   || isset($_GET["fb_ta_cluster"]) || isset($_GET["fb_year_cluster"]) )
   {//Si se oprimio buscar o se selecciono alguna pagina o se dio click en algun resultado de la busqueda o se 
   //dio click en algun termino del asunto de un resultado
   
   $name_sidebar="";
   //aqui obtengo el nombre del sidebar del widget infobuscadorresult
   foreach ( (array) $sidebars_widgets as $index => $sidebar ) 
    { 
        if ( is_array($sidebar) && $index!="wp_inactive_widgets") 
        {
            //echo "nombre sidebar: ". $index."<br>"; 
            foreach ( $sidebar as $i => $name ) 
            {       
                //echo "widget: ".$sidebars_widgets[$index][$i]."<br>";
                if( strpos( $sidebars_widgets[$index][$i], "infobuscadorresult" ) !== false )
                 {
                     $name_sidebar=$index;                   
                 }      
            }
        }
    }
   
   if($name_sidebar!="")
   {
       //Aqui elimino los widgets que estan en el sidebar del widget infobuscadorresult excepto
       //el widget infobuscador y el widget infobuscadorfacet si estan en ese sidebar
        foreach ( (array) $sidebars_widgets as $index => $sidebar ) 
        { 
            if ( is_array($sidebar) && $index!="wp_inactive_widgets") 
            {
                //echo "nombre sidebar: ". $index."<br>";
                if($index==$name_sidebar)
                {
                   foreach ( $sidebar as $i => $name ) 
                    {       
                        //echo "widget: ".$sidebars_widgets[$index][$i]."<br>";
                        $widget_name = $sidebars_widgets[$index][$i];
                        if( strpos( $widget_name, "infobuscador" )!==false )
                         {
                                             
                         }
                         else//Si algunos de los widget del sidebar no pertenece al grupo de widget infobuscador 
                         {
                            //echo "widget: ".$sidebars_widgets[$index][$i]."<br>";
                            unset($sidebars_widgets[$index][$i]);//elimino de la memoria  
                         }     
                    } 
                }
            }
        }
   }  
    
    $GLOBALS['_wp_sidebars_widgets']=$sidebars_widgets;//con esto cambio el valor que esta en memoria
    
   //Aqui se realiza la busqueda en vbs
  //*******************************************************************************************************  
    
  //declaro estas variables globales para acceder a ellas desde los ficheros incluidos
       global $bvs_search, $offset, $cant_res_pag, $id_read_result, $i_read_result, $index_search, $addfilter1, $fb1, $lang_web, $found_request, $blog_url, $num_conn;
       
       $blog_url = get_bloginfo('url');
       $found_request=false;//Esto es para saber si tengo o no que mostrar el widget infobuscadorfacet
       $index_search="";//Inicializo el indice de busqueda para que siempre busque por Todos los indices
       $addfilter1="";//Inicializo el filtro
       $fb1="";//Inicializo el facet browser
       $num_conn = 0;//inicializo la connexion por defecto
       
       $current_language = substr(strtolower(get_bloginfo('language')),0,2);//esto devuelve es, en, pt 
       $lang_web=$current_language;//Inicializo el idioma (compatibilidad con el plugin multi-language-framework)
       
       $addfilter11= isset($_GET["type"])?"Type:\\\"".$_GET["type"]."\\\"":"";//Guardamos el filtro para el tipo de documento
       $addfilter22= isset($_GET["fulltext"])?"fulltext:\\\"".$_GET["fulltext"]."\\\"":"";//Guardamos el filtro para el texto completo
       $addfilter33= isset($_GET["ta_cluster"])?"ta_cluster:\\\"".$_GET["ta_cluster"]."\\\"":"";//Guardamos el filtro para la revista seleccionada
       $addfilter44= isset($_GET["la_cluster"])?"la:\\\"".$_GET["la_cluster"]."\\\"":"";//Guardamos el filtro para el idioma seleccionado
       $addfilter55= isset($_GET["year_cluster"])?"year_cluster:\\\"".$_GET["year_cluster"]."\\\"":"";//Guardamos el filtro para el año seleccionado
       $addfilter66= isset($_GET["clinical_aspect"])?"clinical_aspect:\\\"".$_GET["clinical_aspect"]."\\\"":"";//Guardamos el filtro para el aspecto clinico seleccionado
       $addfilter77= isset($_GET["type_of_study"])?"type_of_study:\\\"".$_GET["type_of_study"]."\\\"":"";//Guardamos el filtro para el tipo de estudio seleccionado
       $addfilter88= isset($_GET["mh_cluster"])?"mh_cluster:\\\"".$_GET["mh_cluster"]."\\\"":"";//Guardamos el filtro para el asunto seleccionado
       $addfilter99= isset($_GET["limit"])?"limit:\\\"".$_GET["limit"]."\\\"":"";//Guardamos el filtro para el limite seleccionado
       if(isset($_GET["fb_ta_cluster"]))
       { 
           $_SESSION["fb_year_cluster"]=20;//Guardo la cantidad inicial para el cluster de años
           $fb1="ta_cluster:".$_GET["fb_ta_cluster"];
           unset($_GET["fb_ta_cluster"]);
       }
       
       if(isset($_GET["fb_year_cluster"]))
       {
           $_SESSION["fb_ta_cluster"]=20;//Guardo la cantidad inicial para el cluster de las revistas
           $fb1="year_cluster:".$_GET["fb_year_cluster"];
           unset($_GET["fb_year_cluster"]);
       }
       
       //Destruimos las varibles pasadas por GET 
       unset($_GET["type"]);
       unset($_GET["fulltext"]);
       unset($_GET["ta_cluster"]);
       unset($_GET["la_cluster"]);
       unset($_GET["year_cluster"]);
       unset($_GET["clinical_aspect"]);
       unset($_GET["type_of_study"]);
       unset($_GET["mh_cluster"]); 
       unset($_GET["limit"]); 
       
       if($addfilter11=="")
       {
           if( !empty($_SESSION["addfilter11"]) )
           {
               $addfilter11=$_SESSION["addfilter11"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter11"]=$addfilter11;//almacenamos el filtro en la variable de sesion
       }
       //***
       if($addfilter22=="")
       {
           if( !empty($_SESSION["addfilter22"]) )
           {
               $addfilter22=$_SESSION["addfilter22"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter22"]=$addfilter22;//almacenamos el filtro en la variable de sesion
       }
      //***
      if($addfilter33=="")
       {
           if( !empty($_SESSION["addfilter33"]) )
           {
               $addfilter33=$_SESSION["addfilter33"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter33"]=$addfilter33;//almacenamos el filtro en la variable de sesion
       }
       //***
      if($addfilter44=="")
       {
           if( !empty($_SESSION["addfilter44"]) )
           {
               $addfilter44=$_SESSION["addfilter44"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter44"]=$addfilter44;//almacenamos el filtro en la variable de sesion
       }
       //***
      if($addfilter55=="")
       {
           if( !empty($_SESSION["addfilter55"]) )
           {
               $addfilter55=$_SESSION["addfilter55"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter55"]=$addfilter55;//almacenamos el filtro en la variable de sesion
       }
       //***
      if($addfilter66=="")
       {
           if( !empty($_SESSION["addfilter66"]) )
           {
               $addfilter66=$_SESSION["addfilter66"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter66"]=$addfilter55;//almacenamos el filtro en la variable de sesion
       }
       //***
      if($addfilter77=="")
       {
           if( !empty($_SESSION["addfilter77"]) )
           {
               $addfilter77=$_SESSION["addfilter77"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter77"]=$addfilter77;//almacenamos el filtro en la variable de sesion
       }
       //***
      if($addfilter88=="")
       {
           if( !empty($_SESSION["addfilter88"]) )
           {
               $addfilter88=$_SESSION["addfilter88"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter88"]=$addfilter88;//almacenamos el filtro en la variable de sesion
       }
       //***
      if($addfilter99=="")
       {
           if( !empty($_SESSION["addfilter99"]) )
           {
               $addfilter99=$_SESSION["addfilter99"];
           }
       }
       else//Se paso un filtro por GET
       {
           $_SESSION["addfilter99"]=$addfilter99;//almacenamos el filtro en la variable de sesion
       }
      
      //Concatenacion de todos los filtros
      $array_filters=array();
      $array_filters[0]=$addfilter11;
      $array_filters[1]=$addfilter22;
      $array_filters[2]=$addfilter33;
      $array_filters[3]=$addfilter44;
      $array_filters[4]=$addfilter55;
      $array_filters[5]=$addfilter66;
      $array_filters[6]=$addfilter77;
      $array_filters[7]=$addfilter88;
      $array_filters[8]=$addfilter99;
      $count_filter=count($array_filters);
      
      for($i=0;$i<$count_filter;$i++)
      {
         if($array_filters[$i]!="")
           {
               $addfilter1.= $array_filters[$i]." AND ";
           }  
      }
      $leng_filter=strlen($addfilter1);
      $addfilter1=substr($addfilter1,0,$leng_filter-5);//Esto es para quitar el " AND " al final del filtro 
      
      if(isset($_SESSION["source_search"]))
      {
          $temp_source_search=$_SESSION["source_search"];
                   
           $arr_source_search = explode("connexion", $temp_source_search);//Obtengo en un array la fuente de busqueda y la connexion que se va a usar
           $source_search = $arr_source_search[0];//Guardo la fuente de busqueda
           $arr_temp_source_search = explode(",", $source_search);//Esto es para si son fuentes concatenadas obtenerlas en un array
           $cant_sources = count($arr_temp_source_search);
           if($cant_sources>1)
           {
               if($addfilter1!="")
                $addfilter1.=" AND db:(";
               else
                $addfilter1.="db:("; 
               for($i=0;$i<$cant_sources;$i++)
               {
                  $addfilter1.="\"".$arr_temp_source_search[$i]."\"";
                  if($i==$cant_sources-1)//si es el ultimo
                  {
                     $addfilter1.=")"; 
                  }
                  else
                  {
                     $addfilter1.=" OR "; 
                  } 
               }
           }
           else
           {
               if($source_search!="")
               { 
                   if($addfilter1=="")
                   {
                       if($source_search=="campusvirtualsp")
                       {
                           $addfilter1="db:".$source_search."*";
                       }  
                       else
                       {
                          $addfilter1="db:\"".$source_search."\""; 
                       }     
                   }
                  else
                   {
                      if($source_search=="campusvirtualsp")
                       {
                           $addfilter1.=" AND db:".$source_search."*";
                       }
                       else
                        $addfilter1.="db:\"".$source_search."\"";
                   } 
               }
           }     
           
           $num_conn = $arr_source_search[1];//Guardo la conexion que utliza esa fuente 
           
      }
       
       if(isset($_POST["gogo"]))//Si se dio click en buscar
       {   
           $url_controler=$_POST["url_controler"];//Guardo la url del controladador
           $_SESSION["url_controler"]=$url_controler;//Guardo la url del controladador en una variable de sesion 
           $index_search=isset($_POST["index"])?$_POST["index"]:"";//Guardo el indice de busqueda pasado por POST
           $_SESSION["index_search"]=$index_search;//Guardo el indice de busqueda en una variable de sesion
           $temp_source_search=isset($_POST["where"])?$_POST["where"]:"";//Guardo la fuente de busqueda pasado por POST 
           $addfilter1="";//inicializo los filtros para que no me filtre por nada
           $arr_source_search = explode("connexion", $temp_source_search);//Obtengo en un array la fuente de busqueda y la connexion que se va a usar
           $source_search = $arr_source_search[0];//Guardo la fuente de busqueda
           $arr_temp_source_search = explode(",", $source_search);//Esto es para si son fuentes concatenadas obtenerlas en un array
           $cant_sources = count($arr_temp_source_search);
           
           if($cant_sources>1)
           {
               if($addfilter1!="")
                $addfilter1.=" AND db:(";
               else
                $addfilter1.="db:("; 
               for($i=0;$i<$cant_sources;$i++)
               {
                  $addfilter1.="\"".$arr_temp_source_search[$i]."\"";
                  if($i==$cant_sources-1)//si es el ultimo
                  {
                     $addfilter1.=")"; 
                  }
                  else
                  {
                     $addfilter1.=" OR "; 
                  } 
               }
           }
           else
           {
               if($source_search!="")
               { 
                   if($addfilter1=="")
                   {
                       if($source_search=="campusvirtualsp")
                       {
                           $addfilter1="db:".$source_search."*";
                       }  
                       else
                       {
                          $addfilter1="db:\"".$source_search."\""; 
                       }     
                   }
                  else
                  {
                        if($source_search=="campusvirtualsp")
                       {
                           $addfilter1="db:".$source_search."*";
                       }
                       else
                        $addfilter1.="db:\"".$source_search."\""; 
                  }
                    
               }
           }
           
           $num_conn = $arr_source_search[1];//Guardo la conexion que utliza esa fuente
           $_SESSION["source_search"]=$temp_source_search;//Guardo la fuente de busqueda en una variable de sesion
           $bvs_search=$_POST["texto"];//Guardo el termino a buscar
           $_SESSION["bvs_search"]=$_POST["texto"];//Guardo el termino a buscar en una variable de sesion
           $cant_res_pag=$_POST["cant_pag"];//Guardo la cantidad de resultados por pagina a mostrar
           $_SESSION["cant_resp_pag"]=$_POST["cant_pag"];//Guardo la cantidad de resultados por pagina a mostrar en una variable de sesion
           $offset=0;//Esto es para que empiese en la primera pagina cada ves que se inicie una busqueda 
           $_SESSION["offset"]=$offset;//Guardo la pagina inicial
           $_SESSION["fb_ta_cluster"]=20;//Guardo la cantidad inicial para el cluster de las revistas 
           $_SESSION["fb_year_cluster"]=20;//Guardo la cantidad inicial para el cluster de años 
           
           unset($_SESSION["addfilter11"]);//Elimino el filtros por tipo de documento 
           unset($_SESSION["addfilter22"]);//Elimino el filtro por texto completo
           unset($_SESSION["addfilter33"]);//Elimino el filtro por la revista
           unset($_SESSION["addfilter44"]);//Elimino el filtro para el idioma
           unset($_SESSION["addfilter55"]);//Elimino el filtro para el año
           unset($_SESSION["addfilter66"]);//Elimino el filtro para el aspecto clinico  
           unset($_SESSION["addfilter77"]);//Elimino el filtro para el tipo de estudio  
           unset($_SESSION["addfilter88"]);//Elimino el filtro para el asunto  
           unset($_SESSION["addfilter99"]);//Elimino el filtro para el limite
              
           $fb1="";//Inicializo el facet browser
       }
       else
       {
           if(isset($_GET["find_again"]))//Si se dio click en un termino de asunto de un resultado 
              {
                  $bvs_search="\"".$_GET["find_again"]."\"";//Guardo el termino a buscar
                  $_SESSION["bvs_search"]=$bvs_search;//Guardo el termino a buscar en una variable de sesion
                  $cant_res_pag=$_SESSION["cant_resp_pag"];//Guardo la cantidad de resultados por pagina a mostrar  
                  $index_search="mh";//Esto es para decir que se va a buscar por el indice asunto
                  $_SESSION["index_search"]=$index_search;//Guardo el indice de busqueda por el que se busco anteriormente
                  $offset=0;//Esto es para que empieze en la primera pagina 
                  $_SESSION["offset"]=$offset;//Guardo la pagina inicial
                  $_SESSION["fb_ta_cluster"]=20;//Guardo la cantidad inicial para el cluster de las revistas 
                  $_SESSION["fb_year_cluster"]=20;//Guardo la cantidad inicial para el cluster de años
                  //unset($_GET["find_again"]);
                  //unset($_GET["index_result"]);
                  
                  unset($_SESSION["addfilter11"]);//Elimino el filtros por tipo de documento
                  unset($_SESSION["addfilter22"]);//Elimino el filtro por texto completo
                  unset($_SESSION["addfilter33"]);//Elimino el filtro por la revista
                  unset($_SESSION["addfilter44"]);//Elimino el filtro para el idioma
                  unset($_SESSION["addfilter55"]);//Elimino el filtro para el año
                  unset($_SESSION["addfilter66"]);//Elimino el filtro para el aspecto clinico  
                  unset($_SESSION["addfilter77"]);//Elimino el filtro para el tipo de estudio  
                  unset($_SESSION["addfilter88"]);//Elimino el filtro para el asunto  
                  unset($_SESSION["addfilter99"]);//Elimino el filtro para el limite   
                  $addfilter1="";//inicializo los filtros para que no me filtre por nada
                  $fb1="";//Inicializo el facet browser
                
              }
              else
              if(isset($_GET["read_result"]))//Si selecciono un resultado 
              {  
                   $bvs_search=$_SESSION["bvs_search"];//Guardo el termino a buscar
                   $cant_res_pag=$_SESSION["cant_resp_pag"];//Guardo la cantidad de resultados por pagina a mostrar
                   $id_read_result=$_GET["read_result"];
                   $i_read_result=$_GET["index_result"];
                   $offset=$_SESSION["offset"];
                   $index_search=$_SESSION["index_search"];
                   
                   unset($_GET["read_result"]);
                   unset($_GET["index_result"]);
                        
              }
              else//si se dio click en la pagina deseada o en alguna facetas 
              {
                  $bvs_search=$_SESSION["bvs_search"];//Guardo el termino a buscar
                  $cant_res_pag=$_SESSION["cant_resp_pag"];//Guardo la cantidad de resultados por pagina a mostrar
                  $index_search=$_SESSION["index_search"];
                  $offset=$_GET["offset"];
                  $_SESSION["offset"]=$offset;
                  unset($_GET["offset"]);
                  
              }
              
       }
        
       include_once dirname(__FILE__) . "/Dia.php";
       include_once dirname(__FILE__) . "/index.php";  
    
  //********************************************************************************************************
  
       if(!$found_request)
       {
           //Aqui elimino el widget infobuscadorfacet
           foreach ( (array) $sidebars_widgets as $index => $sidebar ) 
            { 
                if ( is_array($sidebar) && $index!="wp_inactive_widgets") 
                {
                    //echo "nombre sidebar: ". $index."<br>";
                    $name_sidebar="";
                    foreach ( $sidebar as $i => $name ) 
                    {       
                        //echo "widget: ".$sidebars_widgets[$index][$i]."<br>";
                        if( strpos( $sidebars_widgets[$index][$i], "infobuscadorfacet" ) !== false )
                         {
                             unset($sidebars_widgets[$index][$i]);                   
                         }      
                    }
                }
            }
           
           $GLOBALS['_wp_sidebars_widgets']=$sidebars_widgets;//con esto cambio el valor que esta en memoria 
       }
          
     return; 
   } 
   else
   {
       //Aqui elimino el widget infobuscadorfacet y el infobuscadorresult
       foreach ( (array) $sidebars_widgets as $index => $sidebar ) 
        { 
            if ( is_array($sidebar) && $index!="wp_inactive_widgets") 
            {
                //echo "nombre sidebar: ". $index."<br>";
                $name_sidebar="";
                foreach ( $sidebar as $i => $name ) 
                {       
                    //echo "widget: ".$sidebars_widgets[$index][$i]."<br>";
                    if( strpos( $sidebars_widgets[$index][$i], "infobuscadorfacet" ) !== false || strpos( $sidebars_widgets[$index][$i], "infobuscadorresult" ) !== false )
                     {
                         unset($sidebars_widgets[$index][$i]);                   
                     }      
                }
            }
        }
        
       $GLOBALS['_wp_sidebars_widgets']=$sidebars_widgets;//con esto cambio el valor que esta en memoria   
       return;
   }
}
 




class Widget_Infobuscador extends WP_Widget {
    /*Función Constructor*/
    function Widget_Infobuscador() {
        /* Opciones del Widget nombre de clase y su descipcion */
        $widget_ops = array('classname'=>'widget_Infobuscador','description'=>'Adiciona un buscador para la BVS');

        /* Controles de las opciones del Widget en el panel de administracion */
        $control_ops= array();

        /* Creando el Widget. El idbase que se usa en el formulario de configuración, Titulo del Widget en el panel de control,
         y las opciones del widget y en control */
        $this->WP_Widget('Infobuscador','BVS Buscador',$widget_ops, $control_ops);
    }
    /* Formulario de configuracion del widget */
    function form($instance) {
        //opciones predeterminadas
        $default=array('num_pag'=>10,'show_index'=>false,'show_source'=>false, 'url_controler'=>"http://test-de-aplicaciones.sld.cu:8080/iahx-controller/");
        /* Conjunto de las opciones de widget. Las almacenamos en una variable para
         * que sea mas facil a la hora de hacer alguna operacion */
        $instance=wp_parse_args((array)$instance,$default);
        $title = htmlspecialchars($instance['title']);
        $num_pag = htmlspecialchars($instance['num_pag']);
        $show_index = $instance['show_index'];
        $show_source = $instance['show_source'];
        $url_controler= $instance['url_controler'];
        
        require_once("lang.php");
        $trad=new language();
        $current_language = substr(strtolower(get_bloginfo('language')),0,2);//esto devuelve es, en, pt 
        $lang_wp=$current_language;//WPLANG

    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title')?>"><?php echo $trad->Translated_Word("btitle",$lang_wp)  ?>:</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" type="text" name="<?php echo $this->get_field_name('title');?>" value="<?php echo $title; ?>" class="widefat" style="width:100%"/>
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('num_pag');?>"><?php echo $trad->Translated_Word("resultpage",$lang_wp)  ?>:</label>
            <select id="<?php echo $this->get_field_id('num_pag');?>" name="<?php echo $this->get_field_name('num_pag');?>" class="widefat" style="width:100%">
            <?php
                for($i=5;$i<11;$i++){
                    echo "<option value='".$i."'";
                    if ( $i == $num_pag ) echo 'selected="selected"';
                    echo ">".$i."</option>";
                }

            ?>
            </select>
        </p>
        <p>
        <input class="checkbox" type="checkbox" <?php
                if (! empty($instance['show_index'])) {
                    echo 'checked="checked"';
                }
             ?> id="<?php echo $this->get_field_id('show_index'); ?>" name="<?php echo $this->get_field_name('show_index'); ?>" />
            <label for="<?php echo $this->get_field_id('show_index'); ?>"><?php echo $trad->Translated_Word("searchindex",$lang_wp)  ?></label>
        </p>
        <input class="checkbox" type="checkbox" <?php
                if (! empty($instance['show_source'])) {
                    echo 'checked="checked"';
                }
             ?> id="<?php echo $this->get_field_id('show_source'); ?>" name="<?php echo $this->get_field_name('show_source'); ?>" />
            <label for="<?php echo $this->get_field_id('show_source'); ?>"><?php echo $trad->Translated_Word("searchsource",$lang_wp)  ?></label>
        </p>
        
        <?php
        
    }
    /*procesa las opciones del widget que se guardarán*/
    function update($new_instance, $old_instance) {
        $instance=$old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['num_pag']=$new_instance['num_pag'];
        $instance['show_index']=$new_instance['show_index'];
        $instance['show_source']=$new_instance['show_source'];
        $instance['url_controler']=$new_instance['url_controler'];
        return $instance;  
    }
    /* Funcion que muestra el widget en el sidebar */
    function widget($args, $instance) {
        
        require_once("lang.php");
        $trad=new language();
        $current_language = substr(strtolower(get_bloginfo('language')),0,2);//esto devuelve es, en, pt 
        $lang_wp=$current_language;
        
        // saca el contenido del widget
        extract($args);
        $title=apply_filters('widget_title',empty($instance['title'])?$trad->Translated_Word("buscadobvs",$lang_wp): $instance['title']);
        
        //$title=$trad->Translated_Word("buscadobvs",$lang_wp); 
        $num_pag=empty($instance['num_pag'])?10:$instance['num_pag'];
        $show_index=isset($instance['show_index'])?$instance['show_index']:false;
        $show_source=isset($instance['show_source'])?$instance['show_source']:false;
        
        $valor_buscar = "";
        $index_buscar = "";
        $source_buscar = "";
        if( isset($_POST["gogo"]) && !empty($_POST["texto"]) )//Si se oprimio buscar y hay termino para buscar
        {
           $valor_buscar =  $_POST["texto"];
           $index_buscar =  $_POST["index"];
           $source_buscar = $_POST["where"];
        }
        
        settype($num_pag,"integer");

        /* Antes del widget (definido por el theme) */
        echo $before_widget;

        /* El titulo del widget (antes y despues definidos por el theme) */
        if($title)
            echo $before_title. $title .$after_title;
              
        ?>     
        <form action="" method="post" name="frm_infobuscador">
          <p class="buscar">
            <?php echo "<input type='text' class='text' name='texto' value='" . $valor_buscar . "' />\n"; ?>  
            <?php echo "<input type='hidden' name='cant_pag' value='" . htmlspecialchars($num_pag) . "' />\n"; ?>
            <?php echo "<input type='hidden' name='url_controler' value='" . $url_controler . "' />\n"; ?> 
            <input type="submit" name="gogo" class="submit" value="<?php echo $trad->Translated_Word("bfind",$lang_wp)  ?>" />
          </p>
          <?php
             if($show_index){
            ?>
          <p class="indice">
            <select class="select" name="index">
              <option value="" <?php if($index_buscar=="") echo "selected='selected'"?> ><?php echo $trad->Translated_Word("ballindex",$lang_wp)  ?></option>
              <option value="ti" <?php if($index_buscar=="ti") echo "selected='selected'"?>><?php echo $trad->Translated_Word("btitle",$lang_wp)  ?></option>
              <option value="au" <?php if($index_buscar=="au") echo "selected='selected'"?>><?php echo $trad->Translated_Word("bauthor",$lang_wp)  ?></option>
              <option value="mh" <?php if($index_buscar=="mh") echo "selected='selected'"?>><?php echo $trad->Translated_Word("bsubject",$lang_wp)  ?></option>
            </select>
          </p>
          <?php
             }
             if($show_source){
                
             $path_source_xml=dirname(__FILE__);
            if (file_exists($path_source_xml.'/config/source.xml'))
            {
                $source_list = simplexml_load_file($path_source_xml.'/config/source.xml');
            }      
                   
            ?>
          <p class="fuente">
            <label for="donde"><?php echo $trad->Translated_Word("bwhere",$lang_wp)  ?>: </label>
            <select class="select1" name="where">
            
            <?php
            if(!$source_list)
            {?>
                <option value="" <?php if($source_buscar=="") echo "selected='selected'"?>><?php echo $trad->Translated_Word("ballsourcecuba",$lang_wp)  ?></option>
              <option value="cumed" <?php if($source_buscar=="cumed") echo "selected='selected'"?>><?php echo $trad->Translated_Word("bcumed",$lang_wp)  ?></option>
              <option value="lis" <?php if($source_buscar=="lis") echo "selected='selected'"?>><?php echo $trad->Translated_Word("blis",$lang_wp)  ?></option>
              <option value="evento" <?php if($source_buscar=="evento") echo "selected='selected'"?>><?php echo $trad->Translated_Word("beventdirectory",$lang_wp)  ?></option>
            <?php  
            }  
            else
             {
                 $arr_source_conn = $source_list->coneccion;
                 $cant1 = count($arr_source_conn);
                 for($i=0;$i<$cant1;$i++)
                 {
                     $arr_source = $source_list->coneccion[$i]->where;
                     $cant2 = count($arr_source);
                    for($j=0;$j<$cant2;$j++)
                    {
                         $value = $source_list->coneccion[$i]->where[$j]->value;
                         $name = $source_list->coneccion[$i]->where[$j]->name;
                         $class = $source_list->coneccion[$i]->where[$j]->class;
                         $name_trans = $trad->Translated_Word((String)$name,$lang_wp);
                        ?>
                         <option value="<?php echo $value."connexion".$i ?>" class="<?php echo $class ?>" <?php if($source_buscar==$value."connexion".$i) echo "selected='selected'"?>><?php echo $name_trans  ?></option> 
                      <?php 
                    }      
                 }
             } 
             ?> 
              
            </select>
          </p>
          <?php
             }
            ?>
        </form>  
        <div class="mas"></div>
        <?php
        /* Despues del widget (definido por el theme) */
        echo $after_widget;
            
    }
}

add_action('widgets_init', create_function('', 'register_widget("Widget_Infobuscador");'));



class Widget_Infobuscador_Facet extends WP_Widget {
    /*Función Constructor*/
    function Widget_Infobuscador_Facet() {
        /* Opciones del Widget nombre de clase y su descipcion */
        $widget_ops = array('classname'=>'widget_Infobuscador_facet','description'=>'Adiciona filtros al buscador para la BVS');

        /* Controles de las opciones del Widget en el panel de administracion */
        $control_ops= array();

        /* Creando el Widget. El idbase que se usa en el formulario de configuración, Titulo del Widget en el panel de control,
         y las opciones del widget y en control */
        $this->WP_Widget('Infobuscadorfacet','BVS Facetas',$widget_ops, $control_ops);
    }
    /* Formulario de configuracion del widget */
    function form($instance) {
        
        require_once("lang.php");
        $trad=new language();
        $current_language = substr(strtolower(get_bloginfo('language')),0,2);//esto devuelve es, en, pt 
        $lang_wp=$current_language;
        
        //opciones predeterminadas
        $default=array();
        /* Conjunto de las opciones de widget. Las almacenamos en una variable para
         * que sea mas facil a la hora de hacer alguna operacion */
        $instance=wp_parse_args((array)$instance,$default);
        $title = htmlspecialchars($instance['title']);
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title')?>"><?php echo $trad->Translated_Word("btitle",$lang_wp)  ?>:</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" type="text" name="<?php echo $this->get_field_name('title');?>" value="<?php echo $title; ?>" class="widefat" style="width:100%"/>
        </p>
        <?php
    }
    
    /*procesa las opciones del widget que se guardarán*/
    function update($new_instance, $old_instance) {
        $instance=$old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
    
    /* Funcion que muestra el widget en el sidebar */
    function widget($args, $instance) {
        
        require_once("lang.php");
        $trad=new language();
        $current_language = substr(strtolower(get_bloginfo('language')),0,2);//esto devuelve es, en, pt 
        $lang_wp=$current_language;
        
        // saca el contenido del widget
        extract($args);
        $title=apply_filters('widget_title',empty($instance['title'])?$trad->Translated_Word("filtrarpor",$lang_wp): $instance['title']);
        //$title=$trad->Translated_Word("filtrarpor",$lang_wp); 
        
        /* Antes del widget (definido por el theme) */
        echo $before_widget;

        /* El titulo del widget (antes y despues definidos por el theme) */
        if($title)
            echo $before_title. $title .$after_title;
            
        if( isset($_POST["gogo"]) || isset($_GET["find_again"]) || isset($_SESSION["addfilter11"]) || 
        isset($_SESSION["addfilter22"]) || isset($_SESSION["addfilter33"]) || isset($_SESSION["addfilter44"]) || 
        isset($_SESSION["addfilter55"]) || isset($_SESSION["addfilter66"]) || isset($_SESSION["addfilter77"]) || 
        isset($_SESSION["addfilter88"]) || isset($_SESSION["addfilter99"]) || isset($_SESSION["fb_ta_cluster"])
        || isset($_SESSION["fb_year_cluster"]) )
        {  
            $facet_fields=$_SESSION["facet_fields"];
            unset($_SESSION["facet_fields"]);   
   
        echo "<div id=main>";
        echo "<ul id=browser class=filetree>";
           
               $count_type=count($facet_fields->type);
               if($count_type>0)
               {
                   echo "<li class=closed><span class=closed>".$trad->Translated_Word("type",$lang_wp)."</span>";
                   echo   "<ul>";
                   for($i=0;$i<$count_type;$i++)
                   {
                       $name_type= $facet_fields->type[$i][0];
                       $count_name= $facet_fields->type[$i][1];
                       echo   "<li><span class=closed><a href=?type=".urlencode($name_type).">".$trad->Translated_Word($name_type,$lang_wp)." (".$count_name.")</a></span></li>";  
                   }
                   echo   "</ul>";
                   echo "</li>";
               }
               
               $count_clinical_aspect=count($facet_fields->clinical_aspect);
               if($count_clinical_aspect>0)
               {
                   echo "<li class=closed><span class=closed>".$trad->Translated_Word("clinical_aspect",$lang_wp)."</span>";
                   echo   "<ul>";
                   for($i=0;$i<$count_clinical_aspect;$i++)
                   {
                       $name_clinical_aspect= $facet_fields->clinical_aspect[$i][0];
                       $count_aspect= $facet_fields->clinical_aspect[$i][1];
                       echo   "<li><span class=closed><a href=?clinical_aspect=".$name_clinical_aspect.">".$trad->Translated_Word($name_clinical_aspect,$lang_wp)." (".$count_aspect.")</a></span></li>";  
                   }
                   echo   "</ul>";
                   echo "</li>";
               }
               
               $count_fulltext=count($facet_fields->fulltext);
               if($count_fulltext>0)
                   echo "<li><span class=closed><a href=?fulltext=1>".$trad->Translated_Word("fulltext",$lang_wp)." (".$facet_fields->fulltext[0][1].")</a></span></li>";
               
               $count_type_of_study=count($facet_fields->type_of_study);
               if($count_type_of_study>0)
               {
                   echo "<li class=closed><span class=closed>".$trad->Translated_Word("type_of_study",$lang_wp)."</span>";
                   echo   "<ul>";
                   for($i=0;$i<$count_type_of_study;$i++)
                   {
                       $name_type_of_studyt= $facet_fields->type_of_study[$i][0];
                       $count_study= $facet_fields->type_of_study[$i][1];
                       if($name_type_of_studyt!=$trad->Translated_Word($name_type_of_studyt,$lang_wp))
                       echo   "<li><span class=closed><a href=?type_of_study=".urlencode($name_type_of_studyt).">".$trad->Translated_Word($name_type_of_studyt,$lang_wp)." (".$count_study.")</a></span></li>";  
                   }
                   echo   "</ul>";
                   echo "</li>";
               }
               
               $count_mh_cluster=count($facet_fields->mh_cluster);
               if($count_mh_cluster>0)
               {
                   echo "<li class=closed><span class=closed>".$trad->Translated_Word("mh_cluster",$lang_wp)."</span>";
                   echo   "<ul>";
                   for($i=0;$i<$count_mh_cluster;$i++)
                   {
                       $name_mh_cluster= $facet_fields->mh_cluster[$i][0];
                       $count_mh= $facet_fields->mh_cluster[$i][1];
                       echo   "<li><span class=closed><a href=?mh_cluster=".urlencode($name_mh_cluster).">".$name_mh_cluster." (".$count_mh.")</a></span></li>";  
                   }
                   echo   "</ul>";
                   echo "</li>";
               }
               
               $count_limit=count($facet_fields->limit);
               if($count_limit>0)
               {
                   echo "<li class=closed><span class=closed>".$trad->Translated_Word("limit",$lang_wp)."</span>";
                   echo   "<ul>";
                   for($i=0;$i<$count_limit;$i++)
                   {
                       $name_limit= strtolower($facet_fields->limit[$i][0]);
                       $count_name_limit= $facet_fields->limit[$i][1];
                       if($name_limit!=$trad->Translated_Word($name_limit,$lang_wp))
                       echo   "<li><span class=closed><a href=?limit=".urlencode($name_limit).">".$trad->Translated_Word($name_limit,$lang_wp)." (".$count_name_limit.")</a></span></li>";  
                   }
                   echo   "</ul>";
                   echo "</li>";
               }
               
               $count_ta_cluster=count($facet_fields->ta_cluster);
               if($count_ta_cluster>0)
               {
                    echo "<li class=closed><span class=closed>".$trad->Translated_Word("ta_cluster",$lang_wp)."</span>";
                    echo     "<ul>";
                    for($i=0;$i<$count_ta_cluster;$i++)
                    {
                         $name_revista= $facet_fields->ta_cluster[$i][0];
                         $cant_revista= $facet_fields->ta_cluster[$i][1];                         
                         echo         "<li><span class=closed><a href=?ta_cluster=".urlencode($name_revista).">".$name_revista." (".$cant_revista.")</a></span></li>";                         
                    }
                    
                    if($count_ta_cluster==$_SESSION["fb_ta_cluster"])
                    {
                        $_SESSION["fb_ta_cluster"]+=20;
                        echo          "<li><span class=closed><a href=?fb_ta_cluster=".$_SESSION["fb_ta_cluster"].">".$trad->Translated_Word("mostrarmas",$lang_wp)."</a></span></li>";//f.ta_cluster.facet.limit
                    }
                          
                    echo     "</ul>";
                    echo "</li>";
               }
               
               $count_la=count($facet_fields->la);
               if($count_la>1)
               {
                    echo "<li class=closed><span class=closed>".$trad->Translated_Word("la",$lang_wp)."</span>";
                    echo     "<ul>";
                    for($i=0;$i<$count_la;$i++)
                    { 
                        $name_la= $facet_fields->la[$i][0];
                         $cant_la= $facet_fields->la[$i][1];
                         if($name_la!=$trad->Translated_Word($name_la,$lang_wp))
                         echo         "<li><span class=closed><a href=?la_cluster=".$name_la.">".$trad->Translated_Word($name_la,$lang_wp)." (".$cant_la.")</a></span></li>";   
                    }
                    echo     "</ul>";
                    echo "</li>";
               }
               
               $count_year_cluster=count($facet_fields->year_cluster);
               if($count_year_cluster>0)
               {
                    echo "<li class=closed><span class=closed>".$trad->Translated_Word("year_cluster",$lang_wp)."</span>"; 
                    echo     "<ul>";
                    for($i=0;$i<$count_year_cluster;$i++)
                    {
                         $name_year_cluster= $facet_fields->year_cluster[$i][0];
                         $cant_year_cluster= $facet_fields->year_cluster[$i][1];                         
                         
                         echo         "<li><span class=closed><a href=?year_cluster=".$name_year_cluster.">".$name_year_cluster." (".$cant_year_cluster.")</a></span></li>";
                         
                    }
                    
                    if($count_year_cluster==$_SESSION["fb_year_cluster"])
                    {
                        $_SESSION["fb_year_cluster"]+=20;
                        echo         "<li><span class=closed><a href=?fb_year_cluster=".$_SESSION["fb_year_cluster"].">".$trad->Translated_Word("mostrarmas",$lang_wp)."</a></span></li>";//f.year_cluster.facet.limit 
                    }
                         
                    echo     "</ul>";
                    echo "</li>";
               }
                
       echo "</ul>";          
       echo "</div>";
        }
        else
        {/*?>
          
          <div id="main">
            <ul id="browser" class="filetree">
            
                <li class="closed"><span class="closed">Tipo</span></li>
                <li><span class="closed">Texto completo</span></li>
                <li class="closed"><span class="closed">Revista</span></li>
                <li class="closed"><span class="closed">Idioma</span></li>
                <li class="closed"><span class="closed">A&ntilde;o</span></li>
                
            </ul>
                 
        </div>
            
        <?php */}
        /* Despues del widget (definido por el theme) */
        echo $after_widget;
    }
}

add_action('widgets_init', create_function('', 'register_widget("Widget_Infobuscador_Facet");'));


class Widget_Infobuscador_Result extends WP_Widget {
    /*Función Constructor*/
    function Widget_Infobuscador_Result() {
        /* Opciones del Widget nombre de clase y su descipcion */
        $widget_ops = array('classname'=>'widget_Infobuscador_result','description'=>'Muestra los resultados del buscador para la BVS');

        /* Controles de las opciones del Widget en el panel de administracion */
        $control_ops= array();

        /* Creando el Widget. El idbase que se usa en el formulario de configuración, Titulo del Widget en el panel de control,
         y las opciones del widget y en control */
        $this->WP_Widget('InfobuscadorResult','BVS Resultados',$widget_ops, $control_ops);
    }
    
    /* Formulario de configuracion del widget */
    function form($instance) {
        
    }
    
    /*procesa las opciones del widget que se guardarán*/
    function update($new_instance, $old_instance) {
        $instance=$old_instance;
        return $instance;  
    }
    
    /* Funcion que muestra el widget en el sidebar */
    function widget($args, $instance) {
        
        extract($args);
        $current_language = substr(strtolower(get_bloginfo('language')),0,2);//esto devuelve es, en, pt 
        $lang_wp=$current_language;

        /* Antes del widget (definido por el theme) */
        echo $before_widget;

        /* El titulo del widget (antes y despues definidos por el theme) */
        if($title)
            echo $before_title. $title .$after_title;

         include_once dirname(__FILE__) . "/lang.php";
         include_once dirname(__FILE__) . "/Paginacion.class.php";    
         include_once dirname(__FILE__) . "/result.php";
                      
        /* Despues del widget (definido por el theme) */
        echo $after_widget;
            
    }
}

add_action('widgets_init', create_function('', 'register_widget("Widget_Infobuscador_Result");'));



   function buscadorbvs_install(){
      //   
   }
   function buscadorbvs_uninstall(){
      //   
   }   

add_action('activate_buscadorbvs/buscadorbvs.php','buscadorbvs_install');
add_action('deactivate_buscadorbvs/buscadorbvs.php', 'buscadorbvs_uninstall');  
  
  
?>
