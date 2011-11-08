<?php
    
    class language
    {
        var $arr_es=array();//arreglo de palabras en espanol
        var $arr_en=array();//arreglo de palabras en ingles
        
        function language()
        {           
            $path_lang_xml=dirname(__FILE__);
            if (file_exists($path_lang_xml.'/lang/es.xml'))
            {
               $languaje_es = simplexml_load_file($path_lang_xml.'/lang/es.xml');
               
               $this->arr_es["buscadobvs"]=$languaje_es->buscadobvs;
               $this->arr_es["filtrarpor"]=$languaje_es->filtrarpor;
               $this->arr_es["idioma"]=$languaje_es->idioma;    
               $this->arr_es["es"]=$languaje_es->es;
               $this->arr_es["en"]=$languaje_es->en;
               $this->arr_es["pt"]=$languaje_es->pt;
               $this->arr_es["fr"]=$languaje_es->fr;
               $this->arr_es["it"]=$languaje_es->it;
               $this->arr_es["de"]=$languaje_es->de;
               $this->arr_es["ar"]=$languaje_es->ar;
               $this->arr_es["ara"]=$languaje_es->ara;
               $this->arr_es["ko"]=$languaje_es->ko;
               $this->arr_es["ja"]=$languaje_es->ja;
               $this->arr_es["bg"]=$languaje_es->bg;
               $this->arr_es["bos"]=$languaje_es->bos;
               $this->arr_es["cat"]=$languaje_es->cat;
               $this->arr_es["ru"]=$languaje_es->ru;
               $this->arr_es["rum"]=$languaje_es->rum;
               $this->arr_es["uk"]=$languaje_es->uk;
               $this->arr_es["tr"]=$languaje_es->tr;
               $this->arr_es["sv"]=$languaje_es->sv;
               $this->arr_es["srp"]=$languaje_es->srp;
               $this->arr_es["slo"]=$languaje_es->slo;
               $this->arr_es["sl"]=$languaje_es->sl;
               $this->arr_es["pl"]=$languaje_es->pl;
               $this->arr_es["no"]=$languaje_es->no;
               $this->arr_es["nl"]=$languaje_es->nl;
               $this->arr_es["dut"]=$languaje_es->dut;
               $this->arr_es["lav"]=$languaje_es->lav;
               $this->arr_es["lit"]=$languaje_es->lit;
               $this->arr_es["ice"]=$languaje_es->ice;
               $this->arr_es["hu"]=$languaje_es->hu;
               $this->arr_es["hrv"]=$languaje_es->hrv;
               $this->arr_es["he"]=$languaje_es->he;
               $this->arr_es["fin"]=$languaje_es->fin;
               $this->arr_es["ch"]=$languaje_es->ch;
               $this->arr_es["da"]=$languaje_es->da;
               $this->arr_es["cs"]=$languaje_es->cs;
               
               $this->arr_es["textoes"]=$languaje_es->textoes; 
               $this->arr_es["siguiente"]=$languaje_es->siguiente; 
               $this->arr_es["anterior"]=$languaje_es->anterior;
               $this->arr_es["autores"]=$languaje_es->autores; 
               $this->arr_es["fuente"]=$languaje_es->fuente;
               $this->arr_es["asuntos"]=$languaje_es->asunto;
               //para las facetas  
               $this->arr_es["type"]=$languaje_es->type;
               $this->arr_es["book"]=$languaje_es->book;
               $this->arr_es["monography"]=$languaje_es->monography;
               $this->arr_es["congress and conference"]=$languaje_es->congress; 
               $this->arr_es["article"]=$languaje_es->article;
               $this->arr_es["thesis"]=$languaje_es->thesis;
               $this->arr_es["non-conventional"]=$languaje_es->non_conventional;
               $this->arr_es["project document"]=$languaje_es->project;
               $this->arr_es["internet resource"]=$languaje_es->internet_resource;
               $this->arr_es["oba"]=$languaje_es->oba;
               $this->arr_es["terminology"]=$languaje_es->terminology;
               $this->arr_es["video"]=$languaje_es->video;
               $this->arr_es["podcast"]=$languaje_es->podcast;
               
               $this->arr_es["clinical_aspect"]=$languaje_es->clinical_aspect;
               $this->arr_es["etiology"]=$languaje_es->etiology;
               $this->arr_es["prognosis"]=$languaje_es->prognosis;
               $this->arr_es["therapy"]=$languaje_es->therapy;
               $this->arr_es["diagnosis"]=$languaje_es->diagnosis;
               $this->arr_es["prediction"]=$languaje_es->prediction;
               
               $this->arr_es["fulltext"]=$languaje_es->fulltext;
               
               $this->arr_es["type_of_study"]=$languaje_es->type_of_study;
               $this->arr_es["case_reports"]=$languaje_es->case_reports;
               $this->arr_es["randomized_controlled_trial"]=$languaje_es->randomized_controlled_trial;
               $this->arr_es["incidence"]=$languaje_es->incidence;
               $this->arr_es["prevalence"]=$languaje_es->prevalence;
               $this->arr_es["case_control"]=$languaje_es->case_control;
               $this->arr_es["cohort"]=$languaje_es->cohort;
               $this->arr_es["economic_evaluations"]=$languaje_es->economic_evaluations;
               $this->arr_es["guideline"]=$languaje_es->guideline;
               $this->arr_es["systematic_reviews"]=$languaje_es->systematic_reviews;
               $this->arr_es["technology_assessments"]=$languaje_es->technology_assessments;
               $this->arr_es["clinical_trials"]=$languaje_es->clinical_trials;
               
               $this->arr_es["limit"]=$languaje_es->limit;
               $this->arr_es["humans"]=$languaje_es->humans;
               $this->arr_es["female"]=$languaje_es->female;
               $this->arr_es["animals"]=$languaje_es->animals;
               $this->arr_es["male"]=$languaje_es->male;
               $this->arr_es["adult"]=$languaje_es->adult;
               $this->arr_es["middle_age"]=$languaje_es->middle_age;
               $this->arr_es["aged"]=$languaje_es->aged;
               $this->arr_es["adolescent"]=$languaje_es->adolescent;
               $this->arr_es["child"]=$languaje_es->child;
               $this->arr_es["preschool"]=$languaje_es->preschool;
               $this->arr_es["pregnancy"]=$languaje_es->pregnancy;
               $this->arr_es["infant"]=$languaje_es->infant;
               $this->arr_es["newborn"]=$languaje_es->newborn;   
               
               $this->arr_es["mh_cluster"]=$languaje_es->mh_cluster;
               $this->arr_es["ta_cluster"]=$languaje_es->ta_cluster;
               $this->arr_es["la"]=$languaje_es->la;
               $this->arr_es["year_cluster"]=$languaje_es->year_cluster;
               $this->arr_es["mostrarmas"]=$languaje_es->mostrarmas;
               //Para la vista de busqueda
               $this->arr_es["bwhere"]=$languaje_es->bwhere;
               $this->arr_es["bfind"]=$languaje_es->bfind;
               $this->arr_es["ballindex"]=$languaje_es->ballindex;
               $this->arr_es["btitle"]=$languaje_es->btitle;
               $this->arr_es["bauthor"]=$languaje_es->bauthor;
               $this->arr_es["bsubject"]=$languaje_es->bsubject;
               $this->arr_es["ballsourcecuba"]=$languaje_es->ballsourcecuba;
               $this->arr_es["bcumed"]=$languaje_es->bcumed;
               $this->arr_es["bSCIELO_CUBA"]=$languaje_es->bSCIELO_CUBA;
               $this->arr_es["blis"]=$languaje_es->blis;
               $this->arr_es["beventdirectory"]=$languaje_es->beventdirectory;
               $this->arr_es["ballsourceregional"]=$languaje_es->ballsourceregional;
               $this->arr_es["bgeneral"]=$languaje_es->bgeneral;
               $this->arr_es["bLILACS"]=$languaje_es->bLILACS;
               $this->arr_es["bIBECS"]=$languaje_es->bIBECS;
               $this->arr_es["bMEDLINE"]=$languaje_es->bMEDLINE;
               $this->arr_es["bSPECIALIZED"]=$languaje_es->bSPECIALIZED;
               $this->arr_es["bCIDSAUDE"]=$languaje_es->bCIDSAUDE;
               $this->arr_es["bDESASTRES"]=$languaje_es->bDESASTRES;
               $this->arr_es["bHISA"]=$languaje_es->bHISA;
               $this->arr_es["bHOMEOINDEX"]=$languaje_es->bHOMEOINDEX;
               $this->arr_es["bMEDCARIB"]=$languaje_es->bMEDCARIB;
               $this->arr_es["bREPIDISCA"]=$languaje_es->bREPIDISCA;
               $this->arr_es["bINTERNATIONAL"]=$languaje_es->bINTERNATIONAL;
               $this->arr_es["bPAHO"]=$languaje_es->bPAHO;
               $this->arr_es["bWHOLIS"]=$languaje_es->bWHOLIS;
               $this->arr_es["bSITES"]=$languaje_es->bSITES;
               $this->arr_es["bCVSP"]=$languaje_es->bCVSP;
               $this->arr_es["bDECS"]=$languaje_es->bDECS;
               $this->arr_es["bCOCHRANE"]=$languaje_es->bCOCHRANE;
               $this->arr_es["bCOCHRANE_REVIEWS"]=$languaje_es->bCOCHRANE_REVIEWS;
               $this->arr_es["bCOCHRANE_PROTOCOLS"]=$languaje_es->bCOCHRANE_PROTOCOLS;
               $this->arr_es["bCOCHRANE_CENTRAL"]=$languaje_es->bCOCHRANE_CENTRAL;
               $this->arr_es["bCOCHRANE_CMR"]=$languaje_es->bCOCHRANE_CMR;
               $this->arr_es["bCOCHRANE_HTA"]=$languaje_es->bCOCHRANE_HTA;
               $this->arr_es["bCOCHRANE_EED_BIBLIO"]=$languaje_es->bCOCHRANE_EED_BIBLIO;
               $this->arr_es["bCOCHRANE_EED_ABSTRACTS"]=$languaje_es->bCOCHRANE_EED_ABSTRACTS;
               $this->arr_es["bCOCHRANE_DARE_ABSTRACTS"]=$languaje_es->bCOCHRANE_DARE_ABSTRACTS;
               $this->arr_es["bCOCHRANE_AGENCIAS"]=$languaje_es->bCOCHRANE_AGENCIAS;
               $this->arr_es["bCOCHRANE_BANDOLIER"]=$languaje_es->bCOCHRANE_BANDOLIER;
               $this->arr_es["bCOCHRANE_CLIBPLUSREFS"]=$languaje_es->bCOCHRANE_CLIBPLUSREFS;
               $this->arr_es["bCOCHRANE_EVIDARGENT"]=$languaje_es->bCOCHRANE_EVIDARGENT;
               $this->arr_es["bCOCHRANE_GESTION"]=$languaje_es->bCOCHRANE_GESTION;
               $this->arr_es["bCOCHRANE_KOVACS"]=$languaje_es->bCOCHRANE_KOVACS;
               
               //Para la vista de administracion
               $this->arr_es["urlcontrol"]=$languaje_es->urlcontrol;
               $this->arr_es["resultpage"]=$languaje_es->resultpage;
               $this->arr_es["searchindex"]=$languaje_es->searchindex;
               $this->arr_es["searchsource"]=$languaje_es->searchsource; 
               
               $this->arr_es["result"]=$languaje_es->result;
               $this->arr_es["of"]=$languaje_es->of;
               $this->arr_es["nofoundresult"]=$languaje_es->nofoundresult; 
            }
            
            if(file_exists($path_lang_xml.'/lang/en.xml'))
            {
               $languaje_en = simplexml_load_file($path_lang_xml.'/lang/en.xml');
               
               $this->arr_en["buscadobvs"]=$languaje_en->buscadobvs;
               $this->arr_en["filtrarpor"]=$languaje_en->filtrarpor;
               $this->arr_en["idioma"]=$languaje_en->idioma;
               $this->arr_en["es"]=$languaje_en->es;
               $this->arr_en["en"]=$languaje_en->en;
               $this->arr_en["pt"]=$languaje_en->pt;
               $this->arr_en["fr"]=$languaje_en->fr;
               $this->arr_en["it"]=$languaje_en->it;
               $this->arr_en["de"]=$languaje_en->de;
               $this->arr_en["ar"]=$languaje_en->ar;
               $this->arr_en["ara"]=$languaje_en->ara;
               $this->arr_en["ko"]=$languaje_en->ko;
               $this->arr_en["ja"]=$languaje_en->ja;
               $this->arr_en["bg"]=$languaje_en->bg;
               $this->arr_en["bos"]=$languaje_en->bos;
               $this->arr_en["cat"]=$languaje_en->cat;
               $this->arr_en["ru"]=$languaje_en->ru;
               $this->arr_en["rum"]=$languaje_en->rum;
               $this->arr_en["uk"]=$languaje_en->uk;
               $this->arr_en["tr"]=$languaje_en->tr;
               $this->arr_en["sv"]=$languaje_en->sv;
               $this->arr_en["srp"]=$languaje_en->srp;
               $this->arr_en["slo"]=$languaje_en->slo;
               $this->arr_en["sl"]=$languaje_en->sl;
               $this->arr_en["pl"]=$languaje_en->pl;
               $this->arr_en["no"]=$languaje_en->no;
               $this->arr_en["nl"]=$languaje_en->nl;
               $this->arr_en["dut"]=$languaje_en->dut;
               $this->arr_en["lav"]=$languaje_en->lav;
               $this->arr_en["lit"]=$languaje_en->lit;
               $this->arr_en["ice"]=$languaje_en->ice;
               $this->arr_en["hu"]=$languaje_en->hu;
               $this->arr_en["hrv"]=$languaje_en->hrv;
               $this->arr_en["he"]=$languaje_en->he;
               $this->arr_en["fin"]=$languaje_en->fin;
               $this->arr_en["ch"]=$languaje_en->ch;
               $this->arr_en["da"]=$languaje_en->da;
               $this->arr_en["cs"]=$languaje_en->cs;
               
               $this->arr_en["textoes"]=$languaje_en->textoes; 
               $this->arr_en["siguiente"]=$languaje_en->siguiente; 
               $this->arr_en["anterior"]=$languaje_en->anterior;
               $this->arr_en["autores"]=$languaje_en->autores; 
               $this->arr_en["fuente"]=$languaje_en->fuente;
               $this->arr_en["asuntos"]=$languaje_en->asunto;
               //para las facetas  
               $this->arr_en["type"]=$languaje_en->type;
               $this->arr_en["book"]=$languaje_en->book;
               $this->arr_en["article"]=$languaje_en->article;
               $this->arr_en["monography"]=$languaje_en->monography;//*
               $this->arr_en["congress and conference"]=$languaje_en->congress;//*
               $this->arr_en["thesis"]=$languaje_en->thesis;
               $this->arr_en["non-conventional"]=$languaje_en->non_conventional;
               $this->arr_en["project document"]=$languaje_en->project;//*
               $this->arr_en["internet resource"]=$languaje_en->internet_resource;
               $this->arr_en["oba"]=$languaje_en->oba;
               $this->arr_en["terminology"]=$languaje_en->terminology;
               $this->arr_en["video"]=$languaje_en->video;
               $this->arr_en["podcast"]=$languaje_en->podcast;
               
               $this->arr_en["clinical_aspect"]=$languaje_en->clinical_aspect;
               $this->arr_en["etiology"]=$languaje_en->etiology;
               $this->arr_en["prognosis"]=$languaje_en->prognosis;
               $this->arr_en["therapy"]=$languaje_en->therapy;
               $this->arr_en["diagnosis"]=$languaje_en->diagnosis;
               $this->arr_en["prediction"]=$languaje_en->prediction;
               
               $this->arr_en["fulltext"]=$languaje_en->fulltext;
               
               $this->arr_en["type_of_study"]=$languaje_en->type_of_study;
               $this->arr_en["case_reports"]=$languaje_en->case_reports;
               $this->arr_en["randomized_controlled_trial"]=$languaje_en->randomized_controlled_trial;
               $this->arr_en["incidence"]=$languaje_en->incidence;
               $this->arr_en["prevalence"]=$languaje_en->prevalence;
               $this->arr_en["case_control"]=$languaje_en->case_control;
               $this->arr_en["cohort"]=$languaje_en->cohort;
               $this->arr_en["economic_evaluations"]=$languaje_en->economic_evaluations;
               $this->arr_en["guideline"]=$languaje_en->guideline;
               $this->arr_en["systematic_reviews"]=$languaje_en->systematic_reviews;
               $this->arr_en["technology_assessments"]=$languaje_en->technology_assessments;
               $this->arr_en["clinical_trials"]=$languaje_en->clinical_trials;
               
               $this->arr_en["limit"]=$languaje_en->limit;
               $this->arr_en["humans"]=$languaje_en->humans;
               $this->arr_en["female"]=$languaje_en->female;
               $this->arr_en["animals"]=$languaje_en->animals;
               $this->arr_en["male"]=$languaje_en->male;
               $this->arr_en["adult"]=$languaje_en->adult;
               $this->arr_en["middle_age"]=$languaje_en->middle_age;
               $this->arr_en["aged"]=$languaje_en->aged;
               $this->arr_en["adolescent"]=$languaje_en->adolescent;
               $this->arr_en["child"]=$languaje_en->child;
               $this->arr_en["preschool"]=$languaje_en->preschool;
               $this->arr_en["pregnancy"]=$languaje_en->pregnancy;
               $this->arr_en["infant"]=$languaje_en->infant;
               $this->arr_en["newborn"]=$languaje_en->newborn;   
               
               $this->arr_en["mh_cluster"]=$languaje_en->mh_cluster;
               $this->arr_en["ta_cluster"]=$languaje_en->ta_cluster;
               $this->arr_en["la"]=$languaje_en->la;
               $this->arr_en["year_cluster"]=$languaje_en->year_cluster;
               $this->arr_en["mostrarmas"]=$languaje_en->mostrarmas;
               //Para la vista de busqueda
               $this->arr_en["bwhere"]=$languaje_en->bwhere;
               $this->arr_en["bfind"]=$languaje_en->bfind;
               $this->arr_en["ballindex"]=$languaje_en->ballindex;
               $this->arr_en["btitle"]=$languaje_en->btitle;
               $this->arr_en["bauthor"]=$languaje_en->bauthor;
               $this->arr_en["bsubject"]=$languaje_en->bsubject;
               $this->arr_en["ballsourcecuba"]=$languaje_en->ballsourcecuba;
               $this->arr_en["bcumed"]=$languaje_en->bcumed;
               $this->arr_en["bSCIELO_CUBA"]=$languaje_en->bSCIELO_CUBA;
               $this->arr_en["blis"]=$languaje_en->blis;
               $this->arr_en["beventdirectory"]=$languaje_en->beventdirectory;
               $this->arr_en["ballsourceregional"]=$languaje_es->ballsourceregional;
               $this->arr_en["bgeneral"]=$languaje_en->bgeneral;
               $this->arr_en["bLILACS"]=$languaje_en->bLILACS;
               $this->arr_en["bIBECS"]=$languaje_en->bIBECS;
               $this->arr_en["bMEDLINE"]=$languaje_en->bMEDLINE;
               $this->arr_en["bSPECIALIZED"]=$languaje_en->bSPECIALIZED;
               $this->arr_en["bCIDSAUDE"]=$languaje_en->bCIDSAUDE;
               $this->arr_en["bDESASTRES"]=$languaje_en->bDESASTRES;
               $this->arr_en["bHISA"]=$languaje_en->bHISA;
               $this->arr_en["bHOMEOINDEX"]=$languaje_en->bHOMEOINDEX;
               $this->arr_en["bMEDCARIB"]=$languaje_en->bMEDCARIB;
               $this->arr_en["bREPIDISCA"]=$languaje_en->bREPIDISCA;
               $this->arr_en["bINTERNATIONAL"]=$languaje_en->bINTERNATIONAL;
               $this->arr_en["bPAHO"]=$languaje_en->bPAHO;
               $this->arr_en["bWHOLIS"]=$languaje_en->bWHOLIS;
               $this->arr_en["bSITES"]=$languaje_en->bSITES;
               $this->arr_en["bCVSP"]=$languaje_en->bCVSP;
               $this->arr_en["bDECS"]=$languaje_en->bDECS;
               $this->arr_en["bCOCHRANE"]=$languaje_en->bCOCHRANE;
               $this->arr_en["bCOCHRANE_REVIEWS"]=$languaje_en->bCOCHRANE_REVIEWS;
               $this->arr_en["bCOCHRANE_PROTOCOLS"]=$languaje_en->bCOCHRANE_PROTOCOLS;
               $this->arr_en["bCOCHRANE_CENTRAL"]=$languaje_en->bCOCHRANE_CENTRAL;
               $this->arr_en["bCOCHRANE_CMR"]=$languaje_en->bCOCHRANE_CMR;
               $this->arr_en["bCOCHRANE_HTA"]=$languaje_en->bCOCHRANE_HTA;
               $this->arr_en["bCOCHRANE_EED_BIBLIO"]=$languaje_en->bCOCHRANE_EED_BIBLIO;
               $this->arr_en["bCOCHRANE_EED_ABSTRACTS"]=$languaje_en->bCOCHRANE_EED_ABSTRACTS;
               $this->arr_en["bCOCHRANE_DARE_ABSTRACTS"]=$languaje_en->bCOCHRANE_DARE_ABSTRACTS;
               $this->arr_en["bCOCHRANE_AGENCIAS"]=$languaje_en->bCOCHRANE_AGENCIAS;
               $this->arr_en["bCOCHRANE_BANDOLIER"]=$languaje_en->bCOCHRANE_BANDOLIER;
               $this->arr_en["bCOCHRANE_CLIBPLUSREFS"]=$languaje_en->bCOCHRANE_CLIBPLUSREFS;
               $this->arr_en["bCOCHRANE_EVIDARGENT"]=$languaje_en->bCOCHRANE_EVIDARGENT;
               $this->arr_en["bCOCHRANE_GESTION"]=$languaje_en->bCOCHRANE_GESTION;
               $this->arr_en["bCOCHRANE_KOVACS"]=$languaje_en->bCOCHRANE_KOVACS;
               
               //Para la vista de administracion
               $this->arr_en["urlcontrol"]=$languaje_en->urlcontrol;
               $this->arr_en["resultpage"]=$languaje_en->resultpage;
               $this->arr_en["searchindex"]=$languaje_en->searchindex;
               $this->arr_en["searchsource"]=$languaje_en->searchsource;
               
               $this->arr_en["result"]=$languaje_en->result;
               $this->arr_en["of"]=$languaje_en->of;
               $this->arr_en["nofoundresult"]=$languaje_en->nofoundresult;  
            }
            
            return;
        } 
        
        //Si el idioma no existe se traducira la palabra por defecto a español
        function Translated_Word($word,$lang_word)
        {
            if($lang_word=="es")
            {
                if(array_key_exists($word , $this->arr_es))
                    return $this->arr_es[$word]; 
                else
                 return $word;
            }
            else
            if($lang_word=="en")
            {
                if(array_key_exists($word , $this->arr_en))
                    return $this->arr_en[$word]; 
                else
                 return $word;
            }
            else
                if(array_key_exists($word , $this->arr_es))
                    return $this->arr_es[$word]; 
                else
                 return $word;
        }

    }
    
?>