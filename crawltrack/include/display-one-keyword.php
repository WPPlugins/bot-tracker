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
// file: display-one-keyword.php
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$visitkeywordgoogle=array();
$visitkeywordYahoo=array();
$visitkeywordMSN=array();
$visitkeywordask=array();
$visitkeyword=array();
$visitkeyworddisplay=array();

$crawlencode=urlencode($crawler);
$cachename=$navig.$period.$site.$displayall.$firstdayweek.$crawlencode.$localday.$graphpos.$crawltlang;

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
//cleaning of the crawlt_visits_human table
include"include/cleaning-double-entry.php";
//limite to 
if($displayall=='no')
    {
    $limitquery='LIMIT '.$rowdisplay;
    }
else
    {
    $limitquery='';
    }
//date for the mysql query
if($period>=10)
    {
    $datetolookfor=" date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."'";    
    }
else
    {
    $datetolookfor=" date >'".sql_quote($daterequest)."'";
    }  
//query to have the number of entry per page
$sql = "SELECT  url_page, COUNT(DISTINCT id_visit) AS nbrvisits FROM crawlt_visits_human , crawlt_pages, crawlt_keyword
WHERE crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page 
AND crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
AND  $datetolookfor
AND crawlt_site_id_site='".sql_quote($site)."'
AND keyword='".sql_quote($crawler)."'   
GROUP BY url_page
ORDER BY nbrvisits DESC 
$limitquery";

$requete = mysql_query($sql, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requete);


if($nbrresult>=1)
    {
    while ($ligne = mysql_fetch_row($requete))                                                                              
        {
        $visitkeyword[$ligne[0]] = $ligne[1];
        } 
    }          
//query to have the keyword for Googlebot

$sqlgoogle = "SELECT  url_page, COUNT(DISTINCT id_visit) FROM crawlt_visits_human , crawlt_pages, crawlt_keyword
WHERE crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page 
AND crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
AND  $datetolookfor
AND crawlt_site_id_site='".sql_quote($site)."'
AND keyword='".sql_quote($crawler)."'
AND crawlt_id_crawler= '1'    
GROUP BY url_page";

$requetegoogle = mysql_query($sqlgoogle, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requetegoogle);


if($nbrresult>=1)
    {
    while ($ligne = mysql_fetch_row($requetegoogle))                                                                              
        {
        $visitkeywordgoogle[$ligne[0]] = $ligne[1];
        } 
    }


//query to have the keyword for Yahoo
$sqlYahoo = "SELECT  url_page, COUNT(DISTINCT id_visit) FROM crawlt_visits_human , crawlt_pages, crawlt_keyword
WHERE crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
AND crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword 
AND  $datetolookfor
AND crawlt_site_id_site='".sql_quote($site)."'
AND keyword='".sql_quote($crawler)."'
AND crawlt_id_crawler= '2'    
GROUP BY url_page";


$requeteYahoo = mysql_query($sqlYahoo, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requeteYahoo);

if($nbrresult>=1)
    {
    while ($ligne = mysql_fetch_row($requeteYahoo))                                                                              
        {
        $visitkeywordYahoo[$ligne[0]] = $ligne[1];
        } 
    }


//query to have the keyword for MSN
$sqlMSN = "SELECT  url_page, COUNT(DISTINCT id_visit) FROM crawlt_visits_human , crawlt_pages, crawlt_keyword
WHERE crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
AND crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword 
AND  $datetolookfor 
AND crawlt_site_id_site='".sql_quote($site)."'
AND keyword='".sql_quote($crawler)."'
AND crawlt_id_crawler= '3'    
GROUP BY url_page";


$requeteMSN = mysql_query($sqlMSN, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requeteMSN);

if($nbrresult>=1)
    {
    while ($ligne = mysql_fetch_row($requeteMSN))                                                                              
        {
        $visitkeywordMSN[$ligne[0]] = $ligne[1];
        } 
    }

//query to have the keyword for Ask
$sqlask = "SELECT  url_page, COUNT(DISTINCT id_visit) FROM crawlt_visits_human , crawlt_pages, crawlt_keyword
WHERE crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
AND crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword 
AND  $datetolookfor 
AND crawlt_site_id_site='".sql_quote($site)."'
AND keyword='".sql_quote($crawler)."'
AND crawlt_id_crawler= '4'    
GROUP BY url_page";

$requeteask = mysql_query($sqlask, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requeteask);

if($nbrresult>=1)
    {
    while ($ligne = mysql_fetch_row($requeteask))                                                                              
        {
        $visitkeywordask[$ligne[0]] = $ligne[1];
        }  
    }

//mysql connexion close
mysql_close($connexion);
    
//display
$crawlerdisplay = stripslashes(crawltcuturl($crawler,'55'));
echo"<div class=\"content\">\n";
echo crawltbackforward($crawlerdisplay,$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);
echo"</div>\n";
//to close the menu rollover
echo"<div width='100%' height:'5px' onmouseover=\"javascript:montre();\">&nbsp;</div>\n";
echo"<div class='tableaularge' align='center'>\n";
if(count($visitkeyword)>=1)
    {
    echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
    echo"<tr><th class='tableau1' colspan=\"2\" rowspan=\"2\">\n";
    echo"".$language['entry-page']."\n";
    echo"</th>\n";		
    echo"<th class='tableau2'colspan=\"4\">\n";
    echo"".$language['nbr_tot_visit_seo']."\n";
    echo"</th></tr>\n";  
    echo"<tr>\n";
    echo"<th class='tableau20'>\n";
    echo"".$language['ask']."\n";
    echo"</th>\n";
    echo"<th class='tableau20'>\n";
    echo"".$language['google']."\n";
    echo"</th>\n";
    echo"<th class='tableau20'>\n";
    echo"".$language['msn']."\n";
    echo"</th>\n";
    echo"<th class='tableau200'>\n";
    echo"".$language['yahoo']."\n";
    echo"</th>\n";
    echo"</tr>\n";
    //counter for alternate color lane
    $comptligne=2;
    //counter to limite number of datas displayed
    $comptdata=0;

    
    foreach ($visitkeyword as $keyword => $value)
        {
        $crawlencode=urlencode($keyword);
        $keyworddisplay = stripslashes(crawltcutkeyword($keyword,60));
        if(isset($visitkeywordask[$keyword]))
            {
            $visitask=$visitkeywordask[$keyword];
            }
        else
            {
            $visitask='-';
            }        
        if(isset($visitkeywordgoogle[$keyword]))
            {
            $visitgoogle=$visitkeywordgoogle[$keyword];
            }
        else
            {
            $visitgoogle='-';
            }
        if(isset($visitkeywordMSN[$keyword]))
            {
            $visitmsn=$visitkeywordMSN[$keyword];
            }
        else
            {
            $visitmsn='-';
            }                     
        if(isset($visitkeywordYahoo[$keyword]))
            {
            $visityahoo=$visitkeywordYahoo[$keyword];
            }
        else
            {
            $visityahoo='-';
            }  
            
        //to avoid problem if the url is enter in the database with http://
        if (!eregi("^http://", $urlsite[$site]))
            {
            $urlpage="http://".$urlsite[$site].$keyword;
            }
        else
            {
            $urlpage= $urlsite[$site].$keyword;
            }              
        
        if ($comptligne%2 ==0)
            {
            echo"<tr><td class='tableau3g'";
            if($keywordcut==1)
                {
                echo"onmouseover=\"javascript:montre('smenu".($comptligne+9)."');\"   onmouseout=\"javascript:montre();\"";
                }
            echo">&nbsp;&nbsp;<a href='index.php?navig=14&amp;period=".$period."&amp;site=".$site."&amp;crawler=".$crawlencode."&amp;graphpos=".$graphpos."' >".$keyworddisplay."</a></td>\n";
            echo"<td class='tableau6' width=\"8%\">\n"; 
            echo"<a href='".$urlpage."'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
            echo"</td> \n";                
            echo"<td class='tableau3' width=\"11%\">".numbdisp($visitask)."</td>\n";
            echo"<td class='tableau3' width=\"11%\">".numbdisp($visitgoogle)."</td>\n";
            echo"<td class='tableau3' width=\"11%\">".numbdisp($visitmsn)."</td>\n";
            echo"<td class='tableau5' width=\"11%\">".numbdisp($visityahoo)."</td></tr>\n";
            }
         else
            {
            echo"<tr><td class='tableau30g'";
            if($keywordcut==1)
                {
                echo"onmouseover=\"javascript:montre('smenu".($comptligne+9)."');\"   onmouseout=\"javascript:montre();\"";
                }
            echo">&nbsp;&nbsp;<a href='index.php?navig=14&amp;period=".$period."&amp;site=".$site."&amp;crawler=".$crawlencode."&amp;graphpos=".$graphpos."'  >".$keyworddisplay."</a></td>\n";
            echo"<td class='tableau60' width=\"8%\">\n"; 
            echo"<a href='".$urlpage."'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
            echo"</td> \n";                 
            echo"<td class='tableau30' width=\"11%\">".numbdisp($visitask)."</td>\n";
            echo"<td class='tableau30' width=\"11%\">".numbdisp($visitgoogle)."</td>\n";
            echo"<td class='tableau30' width=\"11%\">".numbdisp($visitmsn)."</td>\n";
            echo"<td class='tableau50' width=\"11%\">".numbdisp($visityahoo)."</td></tr>\n";               
            }  
            if($keywordcut==1)
                {
                if($period==0 OR $period>=1000)
                    {
                    $step=25;
                    }
                else
                    {
                    $step=30;
                    }                                   
                echo"<div id=\"smenu".($comptligne+9)."\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:".(270+(($comptligne-3)*$step))."px; left:20px; background:#fff;\">\n";      
                echo"&nbsp;".stripslashes(htmlentities(utf8_decode(urldecode($keyword))))."&nbsp;\n";
                echo"</div>\n";
                }        

                  
         $comptligne++;
            if($displayall=='no')
                {
                $comptdata++;
                }
             
        }
    echo"</table>\n"; 
      
                  
    if(count($visitkeyword)>=$rowdisplay && $displayall=='no')
        {
        echo"<h2><span class=\"smalltext\">\n";
        printf($language['100_lines'],$rowdisplay);
        echo"<br>\n";
         $crawlencode = urlencode($crawler);
        echo"<a href=\"index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&order=$order&displayall=yes&graphpos=$graphpos\">".$language['show_all']."</a></span></h2>";
        }   
    echo"<br>\n";                   
    }
else
    {
	echo"<h1>".$language['no_visit']."</h1>\n";
    echo"<br>\n"; 
    }	
?>