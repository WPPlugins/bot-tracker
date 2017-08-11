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
// file: admindatasuppress.php
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
//initialize array
$nbvisits=array();
$nbrpagesview=array();
$listmonth=array();
$crawlttablepage=array();	
	
		
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
        //query to get the number of visits and page view per month/year-site 
          
             $sqlstats = "SELECT CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%m/%Y'),'-',crawlt_site_id_site) ,COUNT(DISTINCT id_visit),COUNT(DISTINCT crawlt_pages_id_page) FROM crawlt_visits
             WHERE date < '".sql_quote($datetosuppress)."' 
             GROUP BY CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%m/%Y'),'-',crawlt_site_id_site)"; 
 
                       
            $requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");            
            $nbrresult=mysql_num_rows($requetestats);
            
            if($nbrresult>=1)
                {       
                while ($ligne = mysql_fetch_row($requetestats))                                                                              
                    {        
                    $nbvisits[$ligne[0]]=$ligne[1];
                    $nbrpagesview[$ligne[0]]=$ligne[2];
                     $listmonth[$ligne[0]]=$ligne[0];      
                    }
                            
           
           
            
         //query to get the top 3 crawler (in number of visit) for each month/year-site   
         $sqlstats = "SELECT CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%m/%Y'),'-',crawlt_site_id_site,'+',crawler_name) ,COUNT(DISTINCT id_visit) AS nbvisit FROM crawlt_visits, crawlt_crawler 
         WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
         AND date < '".sql_quote($datetosuppress)."' 
         GROUP BY CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%m/%Y'),'-',crawlt_site_id_site,'+',crawler_name)
         ORDER BY nbvisit DESC"; 
             
        $requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");            
        $nbrresult=mysql_num_rows($requetestats);
        
        if($nbrresult>=1)
            {       
            while ($ligne = mysql_fetch_row($requetestats))                                                                              
                {
                $monthsitecrawler = explode('+', $ligne[0]);
                $monthsite = $monthsitecrawler[0];
                $crawler = $monthsitecrawler[1]; 
                ${'topcrawler'.$monthsite}[]=$crawler;
                ${'nbvisits'.$monthsite}[$crawler]= $ligne[1];                
                }
            } 
        
                
               
         //query to get the top 3 crawlers (in number of page viewed) for each month/year-site   
         $sqlstats = "SELECT CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%m/%Y'),'-',crawlt_site_id_site,'+',crawler_name) ,COUNT(DISTINCT crawlt_pages_id_page) AS nbpage FROM crawlt_visits, crawlt_crawler 
         WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
         AND date < '".sql_quote($datetosuppress)."' 
         GROUP BY CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%m/%Y'),'-',crawlt_site_id_site,'+',crawler_name)
         ORDER BY nbpage DESC"; 
             
        $requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");            
        $nbrresult=mysql_num_rows($requetestats);
        
        if($nbrresult>=1)
            {       
            while ($ligne = mysql_fetch_row($requetestats))                                                                              
                {
                $monthsitecrawler = explode('+', $ligne[0]);
                $monthsite = $monthsitecrawler[0];
                $crawler = $monthsitecrawler[1]; 
                ${'topcrawlerpage'.$monthsite}[]=$crawler;
                ${'nbpages'.$monthsite}[$crawler]= $ligne[1];                
                }
            } 
        

            //preparation of the request to add the datas in the archive table
            foreach ($listmonth as $month)
                {
                //caculation of number of visits for the month
                $nbrvisits=$nbvisits[$month];
                //caculation of number of pages view for the month
                $nbrpages= $nbrpagesview[$month];
                //caculation of top visit for the month

                
                if(isset(${'topcrawler'.$month}[0]))
                    {
                    $tv1 = "1)".${'topcrawler'.$month}[0]." (".${'nbvisits'.$month}[${'topcrawler'.$month}[0]]." ".$language['nbr_visits'].")";
                    }
                else
                    {
                    $tv1="";
                    }
                if(isset(${'topcrawler'.$month}[1]))
                    {                
                    $tv2 = "2)".${'topcrawler'.$month}[1]." (".${'nbvisits'.$month}[${'topcrawler'.$month}[1]]." ".$language['nbr_visits'].")";
                    }
                else
                    {
                    $tv2="";
                    }            
                if(isset(${'topcrawler'.$month}[2]))
                    {         
                    $tv3 = "3)".${'topcrawler'.$month}[2]." (".${'nbvisits'.$month}[${'topcrawler'.$month}[2]]." ".$language['nbr_visits'].")";          
                    }
                else
                    {
                    $tv3="";
                    }             
                
 
                if(isset(${'topcrawlerpage'.$month}[0]))
                    {
                    $tpv1 = "1)".${'topcrawlerpage'.$month}[0]." (".${'nbpages'.$month}[${'topcrawlerpage'.$month}[0]]." ".$language['nbr_pages'].")";
                    }
                else
                    {
                    $tpv1="";
                    }
                if(isset(${'topcrawlerpage'.$month}[1]))
                    {                
                    $tpv2 = "2)".${'topcrawlerpage'.$month}[1]." (".${'nbpages'.$month}[${'topcrawlerpage'.$month}[1]]." ".$language['nbr_pages'].")";
                    }
                else
                    {
                    $tpv2="";
                    }            
                if(isset(${'topcrawlerpage'.$month}[2]))
                    {         
                    $tpv3 = "3)".${'topcrawlerpage'.$month}[2]." (".${'nbpages'.$month}[${'topcrawlerpage'.$month}[2]]." ".$language['nbr_pages'].")";          
                    }
                else
                    {
                    $tpv3="";
                    }                 
                                   
              
 
                //insertion request
                $sqlarchive="INSERT INTO crawlt_archive (mois, nbr_visits, pages_view, top_visits_1,top_visits_2,top_visits_3,top_pages_view_1,top_pages_view_2,top_pages_view_3) VALUES ('".sql_quote($month)."', '".sql_quote($nbrvisits)."', '".sql_quote($nbrpages)."', '".sql_quote($tv1)."', '".sql_quote($tv2)."', '".sql_quote($tv3)."', '".sql_quote($tpv1)."', '".sql_quote($tpv2)."', '".sql_quote($tpv3)."')";
                $requetearchive = mysql_query($sqlarchive, $connexion) or die("MySQL query error");            
                
                }

    
            if($requetearchive)
                {
                
                //database query to suppress the data in visits table		
                $sqldelete="DELETE FROM crawlt_visits WHERE date < '".sql_quote($datetosuppress)."'";
                $requetedelete = mysql_query($sqldelete, $connexion) or die("MySQL query error");	
                
                //database query to optimize the table
                $sqloptimize = "OPTIMIZE TABLE crawlt_visits";
                $requeteoptimize = mysql_query($sqloptimize, $connexion) or die("MySQL query error");
                
                
                //database query to list the pages no more used in visit table
                $sql = "SELECT id_page FROM  crawlt_pages
                LEFT OUTER JOIN crawlt_visits
                ON crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
                WHERE crawlt_visits.crawlt_pages_id_page IS NULL";    	
                $requete = mysql_query($sql, $connexion) or die("MySQL query error");
                $nbrresult=mysql_num_rows($requete);
                if($nbrresult>=1)
                  {
                   while ($ligne = mysql_fetch_row($requete))                                                                              
                      {
                      $crawlttablepage[]=$ligne[0];                                                
                      }                
                  
                  $crawltlistpage=implode("','",$crawlttablepage);
                  
                  //database query to suppress the data in page table		
                  $sqldelete2="DELETE FROM crawlt_pages WHERE id_page IN ('$crawltlistpage')";
                  $requetedelete2 = mysql_query($sqldelete2, $connexion) or die("MySQL query error");	                
                                 
                  //database query to optimize the table
                  $sqloptimize2 = "OPTIMIZE TABLE crawlt_pages";
                  $requeteoptimize2 = mysql_query($sqloptimize2, $connexion) or die("MySQL query error");                
                  }
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