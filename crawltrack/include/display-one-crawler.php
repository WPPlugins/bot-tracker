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
// file: display-one-crawler.php
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

//initialize array
$listpage=array();
$nbvisits=array();
$lastdate1=array();
$address=array();
$info=array();
$agent=array();
$ip=array();
$uagent=array();
$table=array();

$crawlencode=urlencode($crawler);
$cachename=$navig.$period.$site.$order.$crawlencode.$displayall.$firstdayweek.$localday.$graphpos.$crawltlang;

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
//order case    
if($order==0)
    {	
    //case date
    $orderby="maxdate DESC";    
    }
elseif($order==2 OR $order==1 OR $order==4)
    {
    //case visits
    $orderby="maxvisites DESC";                   
    }
elseif($order==3)
    {
    //case crawlers
    $orderby="url_page ASC";		
    }
//date format
if($period == 0 OR $period >= 1000)
    {
    $datequery= "DATE_FORMAT(MAX(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600))), '%H&nbsp;hr&nbsp;%i&nbsp;mn')";
    }
else
    {
    $datequery= "DATE_FORMAT(MAX(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600))), '%d/%m/%Y<br>%H&nbsp;hr&nbsp;%i&nbsp;mn')";    
    }
//limite to 
if($displayall=='no')
    {
    $limitquery='LIMIT '.$rowdisplay;
    }
else
    {
    $limitquery='';
    }
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


//mysql requete
    
 //requete to count the number of crawler per page and to list the page viewed and to count the number of visits per page and to have the date of last visit for each pages	      
$sqlstats = "SELECT  url_page,   COUNT(DISTINCT id_visit) as maxvisites,
MAX(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y-%m-%d %H:%i:%s')) as maxdate,
$datequery 
FROM crawlt_visits, crawlt_crawler, crawlt_pages
WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
AND  crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
AND $datetolookfor    
AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
AND crawlt_crawler.crawler_name='".sql_quote($crawler)."'   
GROUP BY crawlt_pages_id_page
ORDER BY $orderby 
$limitquery";
    	
$requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error1");


$nbrresult=mysql_num_rows($requetestats);
if($nbrresult>=1)
	{
    $onlyarchive=0;
     
    while ($ligne = mysql_fetch_row($requetestats))                                                                              
        {
        $nbvisits[$ligne[0]]=$ligne[1];
        $lastdatedisplay[$ligne[0]]=$ligne[3];
        }
  		
    	
	
	//requete to have the crawler data

    $sqlstats2 = "SELECT DISTINCT crawlt_crawler_id_crawler as robot, crawler_url, crawler_info, crawler_user_agent, crawler_ip, COUNT(DISTINCT id_visit), COUNT(DISTINCT crawlt_pages_id_page) FROM crawlt_visits,crawlt_crawler 
    WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    AND  $datetolookfor          
    AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
    AND crawlt_crawler.crawler_name='".sql_quote($crawler)."'
    GROUP BY robot";

	
    $requetestats2 = mysql_query($sqlstats2, $connexion) or die("MySQL query error2");
        $nbrtotvisits=0;
        $nbrtotpages=0;
    
    while ($ligne = mysql_fetch_row($requetestats2))                                                                              
        {
        $nbrtotvisits = $nbrtotvisits + $ligne[5];
        $nbrtotpages = $nbrtotpages + $ligne[6];
        $address=$ligne[1];
        $info=$ligne[2];
        $agent=$ligne[3];
        $ip=$ligne[4];  

        if($agent!='')
            {
            $uagent[]=$agent;
            }
        if($ip!='')
            {
            $uagent[]=$ip;
            }
            
            	
        } 
        
         //check if there is datas in archive
    $usearchive=0;
    if($period==3 OR ($period>=200 && $period<300))
        {
        $beginperiod =  mktime(0,0,0,$monthbeginserver, 1, $yearbeginserver);        
        
        //data request
        $sqlarchive="SELECT mois, nbr_visits, pages_view FROM crawlt_archive";
        
        $requetearchive = mysql_query($sqlarchive, $connexion) or die("MySQL query error");
            
        $nbrresult=mysql_num_rows($requetearchive);
        if($nbrresult>=1)
            {
            while ($ligne = mysql_fetch_row($requetearchive))                                                                              
                {
                $sitetodisplay = explode('-',$ligne[0]);
                if($sitetodisplay[1]==$site)
                    {
                    $data=$sitetodisplay[0];
                    $archivdate=explode('/',$data);
                    $archivperiod =mktime(0,0,0,$archivdate[0], 1, $archivdate[1]);
                    if($archivperiod >=$beginperiod)
                        {
                        $usearchive=1;
                        }

                    }
                }
            }
                
        }		

               
	//display--------------------------------------------------------------------------------------------------
	
    $crawlerdisplay=htmlentities($crawler);
    $addressdisplay=htmlentities($address);
    $infodisplay=htmlentities($info);
    echo"<br><br><div class=\"content\">\n";
    echo crawltbackforward($crawlerdisplay,$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);
        
    //ua table
    echo"<div class='tableau' align='center' onmouseover=\"javascript:montre();\">\n";	
    echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";	
    echo"<tr><th class='tableau1'>\n";
    echo"".$language['user_agent_or_ip']."\n";
    echo"</th>\n";
    echo"<th class='tableau2'>\n";
    echo"".$language['Origin']."\n";
    echo"</th></tr>\n";

    $nbline=sizeof($uagent);
    $nb=0;
    foreach ($uagent as $ua)
        {
        $uadisplay=htmlentities($ua);
        echo"<tr><td class='tableau3'>".$uadisplay."</td>\n";
        if($nb==0)
            {
            echo"<td class='tableau5' rowspan=".$nbline."><a href=\"$addressdisplay\">".$infodisplay."</a></td></tr>\n";
            }
        else
            {
            echo"</tr>\n";
            }	
        $nb=2;	
        }
    echo"</table></div><br>\n";    
    
        
    echo"</div>\n";
    

      //graph  
    echo"<div align='center'>\n";
    echo"<img src=\"./graphs/page-graph.php?nbrpageview=$nbrtotpages&amp;nbrpagestotal=$nbrpagestotal[$site]&amp;crawltlang=$crawltlang\" alt=\"graph\"  width=\"500\" heigth=\"175\"/>\n";
    echo"</div>\n";	 

 

    
    echo"<div class='tableau' align='center'>\n";	
    echo"<table   cellpadding='0px' cellspacing='0' width='550px'>\n";	
    echo"<tr><th class='tableau1'>\n";
    echo"".$language['nbr_tot_visits']."\n";
    echo"</th>\n";
    echo"<th class='tableau1'>\n";
    echo"".$language['nbr_tot_pages']."\n";
    echo"</th>\n";
    echo"<th class='tableau2'>\n";
    echo"".$language['pc-page-view']."\n";
    echo"</th></tr>\n";
    if($usearchive==0)
        {			
        echo"<tr><td class='tableau3'>".numbdisp($nbrtotvisits)."</td>\n";		
        echo"<td class='tableau3'>".numbdisp($nbrtotpages)."</td>\n";
        $pcvis  = round(($nbrtotpages / $nbrpagestotal[$site])*100,1);
        echo"<td class='tableau5'>".$pcvis."%</td></tr> \n";			
        echo"</table></div><br>\n";
        }
    else
        {
        echo"<tr><td class='tableau3'>".numbdisp($nbrtotvisits)."*</td>\n";		
        echo"<td class='tableau3'>".numbdisp($nbrtotpages)."*</td>\n";
        $pcvis  = round(($nbrtotpages / $nbrpagestotal[$site])*100,1);
        echo"<td class='tableau5'>".$pcvis."%*</td></tr> \n";			
        echo"</table>\n";
        echo"<p>*".$language['use-archive']."</p></div><br>\n";
        }
        
        
        		
     if($period != 5)
        {
        //graph
        echo"<div class='graphvisits'>\n";    
        //mapgraph
        include"include/mapgraph.php";     
        echo"<img src=\"./graphs/visit-graph.php?crawltlang=$crawltlang&period=$period&navig=$navig&graphname=$graphname\" USEMAP=\"#visit\" alt=\"graph\" width=\"700\" heigth=\"300\"  border=\"0\"/>\n";
        echo"</div>\n";
        echo"<div class='imprimgraph'>\n";       
        echo"&nbsp;<br><br><br><br><br><br></div>\n"; 
        }

    //change text if more than x crawlers	and display limited (value of x can be change in function.php,,it's displaynumber)
    if($nbrtotpages>=$rowdisplay && $displayall=='no' && $period !=5)
        {
        echo"<br><h2>";
        printf($language['100_visit_per-crawler'],$rowdisplay);
        echo"<br>\n";
         $crawlencode = urlencode($crawler);
        echo"<span class=\"smalltext\"><a href=\"index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&order=$order&displayall=yes&graphpos=$graphpos\">".$language['show_all']."</a></span></h2>";
           }
    else
        {
        echo"<h2>".$language['visit_per-crawler']."</h2>\n";
        } 


    echo"<div class='tableau' align='center'>\n";	
    echo"<table   cellpadding='0px'; cellspacing='0' width='100%'>\n";
    if($order==3)
        {
        echo"<tr><th class='tableau1' colspan=\"2\">\n";
        echo"<form action=\"index.php\" method=\"POST\" >\n";
        echo "<input type=\"hidden\" name ='order' value=\"3\">\n";			
        echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
        echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";        
        echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
        echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
        echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";		
        echo"<input type='submit' class='orderselect' value='".$language['page']."'>\n";
        echo"</form>\n";		
        echo"</th>\n";
        }
    else
        {
        echo"<tr><th class='tableau1' colspan=\"2\">\n";
        echo"<form action=\"index.php\" method=\"POST\" >\n";
        echo "<input type=\"hidden\" name ='order' value=\"3\">\n";			
        echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
        echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";        
        echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
        echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
        echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";		
        echo"<input type='submit' class='order' value='".$language['page']."'>\n";
        echo"</form>\n";		
        echo"</th>\n";
        }	
    if($order==2)
            {
            if($period !=5)
                {            
                echo"<th class='tableau1' >\n";
                }
            else
                {
                echo"<th class='tableau2' >\n";                
                }
            echo"<form action=\"index.php\" method=\"POST\" >\n";
            echo "<input type=\"hidden\" name ='order' value=\"2\">\n";			
            echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
            echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";            
            echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
            echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
            echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";			
            echo"<input type='submit' class='orderselect' value='".$language['nbr_visits']."'>\n";
            echo"</form>\n";			
            echo"</th>\n";
            }
        else
            {
            if($period !=5)
                {            
                echo"<th class='tableau1' >\n";
                }
            else
                {
                echo"<th class='tableau2' >\n";                
                }
            echo"<form action=\"index.php\" method=\"POST\" >\n";
            echo "<input type=\"hidden\" name ='order' value=\"2\">\n";			
            echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
            echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";            
            echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
            echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
            echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";			
            echo"<input type='submit' class='order' value='".$language['nbr_visits']."'>\n";
            echo"</form>\n";
            echo"</th>\n";
            }	
            if($period !=5)
                {     
                if($order==0)
                        {
                        echo"<th class='tableau2'>\n";			
                        echo"<form action=\"index.php\" method=\"POST\" >\n";
                        echo "<input type=\"hidden\" name ='order' value=\"0\">\n";			
                        echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
                        echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";               
                        echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
                        echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
                        echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";			
                        echo"<input type='submit' class='orderselect' value='".$language['date_visits']."'>\n";
                        echo"</form>\n";
                        echo"</th></tr>\n";
                        }
                    else
                        {
                        echo"<th class='tableau2'>\n";
                        echo"<form action=\"index.php\" method=\"POST\" >\n";
                        echo "<input type=\"hidden\" name ='order' value=\"0\">\n";			
                        echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
                        echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";            
                        echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
                        echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
                        echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";			
                        echo"<input type='submit' class='order' value='".$language['date_visits']."'>\n";
                        echo"</form>\n";
                        echo"</th></tr>\n";
                        }	
                }
    //counter for alternate color lane
    $comptligne=2;

            
    foreach ($nbvisits as $key => $value)
        {
        $page1display = crawltcutkeyword($key,'60');
        $page1encode=urlencode($key);		

        //to avoid problem if the url is enter in the database with http://
        if (!eregi("^http://", $urlsite[$site]))
            {
            $urlpage="http://".$urlsite[$site].$key;
            }
        else
            {
            $urlpage= $urlsite[$site].$key;
            }         
        
			
        if ($comptligne%2 ==0)
            {
            echo"<tr><td class='tableau3g'";
            if($keywordcut==1)
                {
                echo"onmouseover=\"javascript:montre('smenu".($comptligne+40)."');\"   onmouseout=\"javascript:montre();\"";
                }                 
            echo">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=".$period."&amp;site=".$site."&amp;crawler=".$page1encode."&amp;graphpos=".$graphpos."' rel='nofollow'>".$page1display."</a></td>\n";
            echo"<td class='tableau6' width=\"8%\">\n"; 
            echo"<a href='".$urlpage."' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
            echo"</td> \n";
            if($period !=5)
                {                
                echo"<td class='tableau3'>".numbdisp($nbvisits[$key])."</td>\n";                        
                echo"<td class='tableau5'>".$lastdatedisplay[$key]."</td></tr>\n";
                }
            else
                {
                echo"<td class='tableau5'>".numbdisp($nbvisits[$key])."</td></tr>\n";
                }
            }
        else
            {
            echo"<tr><td class='tableau30g'";
            if($keywordcut==1)
                {
                echo"onmouseover=\"javascript:montre('smenu".($comptligne+40)."');\"   onmouseout=\"javascript:montre();\"";
                }                 
            echo">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=".$period."&amp;site=".$site."&amp;crawler=".$page1encode."&amp;graphpos=".$graphpos."' rel='nofollow'>".$page1display."</a></td>\n";
            echo"<td class='tableau60' width=\"8%\">\n"; 
            echo"<a href='".$urlpage."' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
            echo"</td> \n";
            if($period !=5)
                {                 
                echo"<td class='tableau30'>".numbdisp($nbvisits[$key])."</td>\n";
                echo"<td class='tableau50'>".$lastdatedisplay[$key]."</td></tr>\n";
                }
            else
                {
                echo"<td class='tableau50'>".numbdisp($nbvisits[$key])."</td></tr>\n";
                }                    
            }
        if($keywordcut==1)
            { 
            if($period==0 OR $period>=1000)
                {
                $step=25;
                }
            else
                {
                $step=30;
                }                             
            echo"<div id=\"smenu".($comptligne+40)."\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:".(900+(($comptligne-3)*$step))."px; left:5px; background:#fff;\">\n";      
            echo"&nbsp;".crawltcuturl($key,'92')."&nbsp;\n";
            echo"</div>\n";
            }                
        $comptligne++;

        }

    echo"</table>\n";
    echo"<br>\n";
		
	
	}
else
	{
	$sqlstats2 = "SELECT * FROM crawlt_crawler
	WHERE crawlt_crawler.crawler_name='".sql_quote($crawler)."'
	ORDER BY crawler_name ASC";

	$requetestats2 = mysql_query($sqlstats2, $connexion) or die("MySQL query error");
	
    //mysql connexion close
    mysql_close($connexion);
    	
	$nbrresult2=mysql_num_rows($requetestats2);
		if ($nbrresult2==0)
			{
			echo"<h1>Hacking attempt !!!!</h1>";
			exit();
			}
	
	
	while ($ligne = mysql_fetch_object($requetestats2))                                                                              
		{
		$address=$ligne->crawler_url;
		$info=$ligne->crawler_info;
		$agent=$ligne->crawler_user_agent;
		$uagent[]=$agent;
		}

	$crawlerdisplay=htmlentities($crawler);
	$addressdisplay=htmlentities($address);
	$infodisplay=htmlentities($info);
	
	
	echo"<div align='center'>\n";
    echo crawltbackforward($crawlerdisplay,$period,$daytodaylocal,$monthtodaylocal,$yeartodaylocal,$daybeginlocal,$monthbeginlocal,$yearbeginlocal,$dayendweek,$monthendweek,$yearendweek,$crawler,$navig,$site,$graphpos);	
	
	//ua table
    echo"<div class='tableau' align='center'>\n";	
    echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";	
    echo"<tr><th class='tableau1'>\n";
    echo"".$language['user_agent']."\n";
    echo"</th>\n";
    echo"<th class='tableau2'>\n";
    echo"".$language['Origin']."\n";
    echo"</th></tr>\n";
    $uagent=array_unique($uagent);
    $nbline=sizeof($uagent);
    $nb=0;
    foreach ($uagent as $ua)
        {
        $uadisplay=htmlentities($ua);
        echo"<tr><td class='tableau3'>".$uadisplay."</td>\n";
        if($nb==0)
            {
            echo"<td class='tableau5' rowspan=".$nbline."><a href=\"$addressdisplay\">".$infodisplay."</a></td></tr>\n";
            }
        else
            {
            echo"</tr>\n";
            }	
        $nb=2;	
        }
    echo"</table></div><br>\n";		
	
	
						
	echo"<h1>".$language['no_visit']."</h1>\n";
	echo"<br>\n";	
	
	}
	
?>