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
// file: createtableip.php
//----------------------------------------------------------------------
//This product includes GeoLite data created by MaxMind, available from
//http://maxmind.com/"
//----------------------------------------------------------------------



//crawlt_ip_data table creation

//check if table already exist

$table_exist8 = 0;

$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql) or die("MySQL query error"); 

while (list($tablename)=mysql_fetch_array($tables)) 
	{

	if($tablename == 'crawlt_ip_data')
		{
		$table_exist8 = 1;
		}
	}


if($table_exist8 == 0)
	{

	//the table didn't exist, we can create it

	$result10 = mysql_query("CREATE TABLE crawlt_ip_data (
	ip_from INTEGER UNSIGNED NOT NULL,
	ip_to INTEGER UNSIGNED NOT NULL,
	country_code CHAR(2) NULL,
	PRIMARY KEY(ip_from, ip_to)
	)") or die("MySQL query error");
	}
else
	{
	$result10=1;
	}

if($result10==1)
	{
	
		//check if table is already filled

	$nbr=0;
	$resultat2=mysql_query("SELECT ip_from FROM crawlt_ip_data") or die("MySQL query error");
	$nbr2 = mysql_num_rows($resultat2);

	if($nbr2<100)
		{
		
        //we can insert the data
    
        include dirname(__FILE__)."/requeteip01.php";
        include dirname(__FILE__)."/requeteip02.php";
        include dirname(__FILE__)."/requeteip03.php";
        include dirname(__FILE__)."/requeteip04.php";
        include dirname(__FILE__)."/requeteip05.php";
        include dirname(__FILE__)."/requeteip06.php";
        include dirname(__FILE__)."/requeteip07.php";
        include dirname(__FILE__)."/requeteip08.php";
        include dirname(__FILE__)."/requeteip09.php";
        include dirname(__FILE__)."/requeteip10.php";
    
    
        if($result111==1 && $result112==1 && $result113==1 && $result114==1 && $result115==1 && $result116==1 && $result117==1 && $result118==1 && $result119==1)
            {
            $result11=1;
            }

        }
    else
        {
        $result11=1;
        }
            
    }	
?>