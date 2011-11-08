<?php

session_start();

require_once("lang.php");
require_once("Paginacion.class.php");  

$result = $_SESSION["result_search"];

global $offset, $cant_res_pag, $id_read_result, $i_read_result, $lang_web, $blog_url;

$from=( $offset ? $offset+1 : 0 );
$count = $cant_res_pag;
$lang= $lang_web;

$num_found=$result->diaServerResponse[0]->response->numFound;//Total de resultados encontrados 
$doc_result=$result->diaServerResponse[0]->response->docs;//arreglo de documentos
$cant_doc=count($doc_result);
$trad=new language();
if($num_found==0)
{
   echo $trad->Translated_Word("nofoundresult",$lang); 
}
else
{ 
    if($id_read_result)//si se dio click en algun resultado obtenido
    {
       echo "<div class='buscadorbvs-result-item'>"; 
          $i=$i_read_result;
          echo "<h3>";
          if($lang=="en")
          {
             if($doc_result[$i]->ti[1])
               echo $doc_result[$i]->ti[1];
             else
               echo $doc_result[$i]->ti[0];  
          } 
          else
            {
                if($doc_result[$i]->ti_es[0])
                  echo $doc_result[$i]->ti_es[0]; 
                else
                    echo $doc_result[$i]->ti[0];   
            }
         
          echo "</h3>";//fin h3 titulo  
          echo "<div class='item-autor'>";
          $array_autores=$doc_result[$i]->au;
          if($array_autores)
          {
              echo $trad->Translated_Word("autores",$lang).": ";
              $cant_autores=count($array_autores);
              $i_au=0;
              foreach ($array_autores as $autores) 
              {
                  $i_au++;
                  if($i_au==$cant_autores) 
                    echo $autores;
                  else
                    echo "$autores"."; ";  
              }
          }
          
          if($array_autores)
          {
               echo $trad->Translated_Word("fuente",$lang).": ";
               echo $doc_result[$i]->fo[0];
          }
          
          
          echo "</div>";//fin div autores 
          echo "<div class='item-tipo'>";   
          if(is_array($doc_result[$i]->type))
          {
             echo $trad->Translated_Word($doc_result[$i]->type[0],$lang); 
          }
          else
          {
              echo $trad->Translated_Word($doc_result[$i]->type,$lang);
          }
          
          echo " [ ID: ".$doc_result[$i]->id ." ] ";
          //if(!$array_autores)
            //echo "<br>";
          if($array_autores)
            echo $trad->Translated_Word("idioma",$lang)."(s): ".$trad->Translated_Word(strtolower($doc_result[$i]->la[0]),$lang);
          
          echo "</div>";//fin div tipo
          
          echo "<div class='item-resumen'>";
          if($doc_result[$i]->ab)
          {
              if($lang=="es")
              {
                  if($doc_result[$i]->ab_Es)
                     echo $doc_result[$i]->ab_Es[0];
                  else
                     echo $doc_result[$i]->ab[0];
              }
              else
              {
                  if($lang=="en")
                  {
                      if($doc_result[$i]->ab_En)
                         echo $doc_result[$i]->ab_En[0];
                      else
                         echo $doc_result[$i]->ab[0];
                  }
                  else
                     echo $doc_result[$i]->ab[0];
              }
                
          }    
         echo "</div>"; 
          
            
          if($doc_result[$i]->mh)
          {
              echo "<div class='item-asunto'>";
              echo $trad->Translated_Word("asuntos",$lang).": ";
              $array_asuntos=$doc_result[$i]->mh;
              foreach ($array_asuntos as $asuntos) 
              {
                   echo "<a href=?find_again=".urlencode($asuntos)." >".$asuntos."</a>&nbsp;&nbsp;&nbsp;";    
              }
              echo "</div>"; 
          }
          
        
            
          if($doc_result[$i]->ur)
          {
              echo "<div class='item-texto'>";
              echo "<a target='_blank' href='".$doc_result[$i]->ur[0]."'>".$trad->Translated_Word("textoes",$lang)."</a>";
              echo "</div>";
          }
     
     echo "</div>";       
    }
    else//se muestran los resultados de la busqueda normalmente 
    {
        
        $paginacion = new Paginacion($from, 10, $num_found);
        $pag_actual= $paginacion->getPaginaActual();
        $pag_final= $paginacion->getPaginas();
        
       //Este es el paginado para el header 
       echo "<div class='buscadorbvs-result-pag-header'>";
       echo "<div class='buscadorbvs-result-pag-header-item1'>";
       if($pag_final!=1)
       {
           $res1=1+$count*($pag_actual-1);
           $res2=$res1+$count-1;
           if($num_found<$res2)
            $res2=$num_found;
           echo $trad->Translated_Word("result",$lang)."   ".$res1."-".$res2." ".$trad->Translated_Word("of",$lang)." ".$num_found;
           echo "</div>";
           echo "<div class='buscadorbvs-result-pag-header-item2'>";
           if($pag_actual!=1)
              echo  $paginacion->getRenderSingle('-1',$trad->Translated_Word("anterior",$lang));
              
            $paginacion->render();
            
            if($pag_final!=$pag_actual)
                echo $paginacion->getRenderSingle('+1',$trad->Translated_Word("siguiente",$lang)); 
       }
       
       echo "</div>";     
       echo "</div>";
       //fin del paginado para el header
        
        echo "<div class='buscadorbvs-result'>";
        echo "<ul class='buscadorbvs-result-ul'>";
        for($i=0;$i<$cant_doc;$i++)
        {  
            echo "<li class='buscadorbvs-result-li'>";
              $num = $i+1+$count*($pag_actual-1);
              
              $termino = "";
              if($lang=="en")
              {
                 if($doc_result[$i]->ti[1])
                   $termino = $doc_result[$i]->ti[1];
                 else
                   $termino = $doc_result[$i]->ti[0];  
              } 
              else
                {
                    if($doc_result[$i]->ti_es[0])
                      $termino = $doc_result[$i]->ti_es[0]; 
                    else
                        $termino = $doc_result[$i]->ti[0];   
                }
              
              echo "<div class='num'>".$num.".</div>";
              if($doc_result[$i]->type[0]=="terminology")
              {  
                  $decs_url = BUSCADORBVS_PLUGIN_URL."decs_detail.php?term=".urlencode($termino)."&lang=".$lang;
                  echo "<h3><A target='_blank' href=".$decs_url.">"; 
              } 
              else  
                echo "<h3><A href=?read_result=".$doc_result[$i]->id."&index_result=".$i.">";
              if($lang=="en")
              {
                 if($doc_result[$i]->ti[1])
                   echo $doc_result[$i]->ti[1]."</A></h3>";
                 else
                   echo $doc_result[$i]->ti[0]."</A></h3>";  
              } 
              else
                {
                    if($doc_result[$i]->ti_es[0])
                      echo $doc_result[$i]->ti_es[0]."</A></h3>"; 
                    else
                        echo $doc_result[$i]->ti[0]."</A></h3>";   
                }
                
                
              $array_autores=$doc_result[$i]->au;
              if($array_autores)
              {
                  echo "<div class='autor'>";
                  $cant_autores=count($array_autores);
                  $i_au=0;
                  foreach ($array_autores as $autores) 
                  {
                      $i_au++;
                      if($i_au==$cant_autores) 
                        echo $autores;
                      else
                        echo "$autores"."; ";  
                  }
                  echo "</div>";
              }
              
              
              if(is_array($doc_result[$i]->type))
              {
                 if($doc_result[$i]->type[0]=="article")
                  {
                      $ta=$doc_result[$i]->ta[0];
                      $enlace="http://portal.revistas.bvs.br/transf.php?xsl=xsl/titles.xsl&xml=http://catserver.bireme.br/cgi-bin/wxis1660.exe/?IsisScript=../cgi-bin/catrevistas/catrevistas.xis|database_name=TITLES|list_type=title|cat_name=ALL|from=1|count=50&lang=pt&comefrom=home&home=false&task=show_magazines&request_made_adv_search=false&lang=pt&show_adv_search=false&help_file=/help_pt.htm&connector=ET&search_exp=".urlencode($ta);
                      echo  "<div class='article'><a target=_blank href=$enlace>".$ta."<a>".strrchr($doc_result[$i]->fo[0], ";")."</div>"; 
                  }
                  else
                  if($doc_result[$i]->type[0]=="terminology")
                  {
                      echo "<div class='terminores'>";
                      if($lang=="en")
                        echo $doc_result[$i]->ab_en[0];
                      else
                        echo $doc_result[$i]->ab_es[0];
                        
                      echo "</div>";
                  } 
              }
              else
              {
                  if($doc_result[$i]->type=="article")
                  {
                      $ta=$doc_result[$i]->ta[0];
                      $enlace="http://portal.revistas.bvs.br/transf.php?xsl=xsl/titles.xsl&xml=http://catserver.bireme.br/cgi-bin/wxis1660.exe/?IsisScript=../cgi-bin/catrevistas/catrevistas.xis|database_name=TITLES|list_type=title|cat_name=ALL|from=1|count=50&lang=pt&comefrom=home&home=false&task=show_magazines&request_made_adv_search=false&lang=pt&show_adv_search=false&help_file=/help_pt.htm&connector=ET&search_exp=".urlencode($ta);
                      echo  "<div class='article'><a target=_blank href=$enlace>".$ta."<a>".strrchr($doc_result[$i]->fo[0], ";")."</div>"; 
                  }
              }
               
              if(is_array($doc_result[$i]->type))
              {
                  echo "<div class='source'>".$trad->Translated_Word($doc_result[$i]->type[0],$lang);
              }    
              else
              {
                  echo "<div class='source'>".$trad->Translated_Word($doc_result[$i]->type,$lang);
              }
              
              echo " [ ID: ".$doc_result[$i]->id ." ] ";
              if( ($doc_result[$i]->type || is_array($doc_result[$i]->type)) && $doc_result[$i]->la[0]!="" )
              {
                  echo $trad->Translated_Word("idioma",$lang)."(s): ";
                
                  echo $trad->Translated_Word(strtolower($doc_result[$i]->la[0]),$lang)."</div>";
              }
                
              
              if($doc_result[$i]->ur)
              {
                 echo "<div class='texto'><a target='_blank' href='".$doc_result[$i]->ur[0]."'>".$trad->Translated_Word("textoes",$lang)."</a></div>";
              }
              else
              {
                 //echo "<a href=javascript:window.print()>Imprimir esta pagina</a>";  
              }
               
              echo "</li>";
              
        }
      echo "</ul>";
      echo "</div>";
       //Este es el paginado para el footer
       echo "<div class='buscadorbvs-result-pag-footer'>";
       echo "<div class='buscadorbvs-result-pag-footer-item1'>";
       if($pag_final!=1)
       {
           $res1=1+$count*($pag_actual-1);
           $res2=$res1+$count-1;
           if($num_found<$res2)
            $res2=$num_found;
           echo $trad->Translated_Word("result",$lang)."   ".$res1."-".$res2." ".$trad->Translated_Word("of",$lang)." ".$num_found;
           echo "</div>";
           echo "<div class='buscadorbvs-result-pag-footer-item2'>";
           if($pag_actual!=1)
              echo  $paginacion->getRenderSingle('-1',$trad->Translated_Word("anterior",$lang));
              
            $paginacion->render();
            
            if($pag_final!=$pag_actual)
                echo $paginacion->getRenderSingle('+1',$trad->Translated_Word("siguiente",$lang));
       }
            
       echo "</div>";     
       echo "</div>";
       //fin del paginado para el footer        
    }
      
}  
  
  
?>
