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
// file: display-crawlers-info.php
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$listcrawler=array();
$listip=array();
$usercrawler=array();
$countrycode=array();
$nbrcountry=array();
$listcountry=array();
$name=array();
$nbrcountry2=array();
$name2=array();
$values=array();

$cachename=$navig.$period.$site.$firstdayweek.$localday.$graphpos.$crawltlang;

//start the caching if fopen exist
if(function_exists('fopen'))
    {
    cache($cachename);
    }

//database connection
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//include menu 
include"include/menumain.php";
include"include/menusite.php";
include"include/timecache.php";
//mysql requete

if($period>=10)
    {	
    $sqlstats = "SELECT crawler_name,  crawlt_ip_used, date, crawler_info FROM crawlt_crawler, crawlt_visits
    WHERE  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
    AND crawlt_visits.date >'".sql_quote($daterequest)."'
    AND  date <'".sql_quote($daterequest2)."'     
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'";
    }
elseif($period==5)
    {
    $sqlstats = "SELECT code, count(DISTINCT id_visit), count(DISTINCT crawler_name) FROM crawlt_crawler, crawlt_visits, crawlt_ip_used
    WHERE  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  crawlt_visits.crawlt_ip_used = crawlt_ip_used.ip_used    
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    GROUP BY code";    
    }       
else
    {
    $sqlstats = "SELECT crawler_name,  crawlt_ip_used, date, crawler_info FROM crawlt_crawler, crawlt_visits
    WHERE  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
    AND crawlt_visits.date >'".sql_quote($daterequest)."' 
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'";
    }
        
$requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");
	
$nbrresult=mysql_num_rows($requetestats);

$testip=0;
if($nbrresult>=1)
	{	
    while ($ligne = mysql_fetch_row($requetestats))                                                                              
        {
        if($period !=5)
            {
            if($ligne[1]!='')
                {
                $testip=1;
                $listcrawler[$ligne[0]]=$ligne[0];
                ${'ipcrawler'.$ligne[0]}[] =$ligne[1];
                $listip[$ligne[1]]=$ligne[1];
                @${'nbrvisits'.$ligne[0]}[$ligne[1]]++;
                ${'crawler'.$ligne[1]}[] =$ligne[0]; 
                $usercrawler[$ligne[0]] = $ligne[3];                  
                }
            }
        else
            {
            $testip=1;
            if($ligne[0]=='a2' OR $ligne[0]=='xx')
                {
                $nbrvisits['xx']=$ligne[1]+@$nbrvisits['xx'];
                $nbrcountry['xx']=$ligne[2]+@$nbrcountry['xx'];
                }
            else
                {
                $nbrvisits[$ligne[0]]=$ligne[1];
                $nbrcountry[$ligne[0]]=$ligne[2];
                }
            }
        }        

        
if($testip==1)
    {
    if($period !=5)
        {
        //requete to get the country code
    
        $testexist=0;
        
        //get the existing datas in the crawlt_ip_used table
        $sqlexistingip = "SELECT ip_used, code FROM crawlt_ip_used";
        
        $requeteexistingip = mysql_query($sqlexistingip, $connexion) or die("MySQL query error");

        $nbrresultexistingip=mysql_num_rows($requeteexistingip);
        
        if($nbrresultexistingip>=1)
            {
            while ($ligneip = mysql_fetch_row($requeteexistingip))
                {
                $countrycode[$ligneip[0]]=$ligneip[1];                
                }
            }        
        
             
           $j=0;
               
        foreach ($listip as $ip)
            {
            //to detect same IP used by different crawler
            //suppression of double entries in the tables
            ${'crawler'.$ip}=array_unique(${'crawler'.$ip});
            sort(${'crawler'.$ip});				
                
            if(!isset($countrycode[$ip]))
                {
                $ipexplode= explode('.',$ip);            
          
                //maxMind GeoIp calculation formula						
                $ip2=(16777216*$ipexplode[0]) + (65536*$ipexplode[1]) + (256*$ipexplode[2]) + $ipexplode[3];
                
                $sqlstats = "SELECT country_code FROM crawlt_ip_data
                WHERE ip_from <= '".sql_quote($ip2)."'
                AND ip_to >= '".sql_quote($ip2)."'";
                
                $requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");
        
                $nbrresult1=mysql_num_rows($requetestats);
                
                if($nbrresult1>=1)
                    {	
                    $ligne = mysql_fetch_row($requetestats);
                    $countrycode[$ip]=$ligne[0];
                    @$nbrcountry[$ligne[0]]++;
                    $listcountry[]=$ligne[0];
                    }
                else
                    {
                    $countrycode[$ip]='xx';
                    }  
              
              //enter the ip data in the crawlt_ip_used table
              
              
                $sqlinsertip = "INSERT INTO crawlt_ip_used (ip_used, code) VALUES ('".sql_quote($ip)."','".sql_quote($countrycode[$ip])."')";
                $requeteinsertip = mysql_query($sqlinsertip, $connexion) or die("MySQL query error");  
                      
                  } 
             else
                {
                $code=$countrycode[$ip];
                @$nbrcountry[$code]++;
                $listcountry[]=$code;                
                }    
              $j++;       
            }
        }

    //treatment to prepare the datas for the graph and to display the 5 top and group the other in the 'Other' category
    arsort($nbrcountry);

    foreach ($nbrcountry as $key => $value)
      {
      $name[] = $key;
      } 	
    
    $nbrtotcountry=count($nbrcountry);
    
    $i=0;
    foreach ($nbrcountry as $nbr)
        {
            if($i > 4 && $nbrtotcountry>6 )
            {
            $crawler=$name[$i];
            $crawler3=$language['other'];
            @$nbrcountry2[$crawler3]= @$nbrcountry2[$crawler3]+$nbrcountry[$crawler];
            }
        else
            {
            $crawler=$name[$i];		
            @$nbrcountry2[$crawler]= $nbrcountry[$crawler];
            }
        $i++;
        }
    foreach ($nbrcountry2 as $key => $value)
      {
      $name2[] = $key;
      } 
    $i=0;	
    foreach ($nbrcountry2 as $nbr2)
        {

        if($name2[$i]==$language['other'])
            {
            $values[$language['other']]=$nbr2;
            }
        else
            {
            $values[$country[$name2[$i]]]=$nbr2;
            }
        $i++;
        }         

    //prepare datas to be transfert to graph file
    $datatransferttograph=addslashes(urlencode(serialize($values)));
    //insert the values in the graph table 
    $graphname="origin-".$cachename;
    
       
    //check if this graph exit already in the table     
    $sql = "SELECT name  FROM crawlt_graph
                WHERE name= '".sql_quote($graphname)."'";
                
    
    $requete = mysql_query($sql, $connexion) or die("MySQL query error");
    $nbrresult=mysql_num_rows($requete);
    if($nbrresult >=1)
        {     
        $sql2 ="UPDATE crawlt_graph SET graph_values='".sql_quote($datatransferttograph)."'
                  WHERE name= '".sql_quote($graphname)."'";
        }
    else
        {
        $sql2 ="INSERT INTO crawlt_graph (name,graph_values) VALUES ( '".sql_quote($graphname)."','".sql_quote($datatransferttograph)."')";        
        }    
    $requete2 = mysql_query($sql2, $connexion) or die("MySQL query error");      
    //mysql connexion close
    mysql_close($connexion);        
        
	//display---------------------------------------------------------------------------------------------------------
    echo"<div class=\"content\">\n";
    echo crawltbackforward('origin',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);  
    echo"</div>\n";
              
    //graph
    echo"<div align='center'onmouseover=\"javascript:montre();\">\n";
    echo"<img src=\"./graphs/origine-graph.php?graphname=$graphname\" alt=\"graph\"  width=\"450px\" heigth=\"175px\"/>\n";
    echo"</div>\n";
    if($period != 5)
        {
        //order per crawler name
        asort($listcrawler);    
        
            echo"<div class='tableau' align='center'>\n";	
            echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
            echo"<tr><th class='tableau1'>\n";
            echo"".$language['crawler_name']."\n";
            echo"</th>\n";
            echo"<th class='tableau1'>\n";
            echo"".$language['crawler_ip_used']."\n";		
            echo"</th>\n";
            echo"<th class='tableau1'>\n";
            echo"".$language['nbr_visits']."\n";		
            echo"</th>\n";		
            echo"<th class='tableau2'>\n";		
            echo"".$language['crawler_country']."\n";
            echo"</th></tr>\n";
            
            //counter for alternate color lane
            $comptligne=2;
            
            foreach ($listcrawler as $crawl)
                {
                $crawldisplay=htmlentities($crawl);
                //suppression of double entries in the tables
                ${'ipcrawler'.$crawl}=array_unique(${'ipcrawler'.$crawl});
                sort(${'ipcrawler'.$crawl});
            
            
                if ($comptligne%2 ==0)
                    {
                    echo"<tr><td class='tableau3h'><a href='index.php?navig=2&amp;period=".$period."&amp;site=".$site."&amp;crawler=".$crawl."&amp;graphpos=".$graphpos."'>".$crawldisplay."</a></td>\n";
                    echo"<td class='tableau3g' width='20%'>\n";
                    foreach (${'ipcrawler'.$crawl} as $ip)
                        {
                        $nbip=count(${'crawler'.$ip});
                        if ($nbip > 1)
                            {
                            //test to see in case of different crawlers using the same ip if the owner is the same
                            for ($i=0; $i<$nbip; $i++)
                                {
                                ${'difuser'.$ip}[] = $usercrawler[${'crawler'.$ip}[$i]];
                                }
                            ${'difuser'.$ip} = array_unique(${'difuser'.$ip});
                            
                            $nbuser=count(${'difuser'.$ip});
                            
                            if($nbuser > 1)
                                {
                                $teststrangeip = 1;
                                }
                            else
                                {
                                $teststrangeip = 0;
                                }
                            }
                        else
                            {
                            $teststrangeip = 0;
                            }
                        
                        
                        if ($teststrangeip == 1)
                            {								
                            echo"&nbsp;&nbsp;&nbsp;<span class='red'>$ip&nbsp;<a href='index.php?navig=6&amp;iptosuppress=".$ip."&amp;period=".$period."&amp;site=".$site."&amp;validform=19&amp;suppressip=1&amp;graphpos=".$graphpos."'>???</a></span><br>\n";
                            }
                         else
                            {
                            echo"&nbsp;&nbsp;&nbsp;$ip<br>\n";
                            }                        
                        }
                    echo"</td>\n";
                    echo"<td class='tableau3' >\n";
                    foreach (${'ipcrawler'.$crawl} as $ip)
                        {	
                        
                                
                        echo"".numbdisp(${'nbrvisits'.$crawl}[$ip])."<br>\n";
                        }
                    echo"</td>\n";					
                    echo"<td class='tableau5g' width='25%'>\n";
                    foreach (${'ipcrawler'.$crawl} as $ip)
                        {
                        if (isset($countrycode[$ip]))
                            {
                            $code=$countrycode[$ip];
                            echo"&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
                            }
                        else
                            {
                            echo"&nbsp;&nbsp;&nbsp;????<br>\n";
                            }
                        }				
                    echo"</td></tr> \n";			
    
                    }
                else
                    {
                    echo"<tr><td class='tableau30h'><a href='index.php?navig=2&amp;period=".$period."&amp;site=".$site."&amp;crawler=".$crawl."&amp;graphpos=".$graphpos."'>".$crawldisplay."</a></td>\n";
                    echo"<td class='tableau30g' width='20%'>\n";
                    foreach (${'ipcrawler'.$crawl} as $ip)
                        {			
                        $nbip=count(${'crawler'.$ip});
                        if ($nbip > 1)
                            {
                            //test to see in case of different crawlers using the same ip if the owner is the same
                            for ($i=0; $i<$nbip; $i++)
                                {
                                ${'difuser'.$ip}[] = $usercrawler[${'crawler'.$ip}[$i]];
                                }
                            ${'difuser'.$ip} = array_unique(${'difuser'.$ip});
                           
                            $nbuser=count(${'difuser'.$ip});
                            
                            if($nbuser > 1)
                                {
                                $teststrangeip = 1;
                                }
                            else
                                {
                                $teststrangeip = 0;
                                }
                            }
                        else
                            {
                            $teststrangeip = 0;
                            }
                        
                        
                        if ($teststrangeip == 1)
                            {								
                            echo"&nbsp;&nbsp;&nbsp;<span class='red'>$ip&nbsp;<a href='index.php?navig=6&amp;iptosuppress=".$ip."&amp;period=".$period."&amp;site=".$site."&amp;validform=19&amp;suppressip=1&amp;graphpos=".$graphpos."'>???</a></span><br>\n";
                            }
                         else
                            {
                            echo"&nbsp;&nbsp;&nbsp;$ip<br>\n";
                            } 
                        }
                    echo"</td>\n";
                    echo"<td class='tableau30' >\n";
                    foreach (${'ipcrawler'.$crawl} as $ip)
                        {			
                        echo"".numbdisp(${'nbrvisits'.$crawl}[$ip])."<br>\n";
                        }
                    echo"</td>\n";				
                    echo"<td class='tableau50g' width='25%'>\n";
                    foreach (${'ipcrawler'.$crawl} as $ip)
                        {			
                        if (isset($countrycode[$ip]))
                            {
                            $code=$countrycode[$ip];
                            echo"&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
                            }
                        else
                            {
                            echo"&nbsp;&nbsp;&nbsp;????<br>\n";
                            }
                        }				
                    echo"</td></tr> \n";				
                    }				
                
                $comptligne++;
                                
                }
        
            echo"</table>\n";
            }
        else
            {
            //order per country code
            arsort($nbrvisits); 
               
           foreach ($nbrvisits as $key => $value)
              {
              $listcountry[] = $key;
              } 
            
            echo"<div class='tableau' align='center'>\n";	
            echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
            echo"<tr><th class='tableau1'>\n";
            echo"".$language['crawler_country']."\n";
            echo"</th>\n";
            echo"<th class='tableau1'>\n";
            echo"".$language['nbr_visits']."\n";		
            echo"</th>\n";		
            echo"<th class='tableau2'>\n";		
            echo"".$language['nbr_tot_crawlers']."\n";
            echo"</th></tr>\n";
            
            //counter for alternate color lane
            $comptligne=2;            
            foreach ($listcountry as $code)
                {            
                if ($comptligne%2 ==0)
                    {
                    echo"<td class='tableau3g'>&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]</td>\n";
                    echo"<td class='tableau3'>".numbdisp($nbrvisits[$code])."</td>\n"; 
                    echo"<td class='tableau5'>".numbdisp($nbrcountry[$code])."</td></tr>\n";
                    }
                else
                    {
                    echo"<td class='tableau30g'>&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]</td>\n";
                    echo"<td class='tableau30'>".numbdisp($nbrvisits[$code])."</td>\n"; 
                    echo"<td class='tableau50'>".numbdisp($nbrcountry[$code])."</td></tr>\n";
                    }
                  $comptligne++;   
                }
              echo"</table>\n";  
            }
        
        echo"<br>\n";
        echo"<p align='center'><span class='smalltext'>".$language['maxmind']." <a href='http://maxmind.com'>http://maxmind.com</a></span></p>\n";
        
        }
    else
        {
        //case no ip in the visit table (upgrade to 1.50)        
        echo"<div align='center'>\n";    
        echo crawltbackforward('origin',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);
        echo"<h1>".$language['no_ip']."</h1>\n";
        echo"<br>\n";        
        }
	}
else //case no visits
	{
	echo"<div align='center'>\n";
	echo crawltbackforward('origin',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);      
	echo"<h1>".$language['no_visit']."</h1>\n";
	echo"<br>\n";
	}

	

?>