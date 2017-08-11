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
// file: logochoice.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN') && !defined('IN_CRAWLT_INSTALL'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}


//database connection
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//website list query
if($_SESSION['rightsite']==0)
	{
	$sql = "SELECT id_site, name FROM crawlt_site";
	}
else
	{
	$siteright=$_SESSION['rightsite'];
	
	$sql = "SELECT id_site, name FROM crawlt_site	
	WHERE id_site = '".sql_quote($siteright)."'";
	}


//request to get the sites datas

$requete = mysql_query($sql, $connexion) or die("MySQL query error");

$nbrresult=mysql_num_rows($requete);

if($nbrresult>=1)
    {    
    while ($ligne = mysql_fetch_row($requete))                                                                              
        {
        $listsite[]=$ligne[0];
        $namesite[$ligne[0]]=$ligne[1];
        }    
    }

   

    
echo"<h1>".$language['site_name2']."</h1>\n";    
echo"<div class=\"form3\">\n";
echo"<form action=\"index.php\" method=\"POST\" >\n";
echo"<table>\n";
echo"<tr><td>\n";
$sitechoice=0;
foreach($listsite as $siteid)
    { 
    if($sitechoice==0)
       {
       echo"<input type=\"radio\" name=\"site\" value=\"".$siteid."\" checked>".$namesite[$siteid]."<br><br>\n";
       }
   else
       {
        echo"<input type=\"radio\" name=\"site\" value=\"".$siteid."\">".$namesite[$siteid]."<br><br>\n";
        }
    $sitechoice=1;
    }
echo"</td></tr>\n";
echo"</table>\n";



//continue
if($navig==6)
	{
	$validform=3;
	}
elseif ($navig==15)
	{
	$validform=7;
	}


echo "<input type=\"hidden\" name ='navig' value=$navig>\n";
echo "<input type=\"hidden\" name ='validform' value=$validform>";
echo"<table>\n";	
echo"<tr>\n";
echo"<td>\n";
echo"<input name='ok' type='submit'  value='OK' size='40'>\n";
echo"</td>\n";
echo"</tr>\n";
echo"</table>\n";
echo"</form>\n";
echo"</div><br><br>";

?>