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
// file: adminlang.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
if($validlogin==1)
	{
    //update the crawlt_config_table
    
    //database connection
    $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
    $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
    
    $sqlupdatemail ="UPDATE crawlt_config SET lang='".sql_quote($crawltnewlang)."'";
    
    $requeteupdatemail = mysql_query($sqlupdatemail, $connexion) or die("MySQL query error"); 
 
    echo"<br><br><p>".$language['update']."</p><br><br>";    
        
    //continue
    echo"<form action=\"index.php\" method=\"POST\" >\n";
    echo "<input type=\"hidden\" name ='navig' value='6'>\n";
    echo"<table class=\"centrer\">\n";	
    echo"<tr>\n";
    echo"<td colspan=\"2\">\n";
    echo"<input name='ok' type='submit'  value='OK ' size='20'>\n";
    echo"</td>\n";
    echo"</tr>\n";
    echo"</table>\n";
    echo"</form><br>\n"; 
	}
else
    {
   	//language choice
	echo"<h1>".$language['choose_language']."</h1><br><br>\n";
	echo"<div class=\"form\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
    echo "<input type=\"hidden\" name ='validform' value=25>\n";
    echo "<input type=\"hidden\" name ='validlogin' value=1>\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		
	if($crawltlang=='german' OR $crawltlang=='germaniso')
        {
        echo"<h2><input type=\"radio\" name=\"newlang\" value=\"german\" checked>".$language['german']."\n";
        }
    else
        {
        echo"<h2><input type=\"radio\" name=\"newlang\" value=\"german\" >".$language['german']."\n";
        }  	
	
	if($crawltlang=='english' OR $crawltlang=='englishiso')
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"english\" checked>".$language['english']."\n";
        }
    else
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"english\" >".$language['english']."\n";
        }
	if($crawltlang=='spanish' OR $crawltlang=='spanishiso')
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"spanish\" checked>".$language['spanish']."\n";
        }
    else
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"spanish\" >".$language['spanish']."\n";
        }        
	if($crawltlang=='french' OR $crawltlang=='frenchiso')
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"french\" checked>".$language['french']."\n";
        }
    else
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"french\" >".$language['french']."\n";
        } 
 	if($crawltlang=='dutch' OR $crawltlang=='dutchiso')
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"dutch\" checked>".$language['dutch']."\n";
        }
    else
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"dutch\" >".$language['dutch']."\n";
        }       
	if($crawltlang=='turkish' OR $crawltlang=='turkishiso')
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"turkish\" checked>".$language['turkish']."</h2>\n";
        }
    else
        {
        echo"<input type=\"radio\" name=\"newlang\" value=\"turkish\" >".$language['turkish']."</h2>\n";
        }             

	echo"<input name=\"ok\" type=\"submit\"  value=\"OK\" >\n";
	echo"</form>\n";
	echo"<br></div>\n";	    
    
    }


?>