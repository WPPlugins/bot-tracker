<?php
//----------------------------------------------------------------------
//  CrawlTrack 2.2.1
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.fr
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: adminarchivedays.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

if(isset($_POST['suppressdata']))
	{	
	$suppressdata = (int)$_POST['suppressdata'];
	}
else
	{
	$suppressdata = 0;
	}

if(isset($_POST['suppressdataok']))
	{	
	$suppressdataok = (int)$_POST['suppressdataok'];
	}
else
	{
	$suppressdataok = 0;
	}

if($suppressdata==1)
	
	{
	
	if(isset($_POST['datatosuppress']))
		{	
		$datatosuppress = (int)$_POST['datatosuppress'];
		}
	else
		{
		header("Location:../index.php");
		exit();		
		}
	
	
		
	if($suppressdataok==1)
		{
		//data suppression
				
		//database connection
        $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
        $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
    
        //period calculation
        
        $today = date("Y-m-d");
        $today2 = explode('-', $today);
        $yeartoday = $today2[0];
        $monthtoday = $today2[1];
        $daytoday = $today2[2];		



		if ($datatosuppress==1)	
			{
			$ts =  mktime(0,0,0,$monthtoday, $daytoday, $yeartoday) - 31536000;
			}
		elseif ($datatosuppress==2)			
			{
			$ts =  mktime(0,0,0,$monthtoday, $daytoday, $yeartoday) - 15768000;
			}
		elseif ($datatosuppress==3)	
			{
			$ts =  mktime(0,0,0,$monthtoday, $daytoday, $yeartoday) - 2628000;
			}
        else
            {
            echo"<h1>Hacking attempt !!!!</h1>";
            exit();
            }			

		$datetosuppress = date("Y-m-d",$ts);
        //treatment of $datetosuppress to have complete month	
        $suppress = explode('-', $datetosuppress);
        $yearsuppress = $suppress[0];
        $monthsuppress = $suppress[1];
        
        $datetosuppress=$yearsuppress."-".$monthsuppress."-01";	

        //archive creation
        //query to get the number of visits, page view and crawler per day/month/year-site 
          
             $sqlstats = "SELECT CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%d/%m/%Y'),'-',crawlt_site_id_site) ,COUNT(DISTINCT id_visit),COUNT(DISTINCT crawlt_pages_id_page),COUNT(DISTINCT crawler_name) FROM crawlt_visits, crawlt_crawler
             WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
             AND date < '".sql_quote($datetosuppress)."' 
             GROUP BY CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%d/%m/%Y'),'-',crawlt_site_id_site)"; 
 
                       
            $requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");            
            $nbrresult=mysql_num_rows($requetestats);
            
            if($nbrresult>=1)
                {       
                while ($ligne = mysql_fetch_row($requetestats))                                                                              
                    {        
                    $nbvisits[$ligne[0]]=$ligne[1];
                    $nbrpagesview[$ligne[0]]=$ligne[2];
                    $nbrcrawlers[$ligne[0]]=$ligne[3];                    
                    $listmonth[$ligne[0]]=$ligne[0];      
                    }
                            
           
           
            

        
                
               

        

            //preparation of the request to add the datas in the archive table
            foreach ($listmonth as $month)
                {
                echo $month."nbr visites=".$nbvisits[$month]."   nbr pages=".$nbrpagesview[$month]."   nbr  robots=".$nbrcrawlers."<br>";
              /*
 
                //insertion request
                $sqlarchive="INSERT INTO crawlt_archive_day (mois, nbr_visits, pages_view, top_visits_1,top_visits_2,top_visits_3,top_pages_view_1,top_pages_view_2,top_pages_view_3) VALUES ('".sql_quote($month)."', '".sql_quote($nbrvisits)."', '".sql_quote($nbrpages)."', '".sql_quote($tv1)."', '".sql_quote($tv2)."', '".sql_quote($tv3)."', '".sql_quote($tpv1)."', '".sql_quote($tpv2)."', '".sql_quote($tpv3)."')";
                $requetearchive = mysql_query($sqlarchive, $connexion) or die("MySQL query error");            
               */ 
                }

    
            if($requetearchive)
                {
                /*
                //database query to suppress the data		
                $sqldelete="DELETE FROM crawlt_visits WHERE date < '".sql_quote($datetosuppress)."'";
                $requetedelete = mysql_query($sqldelete, $connexion) or die("MySQL query error");	
                
                //database query to optimize the table
                $sqloptimize = "OPTIMIZE TABLE crawlt_visits";
                $requeteoptimize = mysql_query($sqloptimize, $connexion) or die("MySQL query error");
                */
                }
    
            //emptied the cache table
			$sqlcache = "TRUNCATE TABLE crawlt_cache";
			$requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");   

            if($requetedelete)
                {
                echo"<br><br><h1>".$language['data_suppress_ok']."</h1>\n";
                
                echo"<div class=\"form\">\n";
                echo"<form action=\"index.php\" method=\"POST\" >\n";
                echo "<input type=\"hidden\" name ='navig' value='6'>\n";			
                echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
                echo"</form>\n";
                echo"</div>\n";	
                }
            else
                {
                echo"<br><br><h1>".$language['data_suppress_no_ok']."</h1>\n";			
                
                echo"<div class=\"form\">\n";
                echo"<form action=\"index.php\" method=\"POST\" >\n";
                echo "<input type=\"hidden\" name ='navig' value='6'>\n";			
                echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
                echo"</form>\n";
                echo"</div>\n";			
                }
            }
         else
            {
            echo"<h1>".$language['no_data_to_suppress']."</h1>";
            }

		}
	else
		{
		//validation of suppression	
	
		//display	
		
		if ($datatosuppress==1)	
			{
			$datatosuppressdisplay=$language['one_year_data'];
			}
		elseif ($datatosuppress==2)			
			{
			$datatosuppressdisplay=$language['six_months_data'];
			}
		elseif ($datatosuppress==3)	
			{
			$datatosuppressdisplay=$language['one_month_data'];
			}				
        else
            {
            echo"<h1>Hacking attempt !!!!</h1>";
            exit();
            }			

		echo"<br><br><h1>".$language['data_suppress_validation']."$datatosuppressdisplay &nbsp;?</h1>\n";
	
		echo"<div class=\"form\">\n";
		echo"<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"17\">";
		echo "<input type=\"hidden\" name ='suppressdata' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='suppressdataok' value=\"1\">\n";	
		echo "<input type=\"hidden\" name ='datatosuppress' value=\"$datatosuppress\">\n";
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
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='suppressdata' value=\"0\">\n";
		echo "<input type=\"hidden\" name ='suppressdataok' value=\"0\">\n";	
		echo"<table class=\"centrer\">\n";	
		echo"<tr>\n";
		echo"<td colspan=\"2\">\n";
		echo"<input name='ok' type='submit'  value=' ".$language['no']." ' size='20'>\n";
		echo"</td>\n";
		echo"</tr>\n";
		echo"</table>\n";
		echo"</form>\n";
		echo"</div>";	
	
		}	
	
	}
else
	{
	
	echo"<br><br><h1>".$language['data_suppress']."</h1>\n";	

    $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
    $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");


	$sql = "SELECT * FROM crawlt_visits ORDER BY date ASC";

	$requete = mysql_query($sql, $connexion) or die("MySQL query error");
	
	$nbrresult=mysql_num_rows($requete);
	if($nbrresult>=1)
			{
			$ligne = mysql_fetch_object($requete);

			$date=$ligne->date;
			$date2 = explode('-', $date);
			$yeardate = $date2[0];
			$monthdate = $date2[1];
			$daydate = $date2[2];	
			$daydate2 = explode(' ',$daydate);
	
	
			if ($crawltlang=='english')
				{
				echo"<h2>".$language['oldest_data']."$monthdate /".$daydate2[0]."/ $yeardate</h2>";
				}
			else
				{
				echo"<h2>".$language['oldest_data']."".$daydate2[0]." / $monthdate / $yeardate</h2>";
				}
			echo"<br><br><table>\n";
			echo"<tr><td valign='top'>\n";
			echo"".$language['data_suppress2']."\n";
			echo"</td><td>\n";
			echo"<div class=\"form3\">\n";
			echo"<form action=\"index.php\" method=\"POST\" >\n";
			echo"<input type=\"radio\" name=\"datatosuppress\" value=\"1\" checked>".$language['one_year_data']."<br><br>\n";
			echo"<input type=\"radio\" name=\"datatosuppress\" value=\"2\">".$language['six_months_data']."<br><br>\n";
			echo"<input type=\"radio\" name=\"datatosuppress\" value=\"3\">".$language['one_month_data']."<br><br>\n";
			echo "<input type=\"hidden\" name =\"suppressdata\" value=\"1\">\n";	
			echo "<input type=\"hidden\" name =\"navig\" value=\"6\">\n";
			echo "<input type=\"hidden\" name =\"validform\" value=\"17\">\n";
			echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo"</form>\n";
			echo"</div>";
			echo"</td></tr></table>\n";
			echo"<p>".$language['data_suppress3']."</p>\n";
			}
		else
			{
			echo"<h1>".$language['no_data']."</h1>";			
			}
		
	}
?>