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
// file: mail.php
//----------------------------------------------------------------------
@error_reporting(E_ALL);
//function to format the numbers for display
function crawltnumbdisp($value)
    {
    global  $crawltlang ;
    if($crawltlang=='french' OR $crawltlang=='frenchiso')
        {
        $value = number_format($value,0,","," ");
        }
    else
        {
        $value = number_format($value,0,".",",");
        }
    return $value;
    } 
//images url
$crawltimage = $crawltpath."/images/banniere.png";
$crawltimage2 = $crawltpath."/images/background.png";
$crawltimage3 = $crawltpath."/images/fond.png";
$crawltimage4 = $crawltpath."/images/logo.jpg";
//initialize array
$listlangcrawlt=array();
//include listlang file
include $crawltpath."/include/listlang.php";    

//language file include
if(in_array($crawltlang,$listlangcrawlt))
    {
    include $crawltpath."/language/".$crawltlang.".php";
    }

 //update the crawlt_config table 
$sqlcrawltupdatemail ="UPDATE crawlt_config SET datelastmail='".crawlt_sql_quote($crawltdatetoday)."'";

$requetecrawltupdatemail = mysql_query($sqlcrawltupdatemail, $crawltconnexion) or die("MySQL query error");

//request date calculation
$crawltdaterequest1 =	date("Y-m-d",($crawltts - 86400) );
$crawltdaterequest2 =	date("Y-m-d",$crawltts );


$crawltts2 = explode('-', $crawltdatetoday2);
$crawltyeartoday = $crawltts2[0];
$crawltmonthtoday = $crawltts2[1];
$crawltdaytoday = $crawltts2[2]; 

if( $crawltdaytoday==1)
    {
    if($crawltmonthtoday != 1)
        {
        $crawltmonthtoday = $crawltmonthtoday -1;
        if(($crawltmonthtoday)<10)
            {
            $crawltmonthtoday = "0".$crawltmonthtoday;
            }         
        }
    else
        {
        $crawltmonthtoday = 12;
        $crawltyeartoday = $crawltyeartoday-1;
        }    
    }
$crawltdaterequest3 = date("Y-m-d", mktime(0,0,0,$crawltmonthtoday,1,$crawltyeartoday));
$crawltdaterequest4 = date("Y-m-d", mktime(0,0,0,($crawltmonthtoday-1),1,$crawltyeartoday));
$crawltdaterequest5 = date("t", mktime(0,0,0,($crawltmonthtoday-1),1,$crawltyeartoday));
$crawltdaterequest6 = date("Y-m-d", mktime(0,0,0,($crawltmonthtoday-1),28,$crawltyeartoday));
$crawltdaterequest7 =	date("Y-m-d",($crawltts - 7776000) );


$crawlttoday = explode('-', $crawltdaterequest1);
$crawltyeartoday = $crawlttoday[0];
$crawltmonthtoday = $crawlttoday[1];
$crawltdaytoday = $crawlttoday[2];

//mysql requete for site id
$sqlcrawltsite = "SELECT * FROM crawlt_site";

$requetecrawltsite = mysql_query($sqlcrawltsite, $crawltconnexion) or die("MySQL query error");

$nbrresultcrawlt2=mysql_num_rows($requetecrawltsite);
    
if($nbrresultcrawlt2>=1)
        {
        $listsitecrawlt=array();
        $crawltsitename=array();
        $crawltsiteurl=array();
        	
        while ($lignecrawlt2 = mysql_fetch_object($requetecrawltsite))                                                                              
            {
            $namecrawlt=$lignecrawlt2->name; 
            $siteidcrawlt=$lignecrawlt2->id_site;
            $listsitecrawlt[]=$siteidcrawlt;
            $crawltsitename[$siteidcrawlt]=$namecrawlt;
            $crawltsiteurl[$siteidcrawlt]=$lignecrawlt2->url;			
            }        
        }


//prepare the message
if($crawltmailishtml==1)
    {
    @$crawltmessage.="<div style='font-size:14px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:center; border:2px solid navy;' />";
    @$crawltmessage.="<div style='background-image:url(cid:background); text-align:left;'/>";
    @$crawltmessage.="<img src='cid:banner' alt='CrawlTrack' /><br>";
    @$crawltmessage.="</div>";
    }


foreach ($listsitecrawlt as $crawltsite)
            {

          
            
             //mysql request for backlinks and indexed pages
            $crawltsqlseo = "SELECT   linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious,tagdelicious FROM crawlt_seo_position
            WHERE  id_site='".crawlt_sql_quote($crawltsite)."'
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."'    
            ORDER BY date";

            $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteseo);
            
            $crawltlinkyahoo = 0;
            $crawltpageyahoo = 0;
            $crawltlinkmsn = 0;
            $crawltpagemsn = 0; 
            $crawltlinkdelicious= 0;                  
            
            if($crawltnbrresult>=1)
                {                 
                while ($crawltligneseo = mysql_fetch_row($crawltrequeteseo))                                                                              
                    {                 
                    $crawltlinkyahoo = $crawltlinkyahoo + $crawltligneseo[0];
                    $crawltpageyahoo = $crawltpageyahoo + $crawltligneseo[1];
                    $crawltlinkmsn = $crawltlinkmsn + $crawltligneseo[2];
                    $crawltpagemsn = $crawltpagemsn + $crawltligneseo[3]; 
                    $crawltlinkdelicious= $crawltlinkdelicious + $crawltligneseo[4];                          
                    }    
                }

             //mysql request for backlinks and indexed pages at the beginning of the month (to avoid to get 0 if we add a problem the 1st of the month we search for the max value between the 28th of the previous month and the 1st)
            $crawltsqlseo = "SELECT   MAX(linkyahoo), MAX(pageyahoo), MAX(linkmsn), MAX(pagemsn), MAX(nbrdelicious) FROM crawlt_seo_position
            WHERE  id_site='".crawlt_sql_quote($crawltsite)."'
            AND  date <='".crawlt_sql_quote($crawltdaterequest3)."'
            AND  date >='".crawlt_sql_quote($crawltdaterequest6)."'            
            ORDER BY date";

            $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error2");
            $crawltnbrresult=mysql_num_rows($crawltrequeteseo);
            
            $crawltlinkyahoodb = 0;
            $crawltpageyahoodb = 0;
            $crawltlinkmsndb = 0;
            $crawltpagemsndb = 0; 
            $crawltlinkdeliciousdb= 0;                  
            
            if($crawltnbrresult>=1)
                {                 
                while ($crawltligneseo = mysql_fetch_row($crawltrequeteseo))                                                                              
                    {                 
                    $crawltlinkyahoodb = $crawltlinkyahoodb + $crawltligneseo[0];
                    $crawltpageyahoodb = $crawltpageyahoodb + $crawltligneseo[1];
                    $crawltlinkmsndb = $crawltlinkmsndb + $crawltligneseo[2];
                    $crawltpagemsndb = $crawltpagemsndb + $crawltligneseo[3]; 
                    $crawltlinkdeliciousdb= $crawltlinkdeliciousdb + $crawltligneseo[4];                          
                    }    
                }

            //request to have the visits of the Google crawler          
            $sqlgoogle = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'GoogleBot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetegoogle = mysql_query($sqlgoogle, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetegoogle);
            if($crawltnbrresult>=1)
                {
                $crawltlignegoogle = mysql_fetch_row($crawltrequetegoogle);
                $crawltvisitgoogle=$crawltlignegoogle[1];
                }
            else
                {
                $crawltvisitgoogle= 0;
                }
            
            //request to have the visits of the Google crawler  since the beginning of the month        
            $sqlgoogle = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest3)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'GoogleBot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetegoogle = mysql_query($sqlgoogle, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetegoogle);
            if($crawltnbrresult>=1)
                {
                $crawltlignegoogle = mysql_fetch_row($crawltrequetegoogle);
                $crawltvisitgoogledb=$crawltlignegoogle[1];
                }
            else
                {
                $crawltvisitgoogledb= 0;
                }

            //request to have the visits of the Google crawler   for the previous month        
            $sqlgoogle = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest4)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'GoogleBot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetegoogle = mysql_query($sqlgoogle, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetegoogle);
            if($crawltnbrresult>=1)
                {
                $crawltlignegoogle = mysql_fetch_row($crawltrequetegoogle);
                $crawltvisitgooglemb=($crawltlignegoogle[1]/$crawltdaterequest5)*$crawltdaytoday;
                }
            else
                {
                $crawltvisitgooglemb= 0;
                }
                
            //request to have the visits of the Google crawler  for the last 3 monthes        
            $sqlgoogle = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest7)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'GoogleBot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetegoogle = mysql_query($sqlgoogle, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetegoogle);
            if($crawltnbrresult>=1)
                {
                $crawltlignegoogle = mysql_fetch_row($crawltrequetegoogle);
                $crawltvisitgoogletm= ($crawltlignegoogle[1]/90)*$crawltdaytoday;
                }
            else
                {
                $crawltvisitgoogletm= 0;
                }                
                
            //request to have the visits of the Yahoo crawler          
            $sqlyahoo = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Slurp Inktomi (Yahoo)'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteyahoo = mysql_query($sqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteyahoo);
            if($crawltnbrresult>=1)
                {
                $crawltligneyahoo = mysql_fetch_row($crawltrequeteyahoo);
                $crawltvisityahoo=$crawltligneyahoo[1];
                }
            else
                {
                $crawltvisityahoo= 0;
                }
            
            //request to have the visits of the Yahoo crawler  since the beginning of the month        
            $sqlyahoo = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest3)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Slurp Inktomi (Yahoo)'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteyahoo = mysql_query($sqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteyahoo);
            if($crawltnbrresult>=1)
                {
                $crawltligneyahoo = mysql_fetch_row($crawltrequeteyahoo);
                $crawltvisityahoodb=$crawltligneyahoo[1];
                }
            else
                {
                $crawltvisityahoodb= 0;
                }

            //request to have the visits of the Yahoo crawler   for the previous month         
            $sqlyahoo = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest4)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Slurp Inktomi (Yahoo)'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteyahoo = mysql_query($sqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteyahoo);
            if($crawltnbrresult>=1)
                {
                $crawltligneyahoo = mysql_fetch_row($crawltrequeteyahoo);
                $crawltvisityahoomb=($crawltligneyahoo[1]/$crawltdaterequest5)*$crawltdaytoday;
                }
            else
                {
                $crawltvisityahoomb= 0;
                }

            //request to have the visits of the Yahoo crawler for the last 3 monthes          
            $sqlyahoo = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest7)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Slurp Inktomi (Yahoo)'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteyahoo = mysql_query($sqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteyahoo);
            if($crawltnbrresult>=1)
                {
                $crawltligneyahoo = mysql_fetch_row($crawltrequeteyahoo);
                $crawltvisityahootm=($crawltligneyahoo[1]/90)*$crawltdaytoday;
                }
            else
                {
                $crawltvisityahootm= 0;
                }
                
            //request to have the visits of the Live crawler          
            $sqlmsn = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'MSN Bot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetemsn = mysql_query($sqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetemsn);
            if($crawltnbrresult>=1)
                {
                $crawltlignemsn = mysql_fetch_row($crawltrequetemsn);
                $crawltvisitmsn=$crawltlignemsn[1];
                }
            else
                {
                $crawltvisitmsn= 0;
                }
            
            //request to have the visits of the Live crawler  since the beginning of the month        
            $sqlmsn = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest3)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'MSN Bot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetemsn = mysql_query($sqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetemsn);
            if($crawltnbrresult>=1)
                {
                $crawltlignemsn = mysql_fetch_row($crawltrequetemsn);
                $crawltvisitmsndb=$crawltlignemsn[1];
                }
            else
                {
                $crawltvisitmsndb= 0;
                }

            //request to have the visits of the Live crawler for the previous month        
            $sqlmsn = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest4)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'MSN Bot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetemsn = mysql_query($sqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetemsn);
            if($crawltnbrresult>=1)
                {
                $crawltlignemsn = mysql_fetch_row($crawltrequetemsn);
                $crawltvisitmsnmb=($crawltlignemsn[1]/$crawltdaterequest5)*$crawltdaytoday;
                }
            else
                {
                $crawltvisitmsnmb= 0;
                }

            //request to have the visits of the Live crawler for the last 3 monthes         
            $sqlmsn = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest7)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'MSN Bot'    
            GROUP BY crawler_name";
                                     
            $crawltrequetemsn = mysql_query($sqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequetemsn);
            if($crawltnbrresult>=1)
                {
                $crawltlignemsn = mysql_fetch_row($crawltrequetemsn);
                $crawltvisitmsntm=($crawltlignemsn[1]/90)*$crawltdaytoday;
                }
            else
                {
                $crawltvisitmsntm= 0;
                }

            //request to have the visits of the Ask crawler          
            $sqlask = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Ask Jeeves/Teoma'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteask = mysql_query($sqlask, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteask);
            if($crawltnbrresult>=1)
                {
                $crawltligneask = mysql_fetch_row($crawltrequeteask);
                $crawltvisitask=$crawltligneask[1];
                }
            else
                {
                $crawltvisitask= 0;
                }
            
            //request to have the visits of the Ask crawler  since the beginning of the month        
            $sqlask = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest3)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Ask Jeeves/Teoma'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteask = mysql_query($sqlask, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteask);
            if($crawltnbrresult>=1)
                {
                $crawltligneask = mysql_fetch_row($crawltrequeteask);
                $crawltvisitaskdb=$crawltligneask[1];
                }
            else
                {
                $crawltvisitaskdb= 0;
                }

            //request to have the visits of the Ask crawler  for the previous month        
            $sqlask = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest4)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Ask Jeeves/Teoma'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteask = mysql_query($sqlask, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteask);
            if($crawltnbrresult>=1)
                {
                $crawltligneask = mysql_fetch_row($crawltrequeteask);
                $crawltvisitaskmb=($crawltligneask[1]/$crawltdaterequest5)*$crawltdaytoday;
                }
            else
                {
                $crawltvisitaskmb= 0;
                }

            //request to have the visits of the Ask crawler for the last 3 monthes        
            $sqlask = "SELECT  crawler_name, COUNT(DISTINCT id_visit), crawlt_crawler_id_crawler FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND  date >='".crawlt_sql_quote($crawltdaterequest7)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawler_name= 'Ask Jeeves/Teoma'    
            GROUP BY crawler_name";
                                     
            $crawltrequeteask = mysql_query($sqlask, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresult=mysql_num_rows($crawltrequeteask);
            if($crawltnbrresult>=1)
                {
                $crawltligneask = mysql_fetch_row($crawltrequeteask);
                $crawltvisitasktm=($crawltligneask[1]/90)*$crawltdaytoday;
                }
            else
                {
                $crawltvisitasktm= 0;
                }

            //request to have the number total of crawler visits
            $crawltsqlstats2 = "SELECT COUNT(id_visit) FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'";

            $crawltrequetestats2 = mysql_query($crawltsqlstats2, $crawltconnexion) or die("MySQL query error");
            $crawltligne2 = mysql_fetch_row($crawltrequetestats2);
            $crawltvisittotal=$crawltligne2[0]; 

            //request to have the number total of crawler visits  since the beginning of the month  
            $crawltsqlstats2 = "SELECT COUNT(id_visit) FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
            AND date >='".crawlt_sql_quote($crawltdaterequest3)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'";

            $crawltrequetestats2 = mysql_query($crawltsqlstats2, $crawltconnexion) or die("MySQL query error");
            $crawltligne2 = mysql_fetch_row($crawltrequetestats2);
            $crawltvisittotaldb=$crawltligne2[0]; 

            //request to have the number total of crawler visits for the previous month  
            $crawltsqlstats2 = "SELECT COUNT(id_visit) FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
            AND date >='".crawlt_sql_quote($crawltdaterequest4)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'";

            $crawltrequetestats2 = mysql_query($crawltsqlstats2, $crawltconnexion) or die("MySQL query error");
            $crawltligne2 = mysql_fetch_row($crawltrequetestats2);
            $crawltvisittotalmb=($crawltligne2[0]/$crawltdaterequest5)*$crawltdaytoday;      
            
            //request to have the number total of crawler visits  for the last 3 monthes  
            $crawltsqlstats2 = "SELECT COUNT(id_visit) FROM crawlt_visits, crawlt_crawler
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
            AND date >='".crawlt_sql_quote($crawltdaterequest7)."'             
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_visits.crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'";

            $crawltrequetestats2 = mysql_query($crawltsqlstats2, $crawltconnexion) or die("MySQL query error");
            $crawltligne2 = mysql_fetch_row($crawltrequetestats2);
            $crawltvisittotaltm=($crawltligne2[0]/90)*$crawltdaytoday;             
                   
            
            //request to have the visits send by Google           
            $crawltsqlgoogle2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '1' "; 
                             
            $crawltrequetegoogle2 = mysql_query($crawltsqlgoogle2, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendgoogle=mysql_num_rows($crawltrequetegoogle2); 
            
            //request to have the visits send by Google since the beginning of the month         
            $crawltsqlgoogle2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest3)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '1' "; 
                             
            $crawltrequetegoogle2 = mysql_query($crawltsqlgoogle2, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendgoogledb=mysql_num_rows($crawltrequetegoogle2); 
            
            //request to have the visits send by Google  for the previous month           
            $crawltsqlgoogle2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest4)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '1' "; 
                             
            $crawltrequetegoogle2 = mysql_query($crawltsqlgoogle2, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendgooglemb=(mysql_num_rows($crawltrequetegoogle2)/$crawltdaterequest5)*$crawltdaytoday; 
            
            //request to have the visits send by Google for the last 3 monthes         
            $crawltsqlgoogle2 = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest7)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '1' "; 
                             
            $crawltrequetegoogle2 = mysql_query($crawltsqlgoogle2, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendgoogletm=(mysql_num_rows($crawltrequetegoogle2)/90)*$crawltdaytoday;             

            //request to have the visits send by Yahoo           
            $crawltsqlyahoo = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '2' "; 
                                         
            $crawltrequeteyahoo = mysql_query($crawltsqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendyahoo=mysql_num_rows($crawltrequeteyahoo);  
            
            //request to have the visits send by Yahoo since the beginning of the month       
            $crawltsqlyahoo = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest3)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '2' "; 
                                         
            $crawltrequeteyahoo = mysql_query($crawltsqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendyahoodb=mysql_num_rows($crawltrequeteyahoo); 
            
            //request to have the visits send by Yahoo for the previous month        
            $crawltsqlyahoo = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest4)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '2' "; 
                                         
            $crawltrequeteyahoo = mysql_query($crawltsqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendyahoomb=(mysql_num_rows($crawltrequeteyahoo)/$crawltdaterequest5)*$crawltdaytoday; 
            
            //request to have the visits send by Yahoo for the last 3 monthes       
            $crawltsqlyahoo = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest7)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '2' "; 
                                         
            $crawltrequeteyahoo = mysql_query($crawltsqlyahoo, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendyahootm=(mysql_num_rows($crawltrequeteyahoo)/90)*$crawltdaytoday;                                               

            //request to have the visits send by Live           
            $crawltsqlmsn = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '3' "; 
                             
            $crawltrequetemsn = mysql_query($crawltsqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendmsn=mysql_num_rows($crawltrequetemsn); 
            
            //request to have the visits send by Live since the beginning of the month          
            $crawltsqlmsn = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest3)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '3' "; 
                             
            $crawltrequetemsn = mysql_query($crawltsqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendmsndb=mysql_num_rows($crawltrequetemsn);            

            //request to have the visits send by Live for the previous month         
            $crawltsqlmsn = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest4)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '3' "; 
                             
            $crawltrequetemsn = mysql_query($crawltsqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendmsnmb=(mysql_num_rows($crawltrequetemsn)/$crawltdaterequest5)*$crawltdaytoday;

            //request to have the visits send by Live for the last 3 monthes          
            $crawltsqlmsn = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest7)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '3' "; 
                             
            $crawltrequetemsn = mysql_query($crawltsqlmsn, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendmsntm=(mysql_num_rows($crawltrequetemsn)/90)*$crawltdaytoday;              

            //request to have the visits send by Ask           
            $crawltsqlask = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '4' "; 
                             
            $crawltrequeteask = mysql_query($crawltsqlask, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendask=mysql_num_rows($crawltrequeteask);                
                
            //request to have the visits send by Ask  since the beginning of the month        
            $crawltsqlask = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest3)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '4' "; 
                             
            $crawltrequeteask = mysql_query($crawltsqlask, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendaskdb=mysql_num_rows($crawltrequeteask); 
            
            //request to have the visits send by Ask for the previous month          
            $crawltsqlask = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest4)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest3)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '4' "; 
                             
            $crawltrequeteask = mysql_query($crawltsqlask, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendaskmb=(mysql_num_rows($crawltrequeteask)/$crawltdaterequest5)*$crawltdaytoday;             
            
            //request to have the visits send by Ask  for the last 3 monthes        
            $crawltsqlask = "SELECT  id_visit, crawlt_id_crawler FROM crawlt_visits_human
            WHERE  date >='".crawlt_sql_quote($crawltdaterequest7)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."' 
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'
            AND crawlt_id_crawler= '4' "; 
                             
            $crawltrequeteask = mysql_query($crawltsqlask, $crawltconnexion) or die("MySQL query error");
            $crawltvisitsendasktm=(mysql_num_rows($crawltrequeteask)/90)*$crawltdaytoday;  
            
            //query to get the good site list
            $sql = "SELECT host_site FROM crawlt_good_sites";
            $requete = mysql_query($sql, $crawltconnexion) or die("MySQL query error1");
            $nbrresult=mysql_num_rows($requete);
            if($nbrresult>=1)
                {	
                while ($ligne = mysql_fetch_row($requete))
                    {
                    $listgoodsite[]=$ligne[0];               
                    }
                }
            else
              {
              $listgoodsite=array();
              }
            $testcrawler= "AND crawlt_pages.url_page LIKE '%http://%'";
            foreach($listgoodsite as $goodsite)
             {
             $testcrawler.=" AND crawlt_pages.url_page NOT LIKE '%$goodsite%'";
             }
            //request to have the number of css hacking tentatives
             $crawltsqlhacking = "SELECT id_visit FROM crawlt_visits, crawlt_pages
            WHERE  crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
            $testcrawler
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."'       
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."'";   
           
            $crawltrequetehacking = mysql_query($crawltsqlhacking, $crawltconnexion) or die("MySQL query error2");
            $crawlthacking=mysql_num_rows($crawltrequetehacking);
            
            //request to have the number of sql hacking tentatives
             $crawltsqlhacking2 = "SELECT id_visit FROM crawlt_visits, crawlt_pages
            WHERE  ( crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
            AND url_page LIKE '%%20select%20%'
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."'       
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."')
            OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page
            AND url_page LIKE '%%20where%20%'
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."'       
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."')
            OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page
            AND url_page LIKE '%%20like%20%'
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."'       
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."')            
            OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page
            AND url_page LIKE '%%20or%20%'
            AND  date >'".crawlt_sql_quote($crawltdaterequest1)."' 
            AND  date <'".crawlt_sql_quote($crawltdaterequest2)."'       
            AND crawlt_site_id_site='".crawlt_sql_quote($crawltsite)."')            
            ";   
            
            $crawltrequetehacking2 = mysql_query($crawltsqlhacking2, $crawltconnexion) or die("MySQL query error3");
            $crawlthacking2=mysql_num_rows($crawltrequetehacking2);                                
            
            if($crawltmailishtml==1) //case HTML Email
                {
                @$crawltmessage.="<div style='font-size:18px; color:#003399;  border-top:2px solid navy; border-bottom:2px solid navy;  background-color: #E6E6FA;' />";
                @$crawltmessage.="<span style='float:left;'>Site: <b>".$crawltsitename[$crawltsite]."</b></span><span style='float:right; font-size:14px;'>".$language['daily-stats'].": ". $crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday." </span><br/>";
                @$crawltmessage.="</div><br>";
                @$crawltmessage.="<div style='font-size:16px; color:#A52A2A; text-align:center;' />";
                @$crawltmessage.="<b>".$language['nbr_visits_crawler']."</b><br/><br/>";
                @$crawltmessage.="</div>";            
                @$crawltmessage.="<table style='font-size:14px; color:#003399; text-align:center;   margin-left: auto;  margin-right: auto' cellpadding='0px' cellspacing='0'>";            
                @$crawltmessage.="<tr><td rowspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;&nbsp;&nbsp;</td>";             
                @$crawltmessage.="<td rowspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>". $crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday."</b>&nbsp;&nbsp;</td>";           
                @$crawltmessage.="<td rowspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language[$crawltmonthtoday]."&nbsp;".$crawltyeartoday."</b>&nbsp;&nbsp;</td>";            
                @$crawltmessage.="<td colspan='2' style='text-align: center;  border: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language['evolution']."</b>&nbsp;&nbsp;</td></tr>";
                if(($crawltmonthtoday-1)<10)
                    {
                    if($crawltmonthtoday==1)
                        {
                        $crawltlastmonth = 12;
                        }
                     else
                        {
                        $crawltlastmonth = "0".($crawltmonthtoday-1);
                        }
                    }
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language[$crawltlastmonth]."&nbsp;".$crawltyeartoday."</b>&nbsp;&nbsp;</td>";
                @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; border-right: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language['lastthreemonths']."</b>&nbsp;&nbsp;</td></tr>";
    //ask crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------           
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;&nbsp;<b>".$language['ask']."</b>&nbsp;&nbsp;</td>";
    
    
                @$crawltmessage.="<td   style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitask."</b></td>"; 
    
    //-------------------------------------------                
                if($crawltvisitaskmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitaskdb-$crawltvisitaskmb)/$crawltvisitaskmb)*100),1);
                    }
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitaskdb."</b></td>";                   
                if($crawltvisitaskdb > $crawltvisitaskmb)
                    {                                                                          
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisitaskdb <$crawltvisitaskmb) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td>";            
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b>=</b></td>";            
                    } 
                    
                if($crawltvisitasktm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitaskdb-$crawltvisitasktm)/$crawltvisitasktm)*100),1);
                    }                 
                if($crawltvisitaskdb > $crawltvisitasktm)
                    {                                                                          
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisitaskdb <$crawltvisitasktm) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td></tr>";            
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b>=</b></td></tr>";            
                    }                
                    
               
    //google crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------           
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'>&nbsp;&nbsp;<b>".$language['google']."</b>&nbsp;&nbsp;</td>";
    
                @$crawltmessage.="<td  style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisitgoogle."</b></td>"; 
    
    //-------------------------------------------                
                if($crawltvisitgooglemb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitgoogledb-$crawltvisitgooglemb)/$crawltvisitgooglemb)*100),1);
                    } 
                @$crawltmessage.="<td  width='120px' style='text-align: center; color:#003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisitgoogledb."</b></td>";                    
                if($crawltvisitgoogledb > $crawltvisitgooglemb)
                    {                                                                         
                    @$crawltmessage.="<td style='text-align: center;   color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisitgoogledb <$crawltvisitgooglemb) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td>";            
                    }
                else
                    {
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399;  background-color: #E6E6FA;'><b>=</b></td>";            
                    }                                                            
                if($crawltvisitgoogletm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitgoogledb-$crawltvisitgoogletm)/$crawltvisitgoogletm)*100),1);
                    }                 
                if($crawltvisitgoogledb > $crawltvisitgoogletm)
                    {                                                                          
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisitgoogledb <$crawltvisitgoogletm) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td></tr>";            
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #E6E6FA;'><b>=</b></td></tr>";            
                    } 
    //msn crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                      
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;&nbsp;<b>".$language['msn']."</b>&nbsp;&nbsp;</td>";
    
                @$crawltmessage.="<td   style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitmsn."</b></td>"; 
           
    //-------------------------------------------                
                if($crawltvisitmsnmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitmsndb-$crawltvisitmsnmb)/$crawltvisitmsnmb)*100),1);
                    }
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitmsndb."</b></td>";                 
                if($crawltvisitmsndb > $crawltvisitmsnmb)
                    {                                                                           
                    @$crawltmessage.="<td style='text-align: center;   color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisitmsndb <$crawltvisitmsnmb) 
                    { 
                    @$crawltmessage.="<td style='text-align: center;  color: #FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td>";            
                    }  
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b>=</b></td>";            
                    }                                                       
                if($crawltvisitmsntm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitmsndb-$crawltvisitmsntm)/$crawltvisitmsntm)*100),1);
                    }                 
                if($crawltvisitmsndb > $crawltvisitmsntm)
                    {                                                                          
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisitmsndb <$crawltvisitmsntm) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td></tr>";            
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b>=</b></td></tr>";            
                    }
    //yahoo crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                       
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'>&nbsp;&nbsp;<b>".$language['yahoo']."</b>&nbsp;&nbsp;</td>";
                
                @$crawltmessage.="<td   style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisityahoo."</b></td>"; 
                     
    //-------------------------------------------                
                if($crawltvisityahoomb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisityahoodb-$crawltvisityahoomb)/$crawltvisityahoomb)*100),1);
                    }             
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisityahoodb."</b></td>";
                if($crawltvisityahoodb > $crawltvisityahoomb)
                    {                                                                         
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisityahoodb <$crawltvisityahoomb) 
                    {  
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td>";            
                    }
                else
                    { 
                    @$crawltmessage.="<td style='text-align: center; font-size:12px; border-left: 2px solid #003399;  border-bottom: 2px solid #003399;  background-color: #E6E6FA;'><b>=</b></td>";            
                    }                                                         
                if($crawltvisityahootm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisityahoodb-$crawltvisityahootm)/$crawltvisityahootm)*100),1);
                    }                 
                if($crawltvisityahoodb > $crawltvisityahootm)
                    {                                                                          
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisityahoodb <$crawltvisityahootm) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td></tr>";            
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #E6E6FA;'><b>=</b></td></tr>";            
                    }
    //all crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                       
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;&nbsp;<b>".$language['nbr_tot_visits']."</b>&nbsp;&nbsp;</td>";
    
                @$crawltmessage.="<td  style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisittotal."</b></td>"; 
                
    //-------------------------------------------                
                if($crawltvisittotalmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisittotaldb-$crawltvisittotalmb)/$crawltvisittotalmb)*100),1);
                    }            
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisittotaldb."</b></td>";
                if($crawltvisittotaldb > $crawltvisittotalmb)
                    {                                                                            
                    @$crawltmessage.="<td style='text-align: center; font-size:14px;  color:#3CB371; border-left: 2px solid #003399;  border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisittotaldb <$crawltvisittotalmb) 
                    {   
                    @$crawltmessage.="<td style='text-align: center; font-size:14px;  color:#FF0000; border-left: 2px solid #003399;  border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td>";            
                    } 
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center; font-size:14px; border-left: 2px solid #003399;  border-bottom: 2px solid #003399;  background-color: #FFFFFF;'><b>=</b></td>";            
                    }                                                      
                if($crawltvisittotaltm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisittotaldb-$crawltvisittotaltm)/$crawltvisittotaltm)*100),1);
                    }                 
                if($crawltvisittotaldb > $crawltvisittotaltm)
                    {                                                                          
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisittotaldb <$crawltvisittotaltm) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-left: 2px solid #003399; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td></tr>";            
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-left: 2px solid #003399;  border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'><b>=</b></td></tr>";            
                    }
                @$crawltmessage.="</table><br><hr/>";
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                @$crawltmessage.="<div style='font-size:16px; color:#A52A2A; text-align:center;' />";          
                @$crawltmessage.="<b>".$language['nbr_tot_visit_seo']."</b><br/><br/>";
                @$crawltmessage.="</div>";            
                @$crawltmessage.="<table style='font-size:14px; color:#003399; text-align:center;   margin-left: auto;  margin-right: auto' cellpadding='0px' cellspacing='0'>";            
                @$crawltmessage.="<tr><td rowspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;&nbsp;&nbsp;</td>";             
                @$crawltmessage.="<td rowspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>". $crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday."</b>&nbsp;&nbsp;</td>";           
                @$crawltmessage.="<td rowspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language[$crawltmonthtoday]."&nbsp;".$crawltyeartoday."</b>&nbsp;&nbsp;</td>";            
                @$crawltmessage.="<td colspan='2' style='text-align: center;  border: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language['evolution']."</b>&nbsp;&nbsp;</td></tr>";
                if(($crawltmonthtoday-1)<10)
                    {
                    if($crawltmonthtoday==1)
                        {
                        $crawltlastmonth = 12;
                        }
                     else
                        {
                        $crawltlastmonth = "0".($crawltmonthtoday-1);
                        }
                    }
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language[$crawltlastmonth]."&nbsp;".$crawltyeartoday."</b>&nbsp;&nbsp;</td>";
                @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; border-right: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language['lastthreemonths']."</b>&nbsp;&nbsp;</td></tr>";
    //visitors send by Ask----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                      
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;&nbsp;<b>".$language['ask']."</b>&nbsp;&nbsp;</td>";
    
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitsendask."</b></td>"; 
                               
    //-------------------------------------------                
                if($crawltvisitsendaskmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendaskdb-$crawltvisitsendaskmb)/$crawltvisitsendaskmb)*100),1);
                    } 
                @$crawltmessage.="<td  width='120px' style='text-align: center; color:#003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitsendaskdb."</b></td>";               
                if($crawltvisitsendaskdb > $crawltvisitsendaskmb)
                    {
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisitsendaskdb <$crawltvisitsendaskmb) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td>";            
                    }
                else
                    {
                    @$crawltmessage.="<td style='text-align: center;    border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>=</b></td>";            
                    } 
                if($crawltvisitsendasktm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendaskdb-$crawltvisitsendasktm)/$crawltvisitsendasktm)*100),1);
                    } 
                                                                   
                if($crawltvisitsendaskdb > $crawltvisitsendasktm)
                    {
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-bottom: 2px solid #003399; border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisitsendaskdb <$crawltvisitsendasktm) 
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399; border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td></tr>";            
                    }
                else
                    {
                    @$crawltmessage.="<td style='text-align: center;    border-bottom: 2px solid #003399; border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #FFFFFF;'><b>=</b></td></tr>";            
                    } 
    //visitors send by Google----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                              
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'>&nbsp;&nbsp;<b>".$language['google']."</b>&nbsp;&nbsp;</td>";
               
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisitsendgoogle."</b></td>";
                                
    //-------------------------------------------                
                if($crawltvisitsendgooglemb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendgoogledb-$crawltvisitsendgooglemb)/$crawltvisitsendgooglemb)*100),1);
                    }             
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisitsendgoogledb."</b></td>";
                if($crawltvisitsendgoogledb > $crawltvisitsendgooglemb)
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#3CB371; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisitsendgoogledb <$crawltvisitsendgooglemb) 
                    {           
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td>"; 
                    }
                else
                    {           
                    @$crawltmessage.="<td style='text-align: center;    border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>=</b></td>"; 
                    }           
                if($crawltvisitsendgoogletm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendgoogledb-$crawltvisitsendgoogletm)/$crawltvisitsendgoogletm)*100),1);
                    }              
                
                if($crawltvisitsendgoogledb > $crawltvisitsendgoogletm)
                    {
                    @$crawltmessage.="<td style='text-align: center;   color:#3CB371; border-bottom: 2px solid #003399; border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisitsendgoogledb <$crawltvisitsendgoogletm) 
                    {           
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399; border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td></tr>"; 
                    }
                else
                    {           
                    @$crawltmessage.="<td style='text-align: center;    border-bottom: 2px solid #003399; border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #E6E6FA;'><b>=</b></td></tr>"; 
                    } 
    //visitors send by Msn----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                              
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;&nbsp;<b>".$language['msn']."</b>&nbsp;&nbsp;</td>";
                     
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitsendmsn."</b></td>"; 
                                             
    //-------------------------------------------                
                if($crawltvisitsendmsnmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendmsndb-$crawltvisitsendmsnmb)/$crawltvisitsendmsnmb)*100),1);
                    }             
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltvisitsendmsndb."</b></td>";
                if($crawltvisitsendmsndb > $crawltvisitsendmsnmb)
                    {            
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-bottom: 2px solid #003399;  border-left: 2px solid #003399; background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisitsendmsndb <$crawltvisitsendmsnmb) 
                    { 
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399;  border-left: 2px solid #003399; background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td>";                 
                    }
                else
                    {                 
                    @$crawltmessage.="<td style='text-align: center;    border-bottom: 2px solid #003399;  border-left: 2px solid #003399; background-color: #FFFFFF;'><b>=</b></td>";                 
                    }                           
                if($crawltvisitsendmsntm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendmsndb-$crawltvisitsendmsntm)/$crawltvisitsendmsntm)*100),1);
                    } 
                if($crawltvisitsendmsndb > $crawltvisitsendmsntm)
                    {            
                    @$crawltmessage.="<td style='text-align: center;  color:#3CB371; border-bottom: 2px solid #003399;  border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #FFFFFF;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisitsendmsndb <$crawltvisitsendmsntm) 
                    { 
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399;  border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #FFFFFF;'><b> ".$crawltdiv."%</b></td></tr>";                 
                    }
                else
                    {                 
                    @$crawltmessage.="<td style='text-align: center;    border-bottom: 2px solid #003399;  border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #FFFFFF;'><b>=</b></td></tr>";                 
                    } 
    //visitors send by Yahoo----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                               
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'>&nbsp;&nbsp;<b>".$language['yahoo']."</b>&nbsp;&nbsp;</td>";
    
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisitsendyahoo."</b></td>"; 
                                                                                
    //-------------------------------------------                
                if($crawltvisitsendyahoomb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendyahoodb-$crawltvisitsendyahoomb)/$crawltvisitsendyahoomb)*100),1);
                    }             
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltvisitsendyahoodb."</b></td>";
                if($crawltvisitsendyahoodb > $crawltvisitsendyahoomb)
                    {              
                    @$crawltmessage.="<td style='text-align: center;   color:#3CB371; border-bottom: 2px solid #003399;  border-left: 2px solid #003399; background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td>";            
                    }
                elseif($crawltvisitsendyahoodb <$crawltvisitsendyahoomb) 
                    { 
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399;  border-left: 2px solid #003399; background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td>";                 
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399;  border-left: 2px solid #003399; background-color: #E6E6FA;'><b>=</b></td>";                 
                    }                                           
                if($crawltvisitsendyahootm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendyahoodb-$crawltvisitsendyahootm)/$crawltvisitsendyahootm)*100),1);
                    } 
                if($crawltvisitsendyahoodb > $crawltvisitsendyahootm)
                    {              
                    @$crawltmessage.="<td style='text-align: center;   color:#3CB371; border-bottom: 2px solid #003399;  border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #E6E6FA;'><b> +".$crawltdiv."%</b></td></tr>";            
                    }
                elseif($crawltvisitsendyahoodb <$crawltvisitsendyahootm) 
                    { 
                    @$crawltmessage.="<td style='text-align: center;   color:#FF0000; border-bottom: 2px solid #003399;  border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #E6E6FA;'><b> ".$crawltdiv."%</b></td></tr>";                 
                    }
                else
                    {  
                    @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399;  border-left: 2px solid #003399;  border-right: 2px solid #003399; background-color: #E6E6FA;'><b>=</b></td></tr>";                 
                    }  
                    @$crawltmessage.="</table><br><hr/>";
               
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                @$crawltmessage.="<div style='font-size:16px; color:#A52A2A; text-align:center;' />";            
                @$crawltmessage.="<b>".$language['index']."</b><br/><br/>";
                @$crawltmessage.="</div>";            
                @$crawltmessage.="<table style='font-size:14px; color:#003399; text-align:center;   margin-left: auto;  margin-right: auto' cellpadding='0px' cellspacing='0'>";            
                @$crawltmessage.="<tr><td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;&nbsp;&nbsp;</td>";             
                @$crawltmessage.="<td colspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language['nbr_tot_link']."</b>&nbsp;&nbsp;</td>";
                @$crawltmessage.="<td colspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language['nbr_tot_pages_index']."</b>&nbsp;&nbsp;</td>";            
                @$crawltmessage.="<td colspan='2' style='text-align: center;  border: 2px solid #003399; background-color: #00BFFF;'>&nbsp;&nbsp;<b>".$language['nbr_tot_bookmark']."</b>&nbsp;&nbsp;</td></tr>";
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;&nbsp;<b>".$language['msn']."</b>&nbsp;&nbsp;</td>";
    //links msn --------------------------------------------------------            
                if($crawltlinkmsndb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltlinkmsn-$crawltlinkmsndb)/$crawltlinkmsndb)*100),1);
                    }  
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltlinkmsn."</b></td>";    
                if($crawltlinkmsn > $crawltlinkmsndb)
                    {                            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#3CB371; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>(+".$crawltdiv."%*)</td>"; 
                    }
                elseif($crawltlinkmsn < $crawltlinkmsndb) 
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#FF0000; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>(".$crawltdiv."%*)</td>";            
                    }
                else
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>&nbsp;</td>";            
                    }
    //pages msn --------------------------------------------------------                            
                if($crawltpagemsndb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltpagemsn-$crawltpagemsndb)/$crawltpagemsndb)*100),1);
                    }
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltpagemsn."</b></td>";                 
                if($crawltpagemsn > $crawltpagemsndb)
                    {                            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#3CB371; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>(+".$crawltdiv."%*)</td>"; 
                    }
                elseif($crawltpagemsn < $crawltpagemsndb) 
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#FF0000; border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>(".$crawltdiv."%*)</td>";            
                    }
                else
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>&nbsp;</td>";            
                    }  
    //bookmark msn --------------------------------------------------------                           
                @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>-</b></td><td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;</td></tr>";
    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------           
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'>&nbsp;&nbsp;<b>".$language['yahoo']."</b>&nbsp;&nbsp;</td>";
    //links yahoo --------------------------------------------------------            
                if($crawltlinkyahoodb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltlinkyahoo-$crawltlinkyahoodb)/$crawltlinkyahoodb)*100),1);
                    }
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltlinkyahoo."</b></td>";      
                if($crawltlinkyahoo > $crawltlinkyahoodb)
                    {                            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#3CB371; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'>(+".$crawltdiv."%*)</td>"; 
                    }
                elseif($crawltlinkyahoo < $crawltlinkyahoodb) 
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#FF0000; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'>(".$crawltdiv."%*)</td>";            
                    }
                else
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399;  background-color: #E6E6FA;'>&nbsp;</td>";            
                    }
    //pages yahoo --------------------------------------------------------                            
                if($crawltpageyahoodb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltpageyahoo-$crawltpageyahoodb)/$crawltpageyahoodb)*100),1);
                    }
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>".$crawltpageyahoo."</b></td>";                 
                if($crawltpageyahoo > $crawltpageyahoodb)
                    {                            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#3CB371; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'>(+".$crawltdiv."%*)</td>"; 
                    }
                elseif($crawltpageyahoo < $crawltpageyahoodb) 
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#FF0000; border-bottom: 2px solid #003399;  background-color: #E6E6FA;'>(".$crawltdiv."%*)</td>";            
                    }
                else
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399;  background-color: #E6E6FA;'>&nbsp;</td>";            
                    }  
    //bookmark yahoo --------------------------------------------------------                           
                @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #E6E6FA;'><b>-</b></td><td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #E6E6FA;'>&nbsp;</td></tr>";
    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                                                          
                @$crawltmessage.="<tr><td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;&nbsp;<b>".$language['delicious']."</b>&nbsp;&nbsp;</td>";
    //links delicious --------------------------------------------------------            
                @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>-</b></td><td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>&nbsp;</td>"; 
    //pages delicious --------------------------------------------------------            
                @$crawltmessage.="<td style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>-</b></td><td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399;  background-color: #FFFFFF;'>&nbsp;</td>";            
    //bookmark delicious --------------------------------------------------------            
                if($crawltlinkdeliciousdb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltlinkdelicious-$crawltlinkdeliciousdb)/$crawltlinkdeliciousdb)*100),1);
                    }  
                @$crawltmessage.="<td  width='120px' style='text-align: center;  border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #FFFFFF;'><b>".$crawltlinkdelicious."</b></td>";
                if($crawltlinkdelicious > $crawltlinkdeliciousdb)
                    {                            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#3CB371; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'>(+".$crawltdiv."%*)</td>"; 
                    }
                elseif($crawltlinkdelicious < $crawltlinkdeliciousdb) 
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;  color:#FF0000; border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'>(".$crawltdiv."%*)</td>";            
                    }
                else
                    {            
                    @$crawltmessage.="<td style='text-align: center; font-size:12px;   border-bottom: 2px solid #003399; border-right: 2px solid #003399; background-color: #FFFFFF;'>&nbsp;</td>";            
                    }               
                @$crawltmessage.="</tr></table>";
                @$crawltmessage.="<div style='font-size:10px; color:#003399; text-align:center;'>* ".strtolower($language['beginmonth'])."</div><br><hr/>";
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                @$crawltmessage.="<div style='font-size:16px; color:#A52A2A; text-align:center;' />";            
                @$crawltmessage.="<b>".$language['hacking2']."</b><br/><br/>";
                @$crawltmessage.="</div>";                 
                @$crawltmessage.="<br><div style='font-size:14px; color:#003399; text-align:center;'><b>".$language['hacking3'].": ".$crawlthacking." ".$language['hacking']."</b></div><br>";
                @$crawltmessage.="<br><div style='font-size:14px; color:#003399; text-align:center;'><b>".$language['hacking4'].": ".$crawlthacking2." ".$language['hacking']."</b></div><br>"; 
                }
            else //case texte Email
                { 
                @$crawltmessage.="Site: ".$crawltsitename[$crawltsite]."-------------- ".$language['daily-stats'].": ". $crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday."\n\n"; 
                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n"; 
                @$crawltmessage.=$language['nbr_visits_crawler']."\n";
                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n\n";                 
               
            //ask crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------       
                @$crawltmessage.= $language['ask']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisitask."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisitaskdb."\n";
                if(($crawltmonthtoday-1)<10)
                    {
                    if($crawltmonthtoday==1)
                        {
                        $crawltlastmonth = 12;
                        }
                     else
                        {
                        $crawltlastmonth = "0".($crawltmonthtoday-1);
                        }
                    }
                    
                if($crawltvisitaskmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitaskdb-$crawltvisitaskmb)/$crawltvisitaskmb)*100),1);
                    } 
                if($crawltvisitaskdb > $crawltvisitaskmb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitaskdb <$crawltvisitaskmb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    }
                    
                if($crawltvisitasktm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitaskdb-$crawltvisitasktm)/$crawltvisitasktm)*100),1);
                    } 
                if($crawltvisitaskdb > $crawltvisitasktm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitaskdb <$crawltvisitasktm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                        
                @$crawltmessage.="\n\n"; 
                
               //google crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['google']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisitgoogle."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisitgoogledb."\n";

                if($crawltvisitgooglemb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitgoogledb-$crawltvisitgooglemb)/$crawltvisitgooglemb)*100),1);
                    } 
                if($crawltvisitgoogledb > $crawltvisitgooglemb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitgoogledb <$crawltvisitgooglemb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    } 
                    
                if($crawltvisitgoogletm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitgoogledb-$crawltvisitgoogletm)/$crawltvisitgoogletm)*100),1);
                    } 
                if($crawltvisitgoogledb > $crawltvisitgoogletm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitgoogledb <$crawltvisitgoogletm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                        
                @$crawltmessage.="\n\n";               
                
                //msn crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['msn']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisitmsn."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisitmsndb."\n";

                if($crawltvisitmsnmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitmsndb-$crawltvisitmsnmb)/$crawltvisitmsnmb)*100),1);
                    } 
                if($crawltvisitmsndb > $crawltvisitmsnmb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitmsndb <$crawltvisitmsnmb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    }

                 if($crawltvisitmsntm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitmsndb-$crawltvisitmsntm)/$crawltvisitmsntm)*100),1);
                    } 
                if($crawltvisitmsndb > $crawltvisitmsntm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitmsndb <$crawltvisitmsntm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                   
                       
                @$crawltmessage.="\n\n";                
                
                //yahoo crawler visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['yahoo']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisityahoo."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisityahoodb."\n";

                if($crawltvisityahoomb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisityahoodb-$crawltvisityahoomb)/$crawltvisityahoomb)*100),1);
                    } 
                if($crawltvisityahoodb > $crawltvisityahoomb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisityahoodb <$crawltvisityahoomb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    }  
                    
                 if($crawltvisityahootm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisityahoodb-$crawltvisityahootm)/$crawltvisityahootm)*100),1);
                    } 
                if($crawltvisityahoodb > $crawltvisityahootm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisityahoodb <$crawltvisityahootm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                      
                      
                @$crawltmessage.="\n\n";                  


                 //all crawlers visits----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['nbr_tot_visits']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisittotal."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisittotaldb."\n";

                if($crawltvisittotalmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisittotaldb-$crawltvisittotalmb)/$crawltvisittotalmb)*100),1);
                    } 
                if($crawltvisittotaldb > $crawltvisittotalmb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisittotaldb <$crawltvisittotalmb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    } 
                    
                 if($crawltvisittotaltm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisittotaldb-$crawltvisittotaltm)/$crawltvisittotaltm)*100),1);
                    } 
                if($crawltvisittotaldb > $crawltvisittotaltm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisittotaldb <$crawltvisittotaltm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                    
                       
                @$crawltmessage.="\n\n"; 
                


                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n"; 
                @$crawltmessage.=$language['nbr_tot_visit_seo']."\n";
                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n\n";                 
               
            //visitors send by ask-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------       
                @$crawltmessage.= $language['ask']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisitsendask."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisitsendaskdb."\n";

                if($crawltvisitsendaskmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendaskdb-$crawltvisitsendaskmb)/$crawltvisitsendaskmb)*100),1);
                    } 
                if($crawltvisitsendaskdb > $crawltvisitsendaskmb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendaskdb <$crawltvisitsendaskmb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    }
                    
                if($crawltvisitsendasktm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendaskdb-$crawltvisitsendasktm)/$crawltvisitsendasktm)*100),1);
                    } 
                if($crawltvisitsendaskdb > $crawltvisitsendasktm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendaskdb <$crawltvisitsendasktm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                         
                @$crawltmessage.="\n\n"; 
                
               //visitors send by google------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['google']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisitsendgoogle."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisitsendgoogledb."\n";

                if($crawltvisitsendgooglemb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendgoogledb-$crawltvisitsendgooglemb)/$crawltvisitsendgooglemb)*100),1);
                    } 
                if($crawltvisitsendgoogledb > $crawltvisitsendgooglemb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendgoogledb <$crawltvisitsendgooglemb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    } 
                if($crawltvisitsendgoogletm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendgoogledb-$crawltvisitsendgoogletm)/$crawltvisitsendgoogletm)*100),1);
                    } 
                if($crawltvisitsendgoogledb > $crawltvisitsendgoogletm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendgoogledb <$crawltvisitsendgoogletm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                     
                       
                @$crawltmessage.="\n\n";               
                
                //visitors send by msn----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['msn']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisitsendmsn."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisitsendmsndb."\n";

                if($crawltvisitsendmsnmb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendmsndb-$crawltvisitsendmsnmb)/$crawltvisitsendmsnmb)*100),1);
                    } 
                if($crawltvisitsendmsndb > $crawltvisitsendmsnmb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendmsndb <$crawltvisitsendmsnmb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    } 
                  if($crawltvisitsendmsntm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendmsndb-$crawltvisitsendmsntm)/$crawltvisitsendmsntm)*100),1);
                    } 
                if($crawltvisitsendmsndb > $crawltvisitsendmsntm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendmsndb <$crawltvisitsendmsntm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                    
                       
                @$crawltmessage.="\n\n";                
                
                //visitors send by yahoo--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['yahoo']."\n\n"; 
                @$crawltmessage.=$crawltdaytoday."/".$crawltmonthtoday."/".$crawltyeartoday.": ".$crawltvisitsendyahoo."\n";
                @$crawltmessage.=$language[$crawltmonthtoday]." ".$crawltyeartoday.": ".$crawltvisitsendyahoodb."\n";
 
                if($crawltvisitsendyahoomb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendyahoodb-$crawltvisitsendyahoomb)/$crawltvisitsendyahoomb)*100),1);
                    } 
                if($crawltvisitsendyahoodb > $crawltvisitsendyahoomb)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendyahoodb <$crawltvisitsendyahoomb) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language[$crawltlastmonth]." ".$crawltyeartoday.": = \n";            
                    }  
                 if($crawltvisitsendyahootm == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltvisitsendyahoodb-$crawltvisitsendyahootm)/$crawltvisitsendyahootm)*100),1);
                    } 
                if($crawltvisitsendyahoodb > $crawltvisitsendyahootm)
                    {                                                                          
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": +".$crawltdiv."%\n";           
                    }
                elseif($crawltvisitsendyahoodb <$crawltvisitsendyahootm) 
                    {
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": ".$crawltdiv."%\n";            
                    }
                else
                    {  
                    @$crawltmessage.=$language['evolution']." ".$language['lastthreemonths'].": = \n";            
                    }                     
                      
                @$crawltmessage.="\n\n";                 
                
                
                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n"; 
                @$crawltmessage.=$language['index']."\n";
                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n\n";                 
                   
                
                //links msn ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['msn']."\n\n"; 
                @$crawltmessage.=$language['nbr_tot_link'].": ".$crawltlinkmsn."\n";

                if($crawltlinkmsndb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltlinkmsn-$crawltlinkmsndb)/$crawltlinkmsndb)*100),1);
                    } 
                if($crawltlinkmsn > $crawltlinkmsndb)
                    {                                                                          
                    @$crawltmessage.=" +".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";           
                    }
                elseif($crawltlinkmsn <$crawltlinkmsndb) 
                    {
                    @$crawltmessage.=" ".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";            
                    }
                else
                    {  
                    @$crawltmessage.=" = ".strtolower($language['beginmonth'])."\n\n";            
                    }    
                 //pages msn--------------------------------------------------------------------------------------------------
                 @$crawltmessage.=$language['nbr_tot_pages_index'].": ".$crawltpagemsn."\n";

                if($crawltpagemsndb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltpagemsn-$crawltpagemsndb)/$crawltpagemsndb)*100),1);
                    } 
                if($crawltpagemsn > $crawltpagemsndb)
                    {                                                                          
                    @$crawltmessage.=" +".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";          
                    }
                elseif($crawltpagemsn <$crawltpagemsndb) 
                    {
                    @$crawltmessage.=" ".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";           
                    }
                else
                    {  
                    @$crawltmessage.=" = ".strtolower($language['beginmonth'])."\n\n";            
                    }    
                @$crawltmessage.="\n\n";                
                
                
                //links yahoo ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['yahoo']."\n\n"; 
                @$crawltmessage.=$language['nbr_tot_link'].": ".$crawltlinkyahoo."\n";

                if($crawltlinkyahoodb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltlinkyahoo-$crawltlinkyahoodb)/$crawltlinkyahoodb)*100),1);
                    } 
                if($crawltlinkyahoo > $crawltlinkyahoodb)
                    {                                                                          
                    @$crawltmessage.=" +".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";            
                    }
                elseif($crawltlinkyahoo <$crawltlinkyahoodb) 
                    {
                    @$crawltmessage.=" ".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";             
                    }
                else
                    {  
                    @$crawltmessage.=" = ".strtolower($language['beginmonth'])."\n\n";             
                    }    
                 //pages yahoo--------------------------------------------------------------------------------------------------
                 @$crawltmessage.=$language['nbr_tot_pages_index'].": ".$crawltpageyahoo."\n";

                if($crawltpageyahoodb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltpageyahoo-$crawltpageyahoodb)/$crawltpageyahoodb)*100),1);
                    } 
                if($crawltpageyahoo > $crawltpageyahoodb)
                    {                                                                          
                    @$crawltmessage.=" +".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";           
                    }
                elseif($crawltpageyahoo <$crawltpageyahoodb) 
                    {
                    @$crawltmessage.=" ".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";            
                    }
                else
                    {  
                    @$crawltmessage.=" = ".strtolower($language['beginmonth'])."\n\n";            
                    }    
                @$crawltmessage.="\n\n";                
                                
                //boolmark delicious ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
                @$crawltmessage.= $language['delicious']."\n\n"; 
                @$crawltmessage.=$language['nbr_tot_bookmark'].": ".$crawltlinkdelicious."\n";

                if($crawltlinkdeliciousdb == 0)
                    {
                    $crawltdiv="~";
                    }
                else
                    {
                    $crawltdiv= round(((($crawltlinkdelicious-$crawltlinkdeliciousdb)/$crawltlinkdeliciousdb)*100),1);
                    } 
                if($crawltlinkdelicious > $crawltlinkdeliciousdb)
                    {                                                                          
                     @$crawltmessage.=" +".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";           
                    }
                elseif($crawltlinkdelicious <$crawltlinkdeliciousdb) 
                    {
                     @$crawltmessage.=" ".$crawltdiv."% ".strtolower($language['beginmonth'])."\n\n";            
                    }
                else
                    {  
                    @$crawltmessage.=" = ".strtolower($language['beginmonth'])."\n\n";             
                    }                 
                @$crawltmessage.="\n\n";                 
                
                
                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n"; 
                @$crawltmessage.=$language['hacking2']."\n";
                @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n\n";                 
                       
                 
                $crawltmessage.=$language['hacking3'].": ".$crawlthacking." ".$language['hacking']."\n\n"; 
                $crawltmessage.=$language['hacking4'].": ".$crawlthacking2." ".$language['hacking']."\n\n";                  
                $crawltmessage.="--------------------------------------------------------------------------------------------------------------\n";
                $crawltmessage.="--------------------------------------------------------------------------------------------------------------\n";
                $crawltmessage.="--------------------------------------------------------------------------------------------------------------\n\n";
                }           
               
        }
if($crawltmailishtml==1)
    {
    @$crawltmessage.="<hr/>";
    @$crawltmessage.="<a href=\"".$url_crawlt."\"> ".$language['stat-access']."</a><br><br>";
    @$crawltmessage.="<div style='background-image:url(cid:fond); text-align:center; border-top:2px solid navy;'/>";
    @$crawltmessage.="<a href=\"http://www.crawltrack.fr\"><img src='cid:logo' alt='CrawlTrack' style='border:0'/></a>";
    @$crawltmessage.="</div>";
    @$crawltmessage.="</div>";
    }
else
    {
    @$crawltmessage.="--------------------------------------------------------------------------------------------------------------\n\n";                               
    @$crawltmessage.=$language['stat-access']." ".$url_crawlt."\n\n";
    @$crawltmessage.= $language['stat-crawltrack']." ";
    @$crawltmessage.="http://www.crawltrack.fr\n";
    }
    
//send the mail
if( $crawltcharset !=1)
    { 
     require("$crawltpath/phpmailer/class.phpmaileriso.php");
    } 
else
    {
    require("$crawltpath/phpmailer/class.phpmailer.php");
    }

$mail = new PHPMailer();

$mail->IsMail(); // telling the class to use Mail

//if you want to use smtp server comment the previous line and use the following ones:
/*
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = "smtp.email.com"; // SMTP server, put here the name of your server
*/

if($crawltmailishtml==1)
    {
    $mail->IsHTML(true);
    $mail->AddEmbeddedImage($crawltimage, 'banner', 'CrawlTrack');
    $mail->AddEmbeddedImage($crawltimage2, 'background', 'Background');
    $mail->AddEmbeddedImage($crawltimage3, 'fond', 'Backgroundsmall');
    $mail->AddEmbeddedImage($crawltimage4, 'logo', 'Logo');
    }
else
    {
    $mail->IsHTML(false);
    }
    
$mail->FromName = "CrawlTrack";
$mail->Subject = $language['mailsubject'];
$mail->Body = $crawltmessage;

$crawltemail= explode(',',$crawltdest);
foreach($crawltemail as $crawltaddress)
    {
    $mail->ClearAddresses();    
    $mail->AddAddress($crawltaddress);
    $mail->Send();
    }
   


?>