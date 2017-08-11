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
// file: adminipsuppress.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

if(isset($_GET['suppressip']))
	{	
	$suppressip = (int)$_GET['suppressip'];
	}
else
	{
	if(isset($_POST['suppressip']))
        {	
        $suppressip = (int)$_POST['suppressip'];
        }
	else
        {	
        $suppressip = 0;
        }
    }    

if(isset($_GET['suppressipok']))
	{	
	$suppressipok = (int)$_GET['suppressipok'];
	}
else
	{
	if(isset($_POST['suppressipok']))
        {	
        $suppressipok = (int)$_POST['suppressipok'];
        }
	else
        {	
        $suppressipok = 0;
        }
    } 
    
if(isset($_POST['originsuprip']))
    {	
    $originsuprip = (int)$_POST['originsuprip'];
    }
else
    {	
    $originsuprip = 0;
    }    
    
    
if($suppressip==1)	
	{
	
	if(isset($_GET['iptosuppress']))
		{	
		$iptosuppress = $_GET['iptosuppress'];
		}
	else
        {
        if(isset($_POST['iptosuppress']))
            {	
            $iptosuppress = $_POST['iptosuppress'];
            }
        else
            {	
            header("Location:../index.php");
            }
        } 
		

    //test to see if the IP address is correct
    $modele="^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$";
    $iptosuppress=strtolower($iptosuppress);
    if (ereg($modele, $iptosuppress))
        {
        $validaddress=1;
        }
    else    
        {
        $validaddress=0;
        }

		
	if($suppressipok==1  && $validaddress==1)
		{
		//ip suppression
				
		//database connection
        $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
        $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");


		//database query to suppress the visits coming from that ip
		
		$sqldelete="DELETE FROM crawlt_visits WHERE crawlt_ip_used= '".sql_quote($iptosuppress)."'";
		$requetedelete = mysql_query($sqldelete, $connexion) or die("MySQL query error");	
		
        //emptied the cache table
        $sqlcache = "TRUNCATE TABLE crawlt_cache";
        $requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");		
		
		if($requetedelete)
			{
			echo"<br><br><h1>".$language['ip_suppress_ok']."</h1>\n";
			
			echo"<div class=\"form\">\n";
			echo"<form action=\"index.php\" method=\"POST\" >\n";
            if ($originsuprip==1)
                {
                echo "<input type=\"hidden\" name ='navig' value='6'>\n";
                }
            else
                {
                echo "<input type=\"hidden\" name ='navig' value='8'>\n";
                } 
            echo "<input type=\"hidden\" name ='originsuprip' value=\"$originsuprip\">\n";                
            echo "<input type=\"hidden\" name ='period' value=\"$period\">";
            echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";		
			echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo"</form>\n";
			echo"</div>\n";	
			}
		else
			{
			echo"<br><br><h1>".$language['ip_suppress_no_ok']."</h1>\n";			
			
			echo"<div class=\"form\">\n";
			echo"<form action=\"index.php\" method=\"POST\" >\n";
            if ($originsuprip==1)
                {
                echo "<input type=\"hidden\" name ='navig' value='6'>\n";
                }
            else
                {
                echo "<input type=\"hidden\" name ='navig' value='8'>\n";
                } 
            echo "<input type=\"hidden\" name ='originsuprip' value=\"$originsuprip\">\n";                
            echo "<input type=\"hidden\" name ='period' value=\"$period\">";
            echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";			
			echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo"</form>\n";
			echo"</div>\n";			
			}
		
		}
	elseif ($suppressipok==1  && $validaddress==0)
        {
         echo"<p>".$language['ip_no_ok']."</p><br><br>\n";          
        }  
	else
		{
		//validation of suppression	
	
		//display	
		
		$iptosuppress	= stripslashes($iptosuppress);	
		$iptosuppressdisplay=htmlentities($iptosuppress);	

		echo"<br><br><h1>".$language['crawler_ip']."&nbsp;$iptosuppressdisplay</h1>\n";
		
		if($originsuprip==1)
            {
            echo"<br><p>".$language['ip_suppress_validation2']."</p>\n";
            }
        else
            {            
            echo"<br><p>".$language['ip_suppress_validation']."</p>\n";
            }
	
		echo"<div class=\"form\">\n";
		echo"<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"19\">";
		echo "<input type=\"hidden\" name ='suppressip' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='suppressipok' value=\"1\">\n";
        echo "<input type=\"hidden\" name ='originsuprip' value=\"$originsuprip\">\n";			
		echo "<input type=\"hidden\" name ='iptosuppress' value=\"$iptosuppress\">\n";
		echo"<table class=\"centrer\">\n";	
		echo"<tr>\n";
		echo"<td colspan=\"2\">\n";
		echo"<input name='ok' type='submit'  value=' ".$language['yes']." ' size='20'>\n";
		echo"</td>\n";
		echo"</tr>\n";
		echo"</table>\n";
		echo"</form>\n";
		echo"</div>";
	
		echo"<div class=\"form\">\n";
		echo"<form action=\"index.php\" method=\"POST\" >\n";
		if ($originsuprip==1)
            {
            echo "<input type=\"hidden\" name ='navig' value='6'>\n";
            }
        else
            {
            echo "<input type=\"hidden\" name ='navig' value='8'>\n";
            }         
		echo "<input type=\"hidden\" name ='period' value=\"$period\">";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
        echo "<input type=\"hidden\" name ='originsuprip' value=\"$originsuprip\">\n";		
		echo"<table class=\"centrer\">\n";	
		echo"<tr>\n";
		echo"<td colspan=\"2\">\n";
		echo"<input name='ok' type='submit'  value=' ".$language['no']." ' size='20'>\n";
		echo"</td>\n";
		echo"</tr>\n";
		echo"</table>\n";
		echo"</form>\n";
		echo"</div>";	
	
        echo"<br><p>".$language['ip_suppress_validation3']."\n";
        echo"<b>Deny from&nbsp;$iptosuppressdisplay</b></p><br>\n";	
	
	
		}	
	
	}
else
    {
    
 	echo"<br><br><h1>".$language['ip_suppress']."</h1>\n";   
    	//data collect form

	echo"<div class=\"form\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"19\">";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='suppressip' value='1'>\n";
	echo "<input type=\"hidden\" name ='originsuprip' value='1'>\n";	
	echo"<table class=\"centrer\">\n";
	echo"<tr>\n";
	echo"<td>".$language['crawler_ip']."</td>\n";
	echo"<td><input name='iptosuppress' value='' type=\"text\" maxlength=\"16\" size=\"50\"/></td>\n";
	echo"</tr>\n";
	echo"<tr><td>&nbsp;</td></tr>\n";
	echo"<tr><td>&nbsp;</td></tr>\n";			
	echo"<tr>\n";
	echo"<td colspan=\"2\">\n";
	echo"<br>\n";
	echo"<input name='ok' type='submit'  value=' OK ' size='20'>\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"</table>\n";
	echo"</form>\n";
 	echo"</div><br>\n";   
    
    
    }

?>