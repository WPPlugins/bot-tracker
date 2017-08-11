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
// file: adminuser.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

//valid form

if($validlogin==1)
	{
    if($login=='' OR $password2=='' OR $password3=='' OR $password2 != $password3)
		{        
		echo"<br><br><p>".$language['login_no_ok']."</p>";


		echo"<div class=\"form\">\n";
		echo"<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='6'>\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";
		echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";					
		echo "<input type=\"hidden\" name ='login' value='$login'>\n";
		echo "<input type=\"hidden\" name ='password2' value='$password2'>\n";
		echo "<input type=\"hidden\" name ='password3' value='$password3'>\n";
		echo "<input type=\"hidden\" name ='site' value='$site'>\n";		
		echo"<input name='ok' type='submit'  value=' ".$language['back_to_form']." ' size='20'>\n";
		echo"</form>\n";
		echo"</div>\n";
		}
	else
		{		
		//database connection	
        $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
        $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
		//check if login already exist
		
		$sqlexist = "SELECT * FROM crawlt_login
		WHERE crawlt_user='".sql_quote($login)."'";

		$requeteexist = mysql_query($sqlexist, $connexion) or die("MySQL query error");
		
		$nbrresult=mysql_num_rows($requeteexist);
		
		if($nbrresult>=1)
			{
			//login already exist		
			echo"<br><br><h1>".$language['exist_login']."</h1>";


			echo"<div class=\"form\">\n";
			echo"<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='validform' value='6'>\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";
			echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";					
			echo "<input type=\"hidden\" name ='login' value='$login'>\n";
			echo "<input type=\"hidden\" name ='password2' value='$password2'>\n";
			echo "<input type=\"hidden\" name ='password3' value='$password3'>\n";
			echo "<input type=\"hidden\" name ='site' value='$site'>\n";		
			echo"<input name='ok' type='submit'  value=' ".$language['back_to_form']." ' size='20'>\n";
			echo"</form>\n";
			echo"</div>\n";
			}
		else
			{
            //add the login in the database
		
			//password treatment
			$pass=md5($password2);

			$admin=0;
			$website=0;

		
			$sqllogin="INSERT INTO crawlt_login (crawlt_user,crawlt_password,admin,site) VALUES ('".sql_quote($login)."','".sql_quote($pass)."','".sql_quote($admin)."','".sql_quote($website)."')";
			$requetelogin = mysql_query($sqllogin, $connexion) or die("MySQL query error");

			//check is requete is successfull

			if($requetelogin==1)
				{
					echo"<br><br><h2>".$language['user_creation']."</h2>\n";
		
			
				echo"<p>".$language['login_ok']."</p>\n";				
				
			
				echo"<div class=\"form\">\n";
				echo"<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='navig' value='6'>\n";			
				echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
				echo"</form>\n";
				echo"</div>\n";					
							
				}
			else
				{
				echo"<br><br><h2>".$language['user_creation']."</h2>\n";			
				echo"<p>".$language['login_no_ok2']."</p>";

			
				echo"<div class=\"form\">\n";
				echo"<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='validform' value='6'>\n";
				echo "<input type=\"hidden\" name ='navig' value='6'>\n";
				echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";
				echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";		
				echo "<input type=\"hidden\" name ='login' value='$login'>\n";
				echo "<input type=\"hidden\" name ='password2' value='$password2'>\n";
				echo "<input type=\"hidden\" name ='password3' value='$password3'>\n";
				echo"<input name='ok' type='submit'  value=' ".$language['retry']." ' size='20'>\n";
				echo"</form>\n";
				echo"</div>\n";
				}

			}

		}
		
	}

//form

else
	{
    echo"<br><br><h2>".$language['user_creation']."</h2>\n";
    echo"<p>".$language['user_setup']."</p>\n";
    echo"<p>".$language['login_user_what']."</p>\n";
		
	echo"</div>\n";

	//data collect form

	echo"<div class=\"form\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"6\">";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";			
	echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";	
	echo"<table class=\"centrer\">\n";
	echo"<tr>\n";
	echo"<td>".$language['login']."</td>\n";
	echo"<td><input name='login'  value='$login' type='text' maxlength='20' size='50'/></td>\n";
	echo"</tr>\n";
	echo"<tr>\n";
	echo"<td>".$language['password']."</td>\n";
	echo"<td><input name='password2' value='$password2' type='password' size='50'/></td>\n";
	echo"</tr>\n";
	echo"<tr>\n";
	echo"<td colspan=\"2\">\n";
	echo"".	$language['valid_password']."\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"<tr>\n";
	echo"<td>".$language['password']."</td>\n";
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
	}


?>