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
// file: archive.php
//----------------------------------------------------------------------



if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$listid=array();
$month=array();
$visit=array();
$page=array();
$topvisit=array();
$toppage=array();


//database connection
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
//include menu 
include"include/menumain.php";
include"include/menusite.php";


echo"<div class=\"content\">\n";
echo"<h1>".$language['archive']."</h1><br>\n";


//data request
$sqlarchive="SELECT mois, nbr_visits, pages_view, top_visits_1,top_visits_2,top_visits_3,top_pages_view_1,top_pages_view_2,top_pages_view_3 FROM crawlt_archive";

$requetearchive = mysql_query($sqlarchive, $connexion) or die("MySQL query error");
	
$nbrresult=mysql_num_rows($requetearchive);
if($nbrresult>=1)
	{	
	while ($ligne = mysql_fetch_row($requetearchive))                                                                              
		{
		$sitetodisplay = explode('-',$ligne[0]);
		if($sitetodisplay[1]==$site)
            {
            $dateexplode = explode('/',$sitetodisplay[0]);
            $datelist[]=mktime(0,0,0,$dateexplode[0],1,$dateexplode[1]);
            $month[]=$sitetodisplay[0];
            $visit[]=$ligne[1];
            $page[]=$ligne[2];
            $topvisit[]=$ligne[3]."<br>&nbsp;&nbsp;".$ligne[4]."<br>&nbsp;&nbsp;".$ligne[5];
            $toppage[]=$ligne[6]."<br>&nbsp;&nbsp;".$ligne[7]."<br>&nbsp;&nbsp;".$ligne[8];
            }
        }

		echo"<div width='100%' align='center'>\n";	
		echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
		echo"<tr><th class='tableau11'>\n";
		echo"".$language['month2']."\n";
		echo"</th>\n";		
		echo"<th class='tableau11'>\n";
		echo"".$language['nbr_tot_visits']."\n";
		echo"</th>\n";
		echo"<th class='tableau11'>\n";
		echo"".$language['nbr_tot_pages']."\n";
		echo"</th>\n";
		echo"<th class='tableau11'>\n";
		echo"".$language['top_visits']."\n";
		echo"</th>\n";
		echo"<th class='tableau22'>\n";
		echo"".$language['top_pages']."\n";
		echo"</th></tr>\n";


		//counter for alternate color lane
		$comptligne=2;
        arsort($datelist);
		foreach ($datelist as $id =>$value)
			{
				if ($comptligne%2 ==0)
					{            
					echo"<tr><td class='tableau33'>&nbsp;".$month[$id]."&nbsp;</td>\n";
					echo"<td class='tableau33'>".$visit[$id]."</td>\n";
					echo"<td class='tableau33'>".$page[$id]."</td> \n";
					echo"<td class='tableau33g'>&nbsp;&nbsp;".$topvisit[$id]."</td> \n";
					echo"<td class='tableau55g'>&nbsp;&nbsp;".$toppage[$id]."</td></tr>\n";
					}
                else
					{            
					echo"<tr><td class='tableau330'>&nbsp;".$month[$id]."&nbsp;</td>\n";
					echo"<td class='tableau330'>".$visit[$id]."</td>\n";
					echo"<td class='tableau330'>".$page[$id]."</td> \n";
					echo"<td class='tableau330g'>&nbsp;&nbsp;".$topvisit[$id]."</td> \n";
					echo"<td class='tableau550g'>&nbsp;&nbsp;".$toppage[$id]."</td></tr>\n";
					}   
			$comptligne++;		             
            }
	
		echo"</table></div><br>\n";
    }
else
    {    
    echo"<h1>".$language['no-archive']."</h1><br>\n";
    }

?>