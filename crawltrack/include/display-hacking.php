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
// file: display-hacking.php
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$listip=array();
$countrycode=array();
$nbrcountry=array();
$listcountry=array();
$totalscriptdisplay='';
$totalattackdisplay='';
$onlyarchive=0;
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


//mysql query-----------------------------------------------------------------------------------------------

//query to get the good site list
$sql = "SELECT host_site FROM crawlt_good_sites";
$requete = mysql_query($sql, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requete);
if($nbrresult>=1)
    {	
    while ($ligne = mysql_fetch_row($requete))
        {
        $listgoodsite[]=$ligne[0];               
        }
    }
else
  {
  $listgoodsite=array();
  }
//like query
$testcrawler= "AND url_page LIKE '%http:%'";
foreach($listgoodsite as $goodsite)
 {
 $testcrawler.=" AND url_page NOT LIKE '%$goodsite%'";
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
//date format
if($period == 0 OR $period >= 1000)
    {
    $datequery= "DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600)), '%H&nbsp;hr&nbsp;%i&nbsp;mn')";   
    }
else
    {
    $datequery= "DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600)), '<b>%d/%m/%Y</b><br>%H&nbsp;hr&nbsp;%i&nbsp;mn')";    
    }
$sqlstats = "SELECT crawlt_crawler_id_crawler,  crawlt_ip_used, date,  url_page, $datequery FROM crawlt_visits, crawlt_pages
WHERE  crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
$testcrawler
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
ORDER BY date";

        
$requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");
	
$nbrresult=mysql_num_rows($requetestats);

$sqlstats2 = "SELECT crawlt_crawler_id_crawler,  crawlt_ip_used, date,  url_page, $datequery FROM crawlt_visits, crawlt_pages
WHERE  (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20select\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20like\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20where\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20or\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
ORDER BY date";

        
$requetestats2 = mysql_query($sqlstats2, $connexion) or die("MySQL query error2");
	
$nbrresult2=mysql_num_rows($requetestats2);


$testip=0;
if($nbrresult>=1 OR $nbrresult2>=1)
	{
	//display---------------------------------------------------------------------------------------------------------
    echo"<div class=\"content\">\n";
    echo crawltbackforward('hacking2',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);  
    echo"</div>\n";


    //graph
    echo"<div align='center'onmouseover=\"javascript:montre();\">\n";        
    echo"<img src=\"./graphs/page-graph.php?nbrpageview=$nbrresult&amp;nbrpagestotal=$nbrresult2&amp;crawltlang=$crawltlang&amp;navig=$navig\" alt=\"graph\"  width=\"500px\" height=\"175px\" style=\"border:0\"/>\n";       
    echo"</div>\n"; 

    //summary table display
    echo"<div class='tableau' align='center' onmouseout=\"javascript:montre();\">\n";	
    echo"<table   cellpadding='0px' cellspacing='0' width='700px'>\n";
    echo"<tr><th class='tableau1' width='50%'>\n";
    echo"".$language['hacking3']."\n";
    echo"</th>\n";   		
    echo"<th class='tableau2'>\n";
    echo"".$language['hacking4']."\n";
    echo"</th></tr>\n";
    echo"<tr><td class='tableau3'><a href=\"index.php?navig=18&amp;period=$period&amp;site=$site\">".numbdisp($nbrresult)."</a></td>\n";    	
    echo"<td class='tableau5'><a href=\"index.php?navig=19&amp;period=$period&amp;site=$site\">".numbdisp($nbrresult2)."</a></td></tr>\n";
 	
    echo"</table></div>\n";
    if($crawltblockattack==1)
      {
      echo"<h2>".$language['attack-blocked']."</h2>\n";
      }
    else
      {
      echo"<h2><span class=\"alert2\">".$language['attack-no-blocked']."</span></h2>\n";
      }
     if($period != 5)
        {
        //graph
        echo"<div class='graphvisits' >\n";    
        //mapgraph
        include"include/mapgraph.php";
        echo"<img src=\"./graphs/visit-graph.php?crawltlang=$crawltlang&period=$period&navig=$navig&graphname=$graphname\" USEMAP=\"#visit\" alt=\"graph\" width=\"700\" heigth=\"300\"  border=\"0\"/>\n";
        echo"</div>\n";
        echo"<div class='imprimgraph'>\n";       
        echo"&nbsp;<br><br><br><br><br><br><br><br></div>\n"; 
        }     
           
    echo"<div><br>\n"; 	
	}
else //case no visits
	{
    echo"<div class=\"content\">\n";
	echo crawltbackforward('hacking2',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);      
	echo"</div>\n";
    echo"<div class='tableaularge' align='center'>\n";		
	echo"<h1>".$language['no_hacking']."</h1>\n";
	echo"<br>\n";	
	}

	

?>