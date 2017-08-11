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
// file: cleaning-double-entry.php
//----------------------------------------------------------------------

//initialize array
$testunique=array();
$table=array();
$date=array();
$idtosuppress=array();

 //update the crawlt_config table to enter the last cleaning date (now - 1 hour)
$datecleaning = date("Y-m-d H:i:s",(strtotime("now")-3600));
$sqlupdate ="UPDATE crawlt_config SET datelastcleaning='".sql_quote($datecleaning)."'";
$requeteupdate = mysql_query($sqlupdate, $connexion) or die("MySQL query error"); 

/*cleaning of the crawlt_visits_human table
to suppress double entry (same search engine, same keyword, same site, same page view, with less than 5mn between visit)
since the last cleaning*/
$sqlcleaning = "SELECT  id_visit,crawlt_site_id_site,keyword,crawlt_id_crawler, date, crawlt_id_page FROM crawlt_visits_human, crawlt_keyword
WHERE crawlt_visits_human.crawlt_keyword_id_keyword = crawlt_keyword.id_keyword
AND  date >'".sql_quote($datecleaning)."'"; 


$requetecleaning = mysql_query($sqlcleaning, $connexion) or die("MySQL query error");
$visitstotal=mysql_num_rows($requetecleaning);
if($visitstotal>=1)
    {  
    while ($ligne = mysql_fetch_row($requetecleaning))                                                                              
        {
        $testunique[]=$ligne[1].urlencode($ligne[2]).$ligne[3].$ligne[5];
        $table[]=$ligne[0];
        $date[]= strtotime($ligne[4]);  
        } 

    $testnodouble = array_unique($testunique);
    $testdouble= array_diff_assoc($testunique,$testnodouble);
    
                
    $somethingtosuppress=0;

    foreach($testdouble as $i=>$value)
        {        
        foreach($testnodouble as $j=>$value2)
            {
            if($testunique[$i]==$testunique[$j] && abs($date[$i]-$date[$j])<300)
                {        
                $idtosuppress[]=$table[$i];
                $somethingtosuppress=1;        
                }
            }
        }
        
      

    if($somethingtosuppress==1)
        {
        //request to suppress double entry in the visit table
        $listidtosuppress=implode("','",$idtosuppress);
        $sqlsuppress = "DELETE FROM crawlt_visits_human WHERE id_visit IN ('$listidtosuppress')";
        $requetesuppress = mysql_query($sqlsuppress, $connexion) or die("MySQL query error");
        }

   }

?>