<?php
//----------------------------------------------------------------------
//  CrawlTrack 2.3.0
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.fr
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: display-seo.php
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array and variable
$nbrtag=array();
$listkeyworddelicious = array();
$listtag=array();
$values=array();
$tablinkyahoo=array();
$tabpageyahoo=array();
$tablinkmsn=array();
$tabpagemsn=array(); 
$tablinkdelicious=array();    
$tagdelicious="";
$cachename=$navig.$period.$site.$firstdayweek.$localday.$graphpos.$crawltlang;

//start the caching if fopen exist
if(function_exists('fopen'))
    {
    cache($cachename);
    }

//database connection
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//include menu 
include"include/menumain.php";
include"include/menusite.php";
include"include/timecache.php";
//request to get the msn and yahoo positions data and the number of Delicious bookmarks, Technorati link and  Delicious keywords
if($period>=10)
    {
    $sqlseo = "SELECT   linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious,tagdelicious FROM crawlt_seo_position
    WHERE  id_site='".sql_quote($site)."'
    AND  date >='".sql_quote($daterequestseo)."' 
    AND  date <'".sql_quote($daterequest2seo)."'        
    ORDER BY date";
    }
else
    {
    $sqlseo = "SELECT  linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious,tagdelicious FROM crawlt_seo_position
    WHERE  id_site='".sql_quote($site)."' 
    AND  date >='".sql_quote($daterequestseo)."'        
    ORDER BY date";
    }
    
        
    $requeteseo = mysql_query($sqlseo, $connexion) or die("MySQL query error");
    $nbrresult=mysql_num_rows($requeteseo);
    if($nbrresult>=1)
        {  
     $i=1;
        while ($ligneseo = mysql_fetch_row($requeteseo))                                                                              
            {           
            $tablinkyahoo[] = $ligneseo[0];
            $tabpageyahoo[] = $ligneseo[1];
            $tablinkmsn[] = $ligneseo[2];
            $tabpagemsn[] =$ligneseo[3]; 
            $tablinkdelicious[]= $ligneseo[4];          
              

                
            $tabtag=@unserialize($ligneseo[5]);                  
            if(is_array($tabtag))
                {                             
                foreach ($tabtag as $key => $value)
                    {
                    $nbrtag[$key]=$tabtag[$key];
                    } 
                 $checktagdelicious=1;                                         
                } 
             else
                 {
                 $checktagdelicious=0;
                 }                                   
            }

        if(array_sum($tablinkdelicious)!=0 && $checktagdelicious == 1)
            {
           arsort($nbrtag);  

             foreach($nbrtag as $tag => $value)
                {
                 if($crawltcharset==1)
                    {
                    if( !isutf8($tag))
                        {
                        $tag2 = mb_convert_encoding($tag, "UTF-8", "auto");
                        }
                    else
                        {
                        $tag2=$tag;
                        }
                    }
                else
                    {
                    $tag2 = mb_convert_encoding($tag, "ISO-8859-1", "auto");
                    }                     
                if($tag2!="")
                        {                    
                        $tagdelicious=$tagdelicious.$tag2."(".$nbrtag[$tag]."), ";
                        if(strlen($tagdelicious)> (55*$i +(4*($i-1))))
                            {
                            $tagdelicious=$tagdelicious."<br>";
                            $i++;
                            }
                         }         
                }
            $tagdelicious= rtrim($tagdelicious,"<br>");
            $tagdelicious= rtrim($tagdelicious," ");
            $tagdelicious= rtrim($tagdelicious,",");   
            } 
        else
            {
            $tagdelicious='-';
            }
            
        //preparation of values for display

        if($period == 0 OR $period>= 1000)
            {
            $linkyahoo = numbdisp($tablinkyahoo[0]);
            $pageyahoo = numbdisp($tabpageyahoo[0]);
            $linkmsn = numbdisp($tablinkmsn[0]);
            $pagemsn = numbdisp($tabpagemsn[0]);
            $linkdelicious = numbdisp($tablinkdelicious[0]);
            }
        else
            {
            $linkyahoo = numbdisp($tablinkyahoo[0])." --> ".numbdisp($tablinkyahoo[($nbrresult-1)]);
            $pageyahoo = numbdisp($tabpageyahoo[0])." --> ".numbdisp($tabpageyahoo[($nbrresult-1)]);
            $linkmsn = numbdisp($tablinkmsn[0])." --> ".numbdisp($tablinkmsn[($nbrresult-1)]);
            $pagemsn = numbdisp($tabpagemsn[0])." --> ".numbdisp($tabpagemsn[($nbrresult-1)]);
            $linkdelicious = numbdisp($tablinkdelicious[0])."-->".numbdisp($tablinkdelicious[($nbrresult-1)]);                        
            }                                            
        }
        
//request to have the number of Googlebot visits
if($period>=10)
    {
    $sqlgoogle = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."' 
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'GoogleBot'    
    GROUP BY crawler_name";
    }
else
    {
    $sqlgoogle = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."'
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'GoogleBot'    
    GROUP BY crawler_name";
    }

$requetegoogle = mysql_query($sqlgoogle, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requetegoogle);
if($nbrresult>=1)
    {
    $lignegoogle = mysql_fetch_row($requetegoogle);
    $visitgoogle=$lignegoogle[1];
    }
else
    {
    $visitgoogle= 0;
    }

//request to have the number of MsnBot visits
if($period>=10)
    {
    $sqlmsn = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."' 
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'MSN Bot'    
    GROUP BY crawler_name";
    }
else
    {
    $sqlmsn = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."' 
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'MSN Bot'    
    GROUP BY crawler_name";
    }
        
$requetemsn = mysql_query($sqlmsn, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requetemsn);
if($nbrresult>=1)
    {
    $lignemsn = mysql_fetch_row($requetemsn);
    $visitmsn=$lignemsn[1];
    }
else
    {
    $visitmsn=0;
    }

//request to have the number of Slurp Inktomi (Yahoo) visits
if($period>=10)
    {
    $sqlyahoo = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler  FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."'
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'Slurp Inktomi (Yahoo)'    
    GROUP BY crawler_name";
    }
else
    {
    $sqlyahoo = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler  FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."' 
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'Slurp Inktomi (Yahoo)'    
    GROUP BY crawler_name";
    }

$requeteyahoo = mysql_query($sqlyahoo, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requeteyahoo);
if($nbrresult>=1)
    {
    $ligneyahoo = mysql_fetch_row($requeteyahoo);
    $visityahoo=$ligneyahoo[1];
    }
else
    {
    $visityahoo = 0;
    }

//request to have the number of Ask Jeeves/Teoma (Ask) visits
if($period>=10)
    {
    $sqlask = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler  FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."'
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'Ask Jeeves/Teoma'    
    GROUP BY crawler_name";
    }
else
    {
    $sqlask = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler  FROM crawlt_visits, crawlt_crawler
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  date >'".sql_quote($daterequest)."' 
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawler_name= 'Ask Jeeves/Teoma'    
    GROUP BY crawler_name";
    }

$requeteask = mysql_query($sqlask, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requeteask);
if($nbrresult>=1)
    {
    $ligneask = mysql_fetch_row($requeteask);
    $visitask=$ligneask[1];
    }
else
    {
    $visitask = 0;
    }




//cleaning of the crawlt_visits_human table
include"include/cleaning-double-entry.php";

//request to have the visits send by Google
if($period>=10)
    {
    $sqlgoogle2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '1' ";  
    }
else
    {
    $sqlgoogle2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '1' ";
    }

$requetegoogle2 = mysql_query($sqlgoogle2, $connexion) or die("MySQL query error");
$visitsendgoogle=mysql_num_rows($requetegoogle2);



//request to have the visits send by MSN
if($period>=10)
    {
    $sqlmsn2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '3' ";  
    }
else
    {
    $sqlmsn2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '3' ";
    }

$requetemsn2 = mysql_query($sqlmsn2, $connexion) or die("MySQL query error");
$visitsendmsn=mysql_num_rows($requetemsn2);

//request to have the visits send by Yahoo
if($period>=10)
    {
    $sqlyahoo2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '2' ";  
    }
else
    {
    $sqlyahoo2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '2' ";
    }

$requeteyahoo2 = mysql_query($sqlyahoo2, $connexion) or die("MySQL query error");
$visitsendyahoo=mysql_num_rows($requeteyahoo2);

//request to have the visits send by Ask
if($period>=10)
    {
    $sqlask2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '4' ";  
    }
else
    {
    $sqlask2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
    WHERE  date >'".sql_quote($daterequest)."' 
    AND crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_id_crawler= '4' ";
    }

$requeteask2 = mysql_query($sqlask2, $connexion) or die("MySQL query error");
$visitsendask=mysql_num_rows($requeteask2);

//display
echo"<div class=\"content\">\n";
echo crawltbackforward('index',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);
echo"</div>\n";

//backling and index page table
echo"<div class='tableaularge' align='center'>\n";
echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo"<tr onmouseover=\"javascript:montre();\">\n";
echo"<th class='tableau10' colspan=\"3\">\n";
echo"".$language['searchengine']."\n";
echo"</th></tr><tr>\n";
echo"<th class='tableau1' width=\"20%\" >\n";
echo"&nbsp;\n";
echo"</th>\n";
echo"<th class='tableau1'  width=\"40%\">\n";
echo"".$language['nbr_tot_link']."\n";
echo"</th>\n";	
echo"<th class='tableau2' width=\"40%\">\n";
echo"".$language['nbr_tot_pages_index']."\n";
echo"</th></tr>\n";
echo"<tr><td class='tableau3g' >&nbsp;&nbsp;&nbsp;<a href=\"http://msdn.microsoft.com/live/search/\">".$language['msn']."</a>\n";
if($period==0 && ($linkmsn==0 OR $pagemsn==0))
    {
    echo"<a href=\"./php/searchenginespositionrefresh.php?retry=msn&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";    
    }
else
    {
    echo"</td>\n";
    }
if($linkmsn==0)
    {
    echo"<td class='tableau3' >-</td>\n";
    }
else
    {	
    echo"<td class='tableau3'>".$linkmsn."</td>\n";
    }
if($pagemsn==0)
    {
    echo"<td class='tableau5'>-</td></tr>\n";
    }
else
    {    
    echo"<td class='tableau5'>".$pagemsn."</td></tr>\n";
    }
echo"<tr><td class='tableau30g'>&nbsp;&nbsp;&nbsp;<a href=\"http://developer.yahoo.net/about\">".$language['yahoo']."</a>\n";
if($period==0 && ($linkyahoo==0 OR $pageyahoo==0))
    {
    echo"<a href=\"./php/searchenginespositionrefresh.php?retry=yahoo&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";    
    }
else
    {
    echo"</td>\n";
    }
if($linkyahoo==0)
    {
    echo"<td class='tableau30' >-</td>\n";
    }
else
    {	
    echo"<td class='tableau30'>".$linkyahoo."</td>\n";
    }
if($pageyahoo==0)
    {
    echo"<td class='tableau50'>-</td></tr>\n";
    }
else
    {    
    echo"<td class='tableau50'>".$pageyahoo."</td></tr>\n";
    }    
    
echo"</table><br>\n";


echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo"<tr onmouseover=\"javascript:montre();\">\n";
echo"<th class='tableau10' colspan=\"3\">\n";
echo"".$language['social-bookmark']."\n";
echo"</th></tr><tr>\n";
echo"<th class='tableau1' width=\"24%\">\n";
echo"&nbsp;\n";
echo"</th>\n";
echo"<th class='tableau1' width=\"20%\">\n";
echo"".$language['nbr_tot_bookmark']."\n";
echo"</th>\n";	
echo"<th class='tableau2'width=\"56%\">\n";
echo"".$language['tag']."\n";
echo"</th></tr>\n";
echo"<tr><td class='tableau3g' >&nbsp;&nbsp;&nbsp;<a href=\"http://del.icio.us/help/api/\">".$language['delicious']."</a>\n";
if($period==0 && $linkdelicious==0)
    {
    echo"<a href=\"./php/searchenginespositionrefresh.php?retry=delicious&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";    
    }
else
    {
    echo"</td>\n";
    }
if($linkdelicious==0)
    {
    echo"<td class='tableau3' >-</td>\n";
    }
else
    {	
    echo"<td class='tableau3'>".$linkdelicious."</td>\n";
    }
if($tagdelicious==' ')
    {
    echo"<td class='tableau5'>-</td></tr>\n";
    }
else
    {    
    echo"<td class='tableau5'>".$tagdelicious."</td></tr>\n";
    } 
echo"</table><br>\n";


echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo"<tr onmouseover=\"javascript:montre();\">\n";
echo"<th class='tableau10' colspan=\"3\">\n";
echo"".$language['nbr_visits']."\n";
echo"</th></tr><tr>\n";
echo"<th class='tableau1' >\n";
echo"".$language['searchengine']."\n";
echo"</th>\n";		
echo"<th class='tableau1' >\n";
echo"".$language['nbr_visits_crawler']."\n";
echo"</th>\n";
echo"<th class='tableau2' >\n";
echo"".$language['nbr_tot_visit_seo']."\n";
echo"</th></tr>\n";        
echo"<tr><td class='tableau3'>".$language['ask']."</td>\n";
echo"<td class='tableau3'>".numbdisp($visitask)."</td>\n";
echo"<td class='tableau5'>".numbdisp($visitsendask)."</td></tr>\n"; 
echo"<tr><td class='tableau30'>".$language['google']."</td>\n";
echo"<td class='tableau30'>".numbdisp($visitgoogle)."</td>\n";
echo"<td class='tableau50'>".numbdisp($visitsendgoogle)."</td></tr>\n";
echo"<tr><td class='tableau3'>".$language['msn']."</td>\n";
echo"<td class='tableau3'>".numbdisp($visitmsn)."</td>\n";
echo"<td class='tableau5'>".numbdisp($visitsendmsn)."</td></tr>\n";
echo"<tr><td class='tableau30'>".$language['yahoo']."</td>\n";
echo"<td class='tableau30'>".numbdisp($visityahoo)."</td>\n";
echo"<td class='tableau50'>".numbdisp($visitsendyahoo)."</td></tr>\n";                
    
echo"</table></div><br>\n";

if(($visitgoogle + $visitmsn +  $visityahoo + $visitask)>0)
    {
    //graph
    $values[$language['google']]=$visitgoogle;
    $values[$language['msn']]=$visitmsn; 
    $values[$language['yahoo']]=$visityahoo;
    $values[$language['ask']]=$visitask; 
    //prepare datas to be transfert to graph file
    $datatransferttograph=addslashes(urlencode(serialize($values)));
    //insert the values in the graph table  
    $piegraphname1="crawlervisits-".$cachename;
       
    //check if this graph exit already in the table     
    $sql = "SELECT name  FROM crawlt_graph
                WHERE name= '".sql_quote($piegraphname1)."'";
                
    
    $requete = mysql_query($sql, $connexion) or die("MySQL query error");
    $nbrresult=mysql_num_rows($requete);
    if($nbrresult >=1)
        {     
        $sql2 ="UPDATE crawlt_graph SET graph_values='".sql_quote($datatransferttograph)."'
                  WHERE name= '".sql_quote($piegraphname1)."'";
        }
    else
        {
        $sql2 ="INSERT INTO crawlt_graph (name,graph_values) VALUES ( '".sql_quote($piegraphname1)."','".sql_quote($datatransferttograph)."')";        
        }    
    $requete2 = mysql_query($sql2, $connexion) or die("MySQL query error");     
    }
    
if(($visitsendgoogle + $visitsendmsn +  $visitsendyahoo + $visitsendask)>0)
    {
    //graph
    $values2[$language['google']]=$visitsendgoogle;
    $values2[$language['msn']]=$visitsendmsn; 
    $values2[$language['yahoo']]=$visitsendyahoo;
    $values2[$language['ask']]=$visitsendask; 
    //prepare datas to be transfert to graph file
    $datatransferttograph=addslashes(urlencode(serialize($values2)));
    //insert the values in the graph table  
    $piegraphname="searchengine-".$cachename;
       
    //check if this graph exit already in the table     
    $sql = "SELECT name  FROM crawlt_graph
                WHERE name= '".sql_quote($piegraphname)."'";
                
    
    $requete = mysql_query($sql, $connexion) or die("MySQL query error");
    $nbrresult=mysql_num_rows($requete);
    if($nbrresult >=1)
        {     
        $sql2 ="UPDATE crawlt_graph SET graph_values='".sql_quote($datatransferttograph)."'
                  WHERE name= '".sql_quote($piegraphname)."'";
        }
    else
        {
        $sql2 ="INSERT INTO crawlt_graph (name,graph_values) VALUES ( '".sql_quote($piegraphname)."','".sql_quote($datatransferttograph)."')";        
        }    
    $requete2 = mysql_query($sql2, $connexion) or die("MySQL query error");     
    }

if(($visitgoogle + $visitmsn +  $visityahoo + $visitask)>0 && ($visitsendgoogle + $visitsendmsn +  $visitsendyahoo + $visitsendask)>0)
    {
    //graph
    echo"<div align=\"center\" width=\"100%\">\n";    
    echo"<img src=\"./graphs/crawler-graph.php?graphname=$piegraphname1&crawltlang=$crawltlang\" alt=\"graph\"  width=\"450\" heigth=\"175\" style=\"border:0\"/><img src=\"./graphs/crawler-graph.php?graphname=$piegraphname&crawltlang=$crawltlang\" alt=\"graph\"  width=\"450\" heigth=\"175\" style=\"border:0\"/>\n";
    echo"</div>\n"; 
    }
elseif(($visitgoogle + $visitmsn +  $visityahoo + $visitask)>0 && ($visitsendgoogle + $visitsendmsn +  $visitsendyahoo + $visitsendask)==0)
    {
    //graph
    echo"<div align=\"center\" width=\"100%\">\n";    
    echo"<img src=\"./graphs/crawler-graph.php?graphname=$piegraphname1&crawltlang=$crawltlang\" alt=\"graph\"  width=\"450\" heigth=\"175\" style=\"border:0\"/>\n";    
    echo"</div>\n"; 
    } 
elseif(($visitgoogle + $visitmsn +  $visityahoo + $visitask)==0 && ($visitsendgoogle + $visitsendmsn +  $visitsendyahoo + $visitsendask)>0)
    {
    //graph
    echo"<div align=\"center\" width=\"100%\">\n";    
    echo"<img src=\"./graphs/crawler-graph.php?graphname=$piegraphname&crawltlang=$crawltlang\" alt=\"graph\"  width=\"450\" heigth=\"175\" style=\"border:0\"/>\n";     
    echo"</div>\n"; 
    }  
if($period != 5)
    {
    //graph
    echo"<div class='graphvisits'>\n";    
    //mapgraph
    $typegraph='link';
    include"include/mapgraph2.php";
    echo"<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&crawltlang=$crawltlang&period=$period&graphname=$graphname\" USEMAP=\"#seolink\" border=\"0\" alt=\"graph\"  width=\"100%\" heigth=\"100%\"/>\n";
    echo"&nbsp;</div><br>\n";        
    echo"<div class='imprimgraph'>\n";       
    echo"&nbsp;<br><br><br><br><br><br><br><br><br><br><br><br><br><br></div>\n";     
    //graph
    echo"<div class='graphvisits'>\n";    
    //mapgraph
    $typegraph='page';
    include"include/mapgraph2.php";
    echo"<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&crawltlang=$crawltlang&period=$period&graphname=$graphname\" USEMAP=\"#seopage\" border=\"0\" alt=\"graph\"  width=\"100%\" heigth=\"100%\"/>\n";
    echo"&nbsp;</div><br>\n";  
    echo"<div class='imprimgraph'>\n";       
    echo"&nbsp;<br><br><br><br></div>\n"; 
    //graph
    echo"<div class='graphvisits'>\n";    
    //mapgraph
    $typegraph='bookmark';
    include"include/mapgraph2.php";
    echo"<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&crawltlang=$crawltlang&period=$period&graphname=$graphname\" USEMAP=\"#bookmark\" border=\"0\" alt=\"graph\"  width=\"100%\" heigth=\"100%\"/>\n";
    echo"&nbsp;</div><br>\n"; 
    echo"<div class='imprimgraph'>\n";       
    echo"&nbsp;<br><br><br><br></div>\n";  
     
    //graph
    echo"<div class='graphvisits'>\n";    
    //mapgraph
    $typegraph='entry';
    include"include/mapgraph2.php";
    echo"<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&crawltlang=$crawltlang&period=$period&graphname=$graphname\" USEMAP=\"#seoentry\" border=\"0\" alt=\"graph\" width=\"100%\" heigth=\"100%\"/>\n";
    echo"&nbsp;<br><br>\n";
    echo"&nbsp;</div><br>\n";  
    echo"<div class='imprimgraph'>\n";       
    echo"&nbsp;<br><br><br><br>\n";
    }
else
    {
    echo"<div>\n";
    }   
//mysql connexion close
mysql_close($connexion);
?>