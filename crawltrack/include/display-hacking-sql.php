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
// file: display-hacking.php
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$listip=array();
$countrycode=array();
$nbrcountry=array();
$listcountry=array();
$totallistattack=array();
$listscript=array();
$listbadsite=array();
$totalscriptdisplay='';
$totalattackdisplay='';
$onlyarchive=0;
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


//mysql query-----------------------------------------------------------------------------------------------

//date for the mysql query
if($period>=10)
    {
    $datetolookfor=" date >'".sql_quote($daterequest)."' 
    AND  date <'".sql_quote($daterequest2)."'";    
    }
else
    {
    $datetolookfor=" date >'".sql_quote($daterequest)."'";
    }
//date format
if($period == 0 OR $period >= 1000)
    {
    $datequery= "DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600)), '%H&nbsp;hr&nbsp;%i&nbsp;mn')";   
    }
else
    {
    $datequery= "DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600)), '<b>%d/%m/%Y</b><br>%H&nbsp;hr&nbsp;%i&nbsp;mn')";    
    }
$sqlstats = "SELECT crawlt_crawler_id_crawler,  crawlt_ip_used, date,  url_page, $datequery FROM crawlt_visits, crawlt_pages
WHERE  (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20select\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20like\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20where\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
OR (crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND url_page LIKE '%\%20or\%20%' 
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."')
ORDER BY date";

     
$requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");
	
$nbrresult=mysql_num_rows($requetestats);

$testip=0;
if($nbrresult>=1)
	{
	//query to get crawler names
    $sql = "SELECT id_crawler,  crawler_name FROM crawlt_crawler";	
	$requete = mysql_query($sql, $connexion) or die("MySQL query error");
    while ($ligne = mysql_fetch_row($requete))                                                                              
        {
        $crawlername[$ligne[0]]=$ligne[1];
        }
        $crawlername[0]= $language['unknown'];
	
	//treatment of 1st query result
    while ($ligne = mysql_fetch_row($requetestats))                                                                              
        {
        if($ligne[1]!='')
            {
            ${'ipcrawler'.$crawlername[$ligne[0]]}[] =$ligne[1];
            $listip[$ligne[1]]=$ligne[1];
            @$nbrvisits[$ligne[1]]++; 
            ${'crawler'.$ligne[1]}[] =$crawlername[$ligne[0]];
            ${'page'.$ligne[1]}[] =$ligne[3];
            ${'date'.$ligne[1]}[] =$ligne[4];                                         
            }
        }        

    //query to get the country code

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
          
    foreach ($listip as $ip)
        {	
        //suppression of double entries in the tables
        ${'crawler'.$ip}=array_unique(${'crawler'.$ip});
        ${'page'.$ip}=array_unique(${'page'.$ip}); 
        ${'date'.$ip}=array_unique(${'date'.$ip}); 
        //country code                    
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
        }

    //query to get the attack=> script infos
    $sql = "SELECT attack, script FROM crawlt_attack WHERE type='sql'";
    $requete = mysql_query($sql, $connexion) or die("MySQL query error");
    $nbrresult3=mysql_num_rows($requete);
    if($nbrresult3>=1)
        {	
        while ($ligne = mysql_fetch_row($requete))
            {
            ${'attack'.$ligne[0]}[]=$ligne[1];               
            }
        }

     
    //group by attacker------------------------------------------------------------------------------------------- 
    
    /*definition of same attacker:
    -same sql query with the same crawler(s) and using the same type of url (length)
    and in the same period of time 
    (yes it's not 100% accurate but it's the better I have till now)
    */
    foreach ($nbrvisits as $crawlip => $value)
        {
        $listattack=array();
        $listcrawler=array();
        $pagetype2=0;
        $pagetype3=0;      
        foreach (${'page'.$crawlip} as $page)
            {
            crawltattacksql($page);
            //give a page type1
            $pagelength = floor(strlen($page)/10)*10;
            if($pagelength <51)
                {
                $pagetype1[]=1;
                }
            elseif($pagelength >50 && $pagelength <101)
                {
                $pagetype1[]=2;
                }
            elseif($pagelength >100 && $pagelength <151) 
                {
                $pagetype1[]=3;
                }
            elseif($pagelength >150 && $pagelength <201)         
                {
                $pagetype1[]=4;
                } 
            elseif($pagelength >200 && $pagelength <251)         
                {
                $pagetype1[]=5;
                }
            elseif($pagelength >250)         
                {
                $pagetype1[]=6;
                }                                      
            }
        //caculate the type1 page value
        $typepagevalue=max($pagetype1);
        $pagetype=array();
        //check the name of crawler used    
        foreach (${'crawler'.$crawlip} as $crawler)
            {
            $listcrawler[]=$crawler;            
            }
        //check the time period used            
        foreach (${'date'.$crawlip} as $datehacking)
            {
            if($period == 0 OR $period >= 1000)
                {
                //on a one day period we class per hour period
                $time = explode('hr',$datehacking);                
                $tabletime[]=intval($time[0]);
                }
            else
                {
                //on more than one day period we class per day
                $time = explode('/',$datehacking);
                $time2 = explode('>',$time[0]);
                $tabletime[]=intval($time2[1]);                
                }               
            } 
        $typeperiod= array_sum($tabletime)/count($tabletime);
        if($period == 0 OR $period >= 1000)
            {        
            if($typeperiod<=12)
                {
                $typeperiodvalue=1;
                }
            else
                {
                $typeperiodvalue=2;
                }
            }
         else
            {
            if($daytodaylocal>$daybeginlocal)
                {
                if($typeperiod<=((($daytodaylocal-$daybeginlocal)/2)+$daybeginlocal))
                    {
                    $typeperiodvalue=1;
                    }
                else
                    {
                    $typeperiodvalue=2;
                    }
                }
             else
                {
                if($typeperiod<=15)
                    {
                    $typeperiodvalue=1;
                    }
                else
                    {
                    $typeperiodvalue=2;
                    }
                }                
             }           
        $tabletime=array(); 
         
        //prepare the attacker value
        $listbadsite=array_unique($listbadsite); 
        $listcrawler=array_unique($listcrawler);             
        asort($listbadsite);       
        asort($listcrawler);        
        $attacker=$typepagevalue.serialize($listbadsite).serialize($listcrawler).$typeperiodvalue;
        
        //create the table of IP per attacker
        $ipproxy[$attacker]= @$ipproxy[$attacker]."-".$crawlip;
        }
      
    //count the number of attack per attacker   
    
    foreach ($ipproxy as $crawlip)
        {
        $crawlip =ltrim($crawlip,"-");
        $tableauip = explode('-',$crawlip);
        $listcrawler=array();
        $listattack=array();
        foreach($tableauip as $ip)
            {        
            $nbrvisits2[$crawlip]=@$nbrvisits2[$crawlip]+ $nbrvisits[$ip];
            }
             
        }
   
     //prepare the list of targeted script   
     $totallistattack =array_unique($totallistattack); 
      usort($totallistattack, "strcasecmp"); 
     $nbrparameters=0;  
    foreach( $totallistattack as $attack)
        {
        if(isset(${'attack'.$attack}))
            {
            if(is_array(${'attack'.$attack}))
                {
                ${'attack'.$attack}=array_unique(${'attack'.$attack});
                foreach( ${'attack'.$attack} as $script)
                    {
                    $listscript[]=$script;                    
                    }
                }
            else
                {
                $listscript[]='?';
                }   
            }
        else
            {
            $listscript[]='?';
            }
        if($attack!='?')
            {
            $attack=htmlentities($attack);            
            $totalattackdisplay.=$attack."<b> / </b>";
            $nbrparameters++;
            }            
            
            
        }        
         $listscript=array_unique($listscript);
         usort($listscript, "strcasecmp");
         $nbrscript=0;
         foreach($listscript as $script)
            {
            if($script!='?')
                {
                $script=htmlentities($script);
                $totalscriptdisplay.=$script."<b> / </b>";
                $nbrscript++;
                }
            }
        
        $totalscriptdisplay =rtrim($totalscriptdisplay,"<b> / </b>");
        $totalattackdisplay =rtrim($totalattackdisplay,"<b> / </b>");
        
    //prepare datas for the summary table
     $nbrattacker=count($nbrvisits2); 
     $nbrattack=array_sum($nbrvisits2);
     $nbrip= count($listip); 
	//display---------------------------------------------------------------------------------------------------------
    echo"<div class=\"content\">\n";
    echo crawltbackforward('hacking4',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);  
    echo"</div>\n";


    //summary table display
    echo"<div class='tableau' align='center' onmouseout=\"javascript:montre();\">\n";	
    echo"<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
    echo"<tr><th class='tableau1' >\n";
    echo"".$language['hacker']."\n";
    echo"</th>\n";
    echo"<th class='tableau1' >\n";
    echo"".$language['hacking']."\n";
    echo"</th>\n";    		
    echo"<th class='tableau2'>\n";
    echo"".$language['crawler_ip_used']."\n";
    echo"</th></tr>\n";
    echo"<tr><td class='tableau3'>".numbdisp($nbrattacker)."</td>\n";
    echo"<td class='tableau3'>".numbdisp($nbrattack)."</td>\n";    	
    echo"<td class='tableau5'>".numbdisp($nbrip)."</td></tr>\n";
    	
    echo"</table></div>\n";
    if($crawltblockattack==1)
      {
      echo"<h2>".$language['attack-blocked']."</h2>\n";
      }
    else
      {
      echo"<h2><span class=\"alert2\">".$language['attack-no-blocked']."</span></h2>\n";
      }
     if($period != 5)
        {
        //graph
        echo"<div class='graphvisits' >\n";    
        //mapgraph
        include"include/mapgraph.php";
        echo"<img src=\"./graphs/visit-graph.php?crawltlang=$crawltlang&period=$period&navig=$navig&graphname=$graphname\" USEMAP=\"#visit\" alt=\"graph\" width=\"700\" heigth=\"300\"  border=\"0\"/>\n";
        echo"</div>\n";
        echo"<div class='imprimgraph'>\n";       
        echo"&nbsp;<br><br><br><br><br><br><br><br></div>\n"; 
        }
    if($nbrscript != 0)
        {
        echo"<br><div class='tableaularge' align='center'>\n";	
        echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";    
        echo"<tr><td class='tableau23'>\n";
        echo"<div class='alert'><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >".$language['danger']." :</div><div class='scriptdisplay'>".$totalscriptdisplay."</div><br>\n";    
        echo"</td></tr>\n";    
        echo"</table></div>\n"; 
        }
    if($nbrparameters != 0)
        {
        echo"<br><div class='tableaularge' align='center'>\n";	
        echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";    
        echo"<tr><td class='tableau23'>\n";
        echo"<div class='alert'><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >".$language['attack_sql']." :</div><div class='scriptdisplay'>".$totalattackdisplay."</div><br>\n";    
        echo"</td></tr>\n";    
        echo"</table></div>\n"; 
        }        
        
        
        
        
    //change text if more than x attack	and display limited (value of x can be change in function.php,,it's displaynumber)
    if($nbrattack>=$rowdisplay && $displayall=='no')
        {
        echo"<h2>";
        printf($language['attack_number_display'],$rowdisplay);
        echo"<br>\n";
         $crawlencode = urlencode($crawler);
        echo"<span class=\"smalltext\"><a href=\"index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&order=$order&displayall=yes&graphpos=$graphpos\">".$language['show_all']."</a></span></h2>";
        }
    else
        {
        echo"<h2>".$language['attack_detail']."</h2>\n";
        } 
     
        
    echo"<div class='tableaularge' align='center'>\n";	
    echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";    
    echo"<tr><th class='tableau1'>\n";
    echo"".$language['crawler_ip_used']."\n";
    echo"</th>\n";
    echo"<th class='tableau1'>\n";
    echo"".$language['crawler_name']."\n";    		
    echo"</th>\n";
    echo"<th class='tableau1'>\n";
    echo"".$language['hacking']."\n";		
    echo"</th>\n";	
    echo"<th class='tableau1'>\n";		
    echo"".$language['date_hacking']."\n";
    echo"</th>\n";            	
    echo"<th class='tableau2'>\n";		
    echo"".$language['attack_detail']."\n";
    echo"</th></tr>\n";
   
       
    //counter for alternate color lane
    $comptligne=2;

    //sort by number of tentatives    
    arsort($nbrvisits2);    
    $i=0;   
    foreach ($nbrvisits2 as $crawlip=>$value)
        {
        if(($displayall=='no' && $i<$rowdisplay) OR  $displayall=='yes')
            {
            $i++;
          //Initialize array & variables
            $ipdisplay="";
            $urldisplay="";
            $datedisplay="";
            $crawldisplay="";
            $badsitedisplay="";
            $attackdisplay="";
            $scriptdisplay="";
            $listcrawler=array();
            $listbadsite=array();
            $listattack=array();
            $listscript=array();
            $tabledatedisplay=array();
            $tabledatedisplay2=array();
            $tableurldisplay=array();
            $listday=array();
            $crawlip =ltrim($crawlip,"-");
            $tableauip = explode('-',$crawlip);
            sort($tableauip);
            //prepare data for display (group by attacker)
            foreach($tableauip as $ip)
                {
                //prepare list of crawlers name          
                foreach (${'crawler'.$ip} as $crawler)
                    {
                    $listcrawler[]=htmlentities($crawler);
                    }
                
                //prepare details of attacks
                foreach (${'page'.$ip} as $page)
                    {
                    crawltattacksql($page);
                    }
                 
                //prepare time of attack              
                foreach (${'date'.$ip} as $datehacking)
                    {
                    $tabledatedisplay[]=$datehacking;
                    } 
                 //prepare IP used   
                 if (isset($countrycode[$ip]))
                    {
                    $code=$countrycode[$ip];
                    $ipdisplay.="&nbsp;".$ip."&nbsp;<a href=\"http://www.whois-search.com/whois/".$ip."\" target=\"blank\"><span class=\"noprint\"><img src=\"./images/report_user.png\" width=\"16\" height=\"16\" border=\"0\" ></a>&nbsp;</span><br>&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
                    }
                else
                    {
                    $ipdisplay.="&nbsp;".$ip."&nbsp;<a href=\"http://www.whois-search.com/whois/".$ip."\" target=\"blank\"><span class=\"noprint\"><img src=\"./images/report_user.png\" width=\"16\" height=\"16\" border=\"0\" ></a>&nbsp;</span><br>&nbsp;????<br>\n";
                    }                          
                }
            $tabledatedisplay=array_unique($tabledatedisplay);
            sort($tabledatedisplay);
            
            //to group per day when not 1 day period
             if($period == 0 OR $period >= 1000)
                {
                $datedisplay=implode("<br>",$tabledatedisplay);    
                }
            else
                {
                 foreach ($tabledatedisplay as $datehacking)
                    {       
                    $day=explode("<br>",$datehacking);
                    if(in_array($day[0],$listday))
                        {
                        $tabledatedisplay2[]=$day[1];
                        }
                    else
                        {
                        $tabledatedisplay2[]=$datehacking;
                        }                    
                    $listday[]=$day[0];                           
                    }
                
                  $datedisplay=implode("<br>",$tabledatedisplay2); 
                }
            
            
            
            $tableurldisplay=array_unique($tableurldisplay);
            sort($tableurldisplay);
            $urldisplay=implode("<br>--------------------------------------------------------------------------------------------<br>",$tableurldisplay);         
    
            
            $listcrawler=array_unique($listcrawler);
            $listbadsite=array_unique($listbadsite);
            $listattack=array_unique($listattack);        
            $crawldisplay=implode("<br>",$listcrawler);
            $crawldisplay=ltrim($crawldisplay,"<br>");
            
            foreach( $listbadsite as $badsite)
                {
                $badsitedisplay.=crawltcuturl(urldecode($badsite),'75')."<br>";
                }            
            
            $firsttime=0;
             usort($listattack, "strcasecmp");   
            foreach( $listattack as $attack)
                {
                if(isset(${'attack'.$attack}))
                    {
                    if(is_array(${'attack'.$attack}))
                        {
                        ${'attack'.$attack}=array_unique(${'attack'.$attack});
                        foreach( ${'attack'.$attack} as $script)
                            {
                            $listscript[]=$script;                    
                            }
                        }
                    elseif($firsttime==0)
                        {
                        $listscript[]='?';
                        $firsttime=1;
                        }   
                    }
                elseif($firsttime==0)
                    {
                    $listscript[]='?';
                    $firsttime=1;
                    }
                if($attack !='')
                    {
                    $attackdisplay.=crawltcuturl($attack,'75')."<br>";
                    }
                }
             $listscript=array_unique($listscript);
             usort($listscript, "strcasecmp");
             $detect=0;
             foreach($listscript as $script)
                {
                if($script=='?')
                    {
                    $detect=1;
                    }
                else
                    {
                    $scriptdisplay.=crawltcuturl($script,'75')."<br>";
                    }
                }
                if($detect==1)
                    {
                    $scriptdisplay.="?<br>";
                    }               
                                       
            //table display
            if ($comptligne%2 ==0)
                {               
                echo"<tr><td class='tableau3hsg'>$ipdisplay</td> \n";
                echo"<td class='tableau3hsf'>&nbsp;".$crawldisplay."&nbsp;</td>\n";                                   
                echo"<td class='tableau3hsf' >$nbrvisits2[$crawlip]</td>\n";
                echo"<td class='tableau3hsf' width='10%'>".$datedisplay."</td>\n";              
                echo"<td id='tableau5vsf' width='50%'><b><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >".$language['danger'].": </b><br>".$scriptdisplay."<br><b>".$language['attack_sql'].": </b><br>".$attackdisplay."<br><b>".$language['bad_sql'].": </b><br>".$badsitedisplay."<br><b>".$language['bad_url'].":</b><br><a href=\"#\">".$urldisplay."</a><br>&nbsp;</td></tr>\n";                  
                }
            else
                {               
                echo"<tr><td class='tableau30hsg'>$ipdisplay</td> \n";
                echo"<td class='tableau30hsf'>&nbsp;".$crawldisplay."&nbsp;</td>\n";            
                echo"<td class='tableau30hsf' >$nbrvisits2[$crawlip]</td>\n";               			
                echo"<td class='tableau30hsf' width='10%'>".$datedisplay."</td>\n";                
                echo"<td id='tableau50vsf' width='50%'><b><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >".$language['danger'].": </b><br>".$scriptdisplay."<br><b>".$language['attack_sql'].": </b><br>".$attackdisplay."<br><b>".$language['bad_sql'].": </b><br>".$badsitedisplay."<br><b>".$language['bad_url'].":</b><br><a href=\"#\">".$urldisplay."</a><br>&nbsp;</td></tr>\n";                  
                }			
            
            $comptligne++;
            }                
        }

    echo"</table>\n"; 
           
    echo"<br>\n"; 	
	}
else //case no visits
	{
    echo"<div class=\"content\">\n";
	echo crawltbackforward('hacking4',$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);      
	echo"</div>\n";
    echo"<div class='tableaularge' align='center'>\n";		
	echo"<h1>".$language['no_hacking']."</h1>\n";
	echo"<br>\n";	
	}

	

?>