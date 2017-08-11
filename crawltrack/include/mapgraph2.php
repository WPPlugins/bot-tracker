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
// file:mapgraph2.php
//----------------------------------------------------------------------

//initialize array
$axexlabel=array();
$axex=array();
$googlevisit=array();
$yahoovisit=array();
$msnvisit=array();
$askvisit=array();
$yahoozerolink=array();
$msnzerolink=array();
$yahoozeropage=array();
$msnzeropage=array();
$deliciouszerolink=array();
$yahoolink=array();
$yahoopage=array();
$msnlink=array();
$msnpage=array();
$deliciouslink=array();
$yahoo=array();
$msn=array();     
$datatransfert1=array();
$google1=array();
$msn1=array();    
$yahoo1=array();
$ask1=array();
$datatransfert2=array();
$msn2=array();    
$yahoo2=array();
$datatransfert3=array();
$delicious=array();
$datatransfert4=array();
//prepare X axis label
//number of days (or months) for the period
$nbday2=0;
$date=$datebeginlocalcut[0];

if($period==0 OR $period>=1000)
    {
    $nbday=8;
	$daterequestseo = date("Y-m-d H:i:s",(strtotime($daterequest)- 604800)); 
	$datebeginlocalseo = date("Y-m-d H:i:s",(strtotime($datebeginlocal)- 604800));
	$datebeginlocalcutseo = explode(' ', $datebeginlocalseo); 
	$date=$datebeginlocalcutseo[0];  
    }
elseif($period==1 OR ($period>=300 && $period<400))
    {
    $nbday=7;
    $daterequestseo=$daterequest;
    }     
elseif($period==2 OR ($period>=100 && $period<200))
    {    
    $nbday=date("t",mktime(0,0,0,$monthrequest,$dayrequest,$yearrequest)); 
    $daterequestseo=$daterequest;
    }
elseif($period==3 OR ($period >= 200 && $period<300))
    {
    $nbday=12;
    $daterequestseo=$daterequest;
    }    
elseif($period==4)
    {
    $nbday=8;
    $daterequestseo=$daterequest;
    }
    

        
do 	{
    
    $date2=$date;
    $date20 = explode('-', $date);
    $yeardate = $date20[0];
    $monthdate = $date20[1];
    $daydate = $date20[2];		

    if($nbday==7)
        {
        if($firstdayweek =='Monday')
            {
            $day="day".$nbday2;
            }
        else
            {
            //case first week day is sunday
            $nbday3=$nbday2+6;
            if($nbday3>6)
                {
                $nbday3=$nbday3-7;                    
                }
             $day="day".$nbday3;   
            }
        $axexlabel[$daydate."-".$monthdate."-".$yeardate]=$language[$day]." ".$daydate;
        $axex[]=$daydate."-".$monthdate."-".$yeardate;
        $askvisit[$daydate."-".$monthdate."-".$yeardate]=0;
        $googlevisit[$daydate."-".$monthdate."-".$yeardate]=0; 
        $msnlink[$daydate."-".$monthdate."-".$yeardate]=0;
        $msnpage[$daydate."-".$monthdate."-".$yeardate]=0;
        $msnvisit[$daydate."-".$monthdate."-".$yeardate]=0; 
        $yahoolink[$daydate."-".$monthdate."-".$yeardate]=0;
        $yahoopage[$daydate."-".$monthdate."-".$yeardate]=0;
        $yahoovisit[$daydate."-".$monthdate."-".$yeardate]=0; 
        $deliciouslink[$daydate."-".$monthdate."-".$yeardate]=0;                         
        $datatransfert[$language[$day]." ".$daydate]='0-0-0-0'; 
        if(nbdayfromtoday($date)==0)
            {
            $totperiod[$daydate."-".$monthdate."-".$yeardate]=0;
            }
        else
            {
            $totperiod[$daydate."-".$monthdate."-".$yeardate]=999+nbdayfromtoday($date);
            }                     
        }
    elseif($nbday==12)
        {
        $actualmonth=date("m");
        $actualyear=date("Y");        
        $yearmonth=$monthdate."/".$yeardate;
        if($monthdate>=$actualmonth && $yeardate==$actualyear)
            {
            $totperiod[$yearmonth]=2;
            }
        else
            {
            $totperiod[$yearmonth]=99+($actualmonth-$monthdate)+(12*($actualyear-$yeardate));
            }        
        $axexlabel[$yearmonth]=$yearmonth;
        $axex[]=$yearmonth;
        $askvisit[$yearmonth]=0;
        $googlevisit[$yearmonth]=0;
        $msnlink[$yearmonth]=0;
        $msnpage[$yearmonth]=0;
        $msnvisit[$yearmonth]=0;
        $yahoolink[$yearmonth]=0;
        $yahoopage[$yearmonth]=0;
        $yahoovisit[$yearmonth]=0;
        $deliciouslink[$yearmonth]=0;                               
        $datatransfert[$yearmonth]='0-0-0-0';   
        }        
    else
        {
        $axexlabel[$daydate."-".$monthdate."-".$yeardate]=$daydate."-".$monthdate."-".substr("$yeardate",2,4);
        $axex[]=$daydate."-".$monthdate."-".$yeardate;
        $askvisit[$daydate."-".$monthdate."-".$yeardate]=0;
        $googlevisit[$daydate."-".$monthdate."-".$yeardate]=0;
        $msnlink[$daydate."-".$monthdate."-".$yeardate]=0;
        $msnpage[$daydate."-".$monthdate."-".$yeardate]=0;
        $msnvisit[$daydate."-".$monthdate."-".$yeardate]=0;
        $yahoolink[$daydate."-".$monthdate."-".$yeardate]=0;
        $yahoopage[$daydate."-".$monthdate."-".$yeardate]=0;
        $yahoovisit[$daydate."-".$monthdate."-".$yeardate]=0; 
        $deliciouslink[$daydate."-".$monthdate."-".$yeardate]=0;                             
        $datatransfert[$daydate."-".$monthdate."-".substr("$yeardate",2,4)]='0-0-0-0'; 
        if(nbdayfromtoday($date)==0)
            {
            $totperiod[$daydate."-".$monthdate."-".$yeardate]=0;
            }
        else
            {
            $totperiod[$daydate."-".$monthdate."-".$yeardate]=999+nbdayfromtoday($date);
            }    
        }
    if($nbday==12)
        {
        $monthdate1=$monthdate + 1;
		$ts =  mktime(0,0,0,$monthdate1, 15, $yeardate);
        }
    else
        {
        $ts =  mktime(0,0,0,$monthdate, $daydate, $yeardate) + 86400;
        }
    $date = date("Y-m-d",$ts);
    //case change summer time to winter time
    if($date==$date2)
        {
        $date = date("Y-m-d",($ts+7200));
        }
    $nbday2++;
    }
while($nbday2<$nbday);


//mysql requete

if($typegraph =='entry')
    {
     //requete to count the number of entry
    if($period ==3) //case one year 
        {
        $sqlgoogle = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='1'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requetegoogle = mysql_query($sqlgoogle, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requetegoogle))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $googlevisit[$yearmonth]= $ligne[2];
                }  
                
        $sqlyahoo = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='2'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requeteyahoo = mysql_query($sqlyahoo, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteyahoo))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $yahoovisit[$yearmonth]= $ligne[2];
                }                 
                
        $sqlmsn = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='3'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requetemsn = mysql_query($sqlmsn, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requetemsn))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $msnvisit[$yearmonth]= $ligne[2];
                }  
                
        $sqlask = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='4'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requeteask = mysql_query($sqlask, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteask))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $askvisit[$yearmonth]= $ligne[2];
                }                                
        
        }     
    elseif($period >= 200 && $period<300) //case one year back and forward
        {
        $sqlgoogle = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND  date <'".sql_quote($daterequest2)."'        
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='1'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requetegoogle = mysql_query($sqlgoogle, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requetegoogle))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $googlevisit[$yearmonth]= $ligne[2];
                }  
                
        $sqlyahoo = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND  date <'".sql_quote($daterequest2)."'        
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='2'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requeteyahoo = mysql_query($sqlyahoo, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteyahoo))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $yahoovisit[$yearmonth]= $ligne[2];
                }                 
                
        $sqlmsn = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND  date <'".sql_quote($daterequest2)."'        
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='3'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requetemsn = mysql_query($sqlmsn, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requetemsn))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $msnvisit[$yearmonth]= $ligne[2];
                }                 

        $sqlask = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND  date <'".sql_quote($daterequest2)."'        
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='4'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requeteask = mysql_query($sqlask, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteask))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                $askvisit[$yearmonth]= $ligne[2];
                }
        
        }
    else 
        { 
        $sqlgoogle = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='1'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y')";
        
        $requetegoogle = mysql_query($sqlgoogle, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requetegoogle))  
                { 
                $googlevisit[$ligne[0]]= $ligne[2];
                }  
                
        $sqlyahoo = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='2'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y')";
        
        $requeteyahoo = mysql_query($sqlyahoo, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteyahoo))  
                { 
                $yahoovisit[$ligne[0]]= $ligne[2];
                }                 
                
        $sqlmsn = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='3'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y')";
        
        $requetemsn = mysql_query($sqlmsn, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requetemsn))  
                { 
                $msnvisit[$ligne[0]]= $ligne[2];
                }        

        $sqlask = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y') , crawlt_id_crawler,COUNT(id_visit)  FROM crawlt_visits_human
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND crawlt_site_id_site='".sql_quote($site)."'
        AND  crawlt_id_crawler='4'
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y')";
        
        $requeteask = mysql_query($sqlask, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteask))  
                { 
                $askvisit[$ligne[0]]= $ligne[2];
                }        
        } 
   
    
    }
else
    {
     //request to count the number of link, index page and bookmark
     
    if($period==3) //case one year
        {
         //request to count the number of link, index page and bookmarks
        
        $sqlseograph = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , MAX(linkyahoo),MAX(pageyahoo),  MAX(linkmsn),MAX(pagemsn),  MAX(nbrdelicious) FROM crawlt_seo_position
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND id_site='".sql_quote($site)."' 
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        
        $requeteseograph = mysql_query($sqlseograph, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteseograph))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                
                $yahoolink[$yearmonth]= $ligne[1];     
                $yahoopage[$yearmonth]= $ligne[2];     
                $msnlink[$yearmonth]= $ligne[3];                   
                $msnpage[$yearmonth]= $ligne[4];
                $deliciouslink[$yearmonth]= $ligne[5];                
                }         
        
        }
    elseif($period >= 200 && $period<300) //case one year back and forward
        {
                
        $sqlseograph = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , MAX(linkyahoo),MAX(pageyahoo), MAX(linkmsn),MAX(pagemsn),  MAX(nbrdelicious) FROM crawlt_seo_position
        WHERE  date >='".sql_quote($daterequestseo)."'
        AND  date <'".sql_quote($daterequest2)."'    
        AND id_site='".sql_quote($site)."' 
        GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
        
        $requeteseograph = mysql_query($sqlseograph, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteseograph))  
                {                
                $year=substr($ligne[0],0,4);
                $month=substr($ligne[0],4,2);
                $yearmonth=$month."/".$year;
                                
                $yahoolink[$yearmonth]= $ligne[1];     
                $yahoopage[$yearmonth]= $ligne[2];     
                $msnlink[$yearmonth]= $ligne[3];                   
                $msnpage[$yearmonth]= $ligne[4];
                $deliciouslink[$yearmonth]= $ligne[5];                
                }         
        
        } 
    else
        {
        if($period>=10)
            {
            $sqlseograph = "SELECT  FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'), linkyahoo, pageyahoo, linkmsn, pagemsn,nbrdelicious FROM crawlt_seo_position
            WHERE  date >='".sql_quote($daterequestseo)."'
            AND  date <='".sql_quote($daterequest2)."'
            AND id_site='".sql_quote($site)."'";
            }
          else
            {
            $sqlseograph = "SELECT  FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'), linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious FROM crawlt_seo_position
            WHERE  date >='".sql_quote($daterequestseo)."'
            AND id_site='".sql_quote($site)."'";
            }          
        
        $requeteseograph = mysql_query($sqlseograph, $connexion) or die("MySQL query error");
        
        while ($ligne = mysql_fetch_row($requeteseograph))  
                {
                $yahoolink[$ligne[0]]=$ligne[1];
                $yahoopage[$ligne[0]]=$ligne[2];
                $msnlink[$ligne[0]]=$ligne[3];
                $msnpage[$ligne[0]]=$ligne[4]; 
                $deliciouslink[$ligne[0]]=$ligne[5];                           
                }  
       }
        
    }


   
//create table for graph


if($typegraph =='link')
    {
    foreach($axex as $data)
        {            
        $yahoo[]=$yahoolink[$data];
        $msn[]=$msnlink[$data];     
        $datatransfert1[$axexlabel[$data]]= $yahoolink[$data]."-".$msnlink[$data];       
        }
//prepare datas to be transfert to graph file
$datatransferttograph=addslashes(urlencode(serialize($datatransfert1)));         
    }
elseif($typegraph =='entry')
    {
    foreach($axex as $data)
        {
        $google1[]=$googlevisit[$data];

        $msn1[]=$msnvisit[$data];
            
        $yahoo1[]=$yahoovisit[$data]; 

        $ask1[]=$askvisit[$data];
                
        $datatransfert2[$axexlabel[$data]]= $googlevisit[$data]."-". $msnvisit[$data]."-".$yahoovisit[$data]."-".$askvisit[$data];        
        }
//prepare datas to be transfert to graph file
$datatransferttograph=addslashes(urlencode(serialize($datatransfert2)));         
        
    }    
elseif($typegraph =='page')
    {
    foreach($axex as $data)
        {
           
        $yahoo2[]=$yahoopage[$data];
        $msn2[]=$msnpage[$data];        
         $datatransfert3[$axexlabel[$data]]=$yahoopage[$data]."-".$msnpage[$data];         
        }
    //prepare datas to be transfert to graph file
    $datatransferttograph=addslashes(urlencode(serialize($datatransfert3)));         
            
    }
elseif($typegraph =='bookmark')
    {
    foreach($axex as $data)
        {           
        $delicious[]=$deliciouslink[$data];       
        $datatransfert4[$axexlabel[$data]]=$deliciouslink[$data];         
        }
    //prepare datas to be transfert to graph file
    $datatransferttograph=addslashes(urlencode(serialize($datatransfert4)));         
            
    }    
//insert the values in the graph table  
$graphname=$typegraph."-".$cachename;
   
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
  
//map graph
if($period==3 OR ($period >= 200 && $period<300))
    {    
    $widthzone=67;
    $x2=132.3;
    $y=31;
    $y2=211;          
    } 
elseif($period==2 OR ($period>=100 && $period<200))
    {
     if($nbday==28)
        {            
        $widthzone=28.7;
        $x2=94;
        $y=31;
        $y2=211; 
        }
    elseif($nbday==29)
        {            
        $widthzone=27.7;
        $x2=93;
        $y=31;
        $y2=211; 
        } 
    elseif($nbday==30)
        {            
        $widthzone=26.8;
        $x2=92.1;
        $y=31;
        $y2=211; 
        }               
    else
        {            
        $widthzone=26;
        $x2=91.3;
        $y=31;
        $y2=211; 
        }
    }  
elseif($period==0 OR $period==4 OR $period>=1000)
    {    
    $widthzone=100.75;
    $x2=166.05;
    $y=31;
    $y2=211;          
    }          
elseif($period==1 OR ($period>=300 && $period<400))
    {
    $widthzone=115.14;
    $x2=180.44;
    $y=31;
    $y2=211;        
    } 
if($typegraph=='link')
   {
    echo"<MAP ID=\"seolink\" NAME=\"seolink\">\n";
    $iday=0;
    $x=65.3;
    do 	{
        echo"<AREA SHAPE=\"RECT\" COORDS=\"".$x.",".$y.",".$x2.",".$y2."\" onmouseover=\"javascript:montre('smenu".($iday+9)."');\" onmouseout=\"javascript:montre();\"";
        $dateday=$axex[$iday];
        $periodtogo = $totperiod[$dateday];
        echo"href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\">\n"; 
         $x=$x+$widthzone;
         $x2=$x2+$widthzone;
         $iday++;            
         }
    while($iday<$nbday);
    echo"</MAP>\n";    
        
     $iday=0;
      do 	{           
            echo"<div id=\"smenu".($iday+9)."\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";       
            echo"&nbsp;".$language['nbr_tot_link'].":&nbsp;".$axexlabel[$axex[$iday]]."&nbsp;\n";  
            echo"&nbsp;".$language['msn'].":&nbsp;".$msn[$iday]."\n";
            echo"&nbsp;".$language['yahoo'].":&nbsp;".$yahoo[$iday]."&nbsp;\n";
            echo"</div>\n";           
        $iday++;
         }
      while($iday<$nbday); 
  } 
elseif($typegraph=='page')
   {
    echo"<MAP ID=\"seopage\" NAME=\"seopage\">\n";
    $iday=0;
    $x=65.3;
    do 	{
        echo"<AREA SHAPE=\"RECT\" COORDS=\"".$x.",".$y.",".$x2.",".$y2."\" onmouseover=\"javascript:montre('smenu".($iday+70)."');\" onmouseout=\"javascript:montre();\"";
        $dateday=$axex[$iday];
        $periodtogo = $totperiod[$dateday];
        echo"href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\">\n";         
         $x=$x+$widthzone;
         $x2=$x2+$widthzone;
         $iday++;            
         }
    while($iday<$nbday);
    echo"</MAP>\n";    
        
     $iday=0;
      do 	{           
            echo"<div id=\"smenu".($iday+70)."\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";       
            echo"&nbsp;".$language['nbr_tot_pages_index'].":&nbsp;".$axexlabel[$axex[$iday]]."&nbsp;\n";
            echo"&nbsp;".$language['msn'].":&nbsp;".$msn2[$iday]."\n";
            echo"&nbsp;".$language['yahoo'].":&nbsp;".$yahoo2[$iday]."&nbsp;\n";
            echo"</div>\n";           
        $iday++;
         }
      while($iday<$nbday); 
  }  
elseif($typegraph=='entry')
   {
    echo"<MAP ID=\"seoentry\" NAME=\"seoentry\">\n";
    $iday=0;
    $x=65.3;
    do 	{
        echo"<AREA SHAPE=\"RECT\" COORDS=\"".$x.",".$y.",".$x2.",".$y2."\" onmouseover=\"javascript:montre('smenu".($iday+131)."');\" onmouseout=\"javascript:montre();\"";
        $dateday=$axex[$iday];
        $periodtogo = $totperiod[$dateday];
        echo"href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\">\n";         
         $x=$x+$widthzone;
         $x2=$x2+$widthzone;
         $iday++;            
         }
    while($iday<$nbday);
    echo"</MAP>\n";    
        
     $iday=0;
      do 	{           
            echo"<div id=\"smenu".($iday+131)."\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";       
            echo"&nbsp;".$language['nbr_tot_visit_seo'].":&nbsp;".$axexlabel[$axex[$iday]]."&nbsp;\n";
            echo"&nbsp;".$language['ask'].":&nbsp;".$ask1[$iday]."\n";           
            echo"&nbsp;".$language['google'].":&nbsp;".$google1[$iday]."\n";               
            echo"&nbsp;".$language['msn'].":&nbsp;".$msn1[$iday]."\n";
            echo"&nbsp;".$language['yahoo'].":&nbsp;".$yahoo1[$iday]."&nbsp;\n";
            echo"</div>\n";           
        $iday++;
         }
      while($iday<$nbday); 
  }  
elseif($typegraph=='bookmark')
   {
    echo"<MAP ID=\"bookmark\" NAME=\"bookmark\">\n";
    $iday=0;
    $x=65.3;
    do 	{
        echo"<AREA SHAPE=\"RECT\" COORDS=\"".$x.",".$y.",".$x2.",".$y2."\" onmouseover=\"javascript:montre('smenu".($iday+192)."');\" onmouseout=\"javascript:montre();\"";
        $dateday=$axex[$iday];
        $periodtogo = $totperiod[$dateday];
        echo"href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\">\n";          
         $x=$x+$widthzone;
         $x2=$x2+$widthzone;
         $iday++;            
         }
    while($iday<$nbday);
    echo"</MAP>\n";    
        
     $iday=0;
      do 	{           
            echo"<div id=\"smenu".($iday+192)."\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";       
            echo"&nbsp;".$language['nbr_tot_bookmark'].":&nbsp;".$axexlabel[$axex[$iday]]."&nbsp;\n";
            echo"&nbsp;".$language['delicious'].":&nbsp;".$delicious[$iday]."&nbsp;\n";     
            echo"</div>\n";           
        $iday++;
         }
      while($iday<$nbday); 
  }   
  
  
    
?>