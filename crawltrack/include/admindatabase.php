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
// file: admindatabase.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}


$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword);
$selection = mysql_select_db($crawltdb);

$requete = mysql_query("show table status",$connexion);


echo "<h1>".$language['database_size']."</h1>\n";

//summary table display
echo"<div class='tableau' align='center' onmouseout=\"javascript:montre();\">\n";	
echo"<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
echo"<tr><th class='tableau1' >\n";
echo"".$language['table_name']."\n";
echo"</th>\n";		
echo"<th class='tableau1'>\n";
echo"".$language['nbr_of_data']."\n";
echo"</th>\n";
echo"<th class='tableau2'>\n";
echo"".$language['table_size']."\n";
echo"</th></tr>\n";

//counter for alternate color lane
$comptligne=2;

$tablesize = 1024;
$databasesize = 0;

while($result = mysql_fetch_assoc($requete))
    {
    if ($comptligne%2 ==0)
        {
        echo"<tr><td class='tableau3'>".$result['Name']."</td>\n";	
        echo"<td class='tableau3'>".numbdisp($result['Rows'])."</td>\n";
        $tablesize=($tablesize+$result['Data_length'])/1024;
        $databasesize+=$tablesize;        
        echo"<td class='tableau5'>".numbdisp(round($tablesize))." KB</td></tr>\n";
        }
    else
        {
        echo"<tr><td class='tableau30'>".$result['Name']."</td>\n";	
        echo"<td class='tableau30'>".numbdisp($result['Rows'])."</td>\n";
        $tablesize=($tablesize+$result['Data_length'])/1024;
        $databasesize+=$tablesize;        
        echo"<td class='tableau50'>".numbdisp(round($tablesize))." KB</td></tr>\n";
        }        
     $comptligne++;
     }   
       
    if ($comptligne%2 ==0)
        {       
         echo"<tr><td class='tableau3d' colspan='2'><b>".$language['total']."</b></td>\n";	
         echo"<td class='tableau5'><b>".numbdisp(round($databasesize))." KB</b></td></tr>\n";
         }
    else
        {       
         echo"<tr><td class='tableau30d' colspan='2'><b>".$language['total']."</b></td>\n";	
         echo"<td class='tableau50'><b>".numbdisp(round($databasesize))." KB</b></td></tr>\n";
         }         
         
        echo"</table></div><br><br>\n";
            


?>