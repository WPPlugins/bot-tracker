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
// file: adminchangepassword.php
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}



if($validlogin==1)
	{
	
    if($password1 != $_SESSION['userpass'] OR $password2=='' OR $password3=='' OR $password2 != $password3)
		{        
		echo"<p>".$language['login_no_ok']."</p>";


		echo"<div class=\"form\">\n";
		echo"<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='30'>\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";		
		echo"<input name='ok' type='submit'  value=' ".$language['back_to_form']." ' size='20'>\n";
		echo"</form>\n";
		echo"</div>\n";
		}
	else
		{	
        //password treatment
        $pass=md5($password2);
        
        //database connection
        $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
        $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");        
				
        $sqllogin="UPDATE crawlt_login SET  crawlt_password='".sql_quote($pass)."'
        WHERE crawlt_user='".sql_quote($_SESSION['userlogin'])."'";
        
        $requetelogin = mysql_query($sqllogin, $connexion) or die("MySQL query error");

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
	}
else
	{
    //first arrival on the page		

    echo"<h1>".$language['change_password']."</h1>\n";                
    		
	echo"<div class=\"form\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"30\">";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";			
	echo"<table class=\"centrer\">\n";
	echo"<tr>\n";
	echo"<td>".$language['old_password']."</td>\n";
	echo"<td><input name='password1'  value='$password1' type='password' maxlength='20' size='50'/></td>\n";
	echo"</tr>\n";
	echo"<tr>\n";
	echo"<td>".$language['new_password']."</td>\n";
	echo"<td><input name='password2' value='$password2' type='password' size='50'/></td>\n";
	echo"</tr>\n";
	echo"<tr>\n";
	echo"<td colspan=\"2\">\n";
	echo"".	$language['valid_new_password']."\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"<tr>\n";
	echo"<td>".$language['new_password']."</td>\n";
	echo"<td><input name='password3' value='$password3' type='password' size='50'/></td>\n";
	echo"</tr>\n";	
	echo"<tr>\n";
	echo"<td colspan=\"2\">\n";
	echo"<br>\n";
	echo"<input name='ok' type='submit'  value=' OK ' size='20'>\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"</table>\n";
	echo"</form>\n";
	echo"</div>\n";	  
	}


?>