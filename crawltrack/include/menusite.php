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
// file: menusite.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$listsite=array();
$urlsite=array();
$listidsite=array();
$nbrpagestotal=array();


if($_SESSION['rightsite']==0)
	{
		
	//mysql requete	
	$sqlsite = "SELECT * FROM crawlt_site";	
	$requetesite = mysql_query($sqlsite, $connexion) or die("MySQL query error");
	
	$nbrresult=mysql_num_rows($requetesite);
		
	if($nbrresult>=1)
			{	
	
		while ($ligne = mysql_fetch_object($requetesite))                                                                              
			{
			$sitename=$ligne->name;
			$siteurl=$ligne->url; 
			$siteid=$ligne->id_site;
			$listsite[]=$sitename;
			$urlsite[$siteid]=$siteurl;
			$listidsite[]=$siteid;
			}

		//preparation of site list display
		$nbrsite=sizeof($listsite);
		$nbrsiteaf=0;


		//display
		echo"<div class=\"menusite\" align=\"centrer\">\n";

		echo"<div width=\"444px\" z-index:0>\n";
		echo"<form action=\"index.php\" method=\"POST\" z-index:0>\n";

		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='search' value=\"$search\">\n";		
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo"<select onchange=\"form.submit()\" size=\"1\" name=\"site\"  style=\" font-size:13px; font-weight:bold; color: #003399;
		font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
		do
			{			
            //calculation of the number of pages of the site
            $site2=$listidsite[$nbrsiteaf];
            $sqlstats = "SELECT COUNT(DISTINCT crawlt_pages_id_page) FROM crawlt_visits, crawlt_crawler, crawlt_pages
            WHERE  crawlt_site_id_site='".sql_quote($site2)."'
            AND crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
            AND  crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page
            AND crawlt_visits.crawlt_crawler_id_crawler !='0'";
            			
            $requetestats = mysql_query($sqlstats, $connexion);	
            $ligne = mysql_fetch_row($requetestats);
            $nbrpagestotal[$site2]=$ligne[0];	
			
			if($listidsite[$nbrsiteaf]==$site)
				{
				echo"<option value=\"$listidsite[$nbrsiteaf]\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$listsite[$nbrsiteaf]."&nbsp;&bull;&nbsp;".$nbrpagestotal[$site2]." &nbsp;".$language['page']."</option>\n";
				}
			else
				{
				echo"<option value=\"$listidsite[$nbrsiteaf]\" style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$listsite[$nbrsiteaf]."&nbsp;&bull;&nbsp;".$nbrpagestotal[$site2]." &nbsp;".$language['page']."</option>\n";
				}		
			$nbrsiteaf++;
			}
	
		while($nbrsiteaf<$nbrsite);

		echo"</select></form></div></div>\n";
		}	
	}
else
	{

	//mysql requete	
	$site=$_SESSION['rightsite'];	
				
	$sqlsite = "SELECT * FROM crawlt_site
	WHERE id_site='".sql_quote($site)."'";

	
	$requetesite = mysql_query($sqlsite, $connexion) or die("MySQL query error");
	
	$nbrresult=mysql_num_rows($requetesite);
		
	if($nbrresult>=1)
		{		
	
		while ($ligne = mysql_fetch_object($requetesite))                                                                              
			{
			$sitename=$ligne->name; 
			}
			
			
		$sqlstats = "SELECT COUNT(DISTINCT crawlt_pages_id_page) FROM crawlt_visits 
        WHERE  crawlt_site_id_site='".sql_quote($site)."'";			
		$requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");	
		$ligne = mysql_fetch_row($requetestats);
        $nbrpagestotal[$site]=$ligne[0];			
			
			

		//display
		echo"<div class=\"menusite\" >\n";

		echo"<div width=\"244px\" align=\"centrer\">\n";
		echo"<form action=\"index.php\" method=\"POST\" >\n";

		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='search' value=\"$search\">\n";		
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo"<select size=\"1\" name=\"site\"  style=\" font-size:13px; font-weight:bold; color: #003399;
		font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; width:244px;\">\n";
		echo"<option value=\"$site\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$sitename."&nbsp;&bull;&nbsp;".$nbrpagestotal[$site]." &nbsp;".$language['page']."</option>\n";


		echo"</select></form></div>\n";
		
		echo"</div>\n";
		
		}

	}
		
?>