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
// file: update.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

//crawlt_update table creation if not exist in case of upgrade from a previous version

//check if table already exist

$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");



$table_exist6 = 0;

$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion) or die("MySQL query error"); 

while (list($tablename)=mysql_fetch_array($tables)) 
	{

	if($tablename == 'crawlt_update')
		{
		$table_exist6 = 1;
		}
	}


if($table_exist6 == 0)
	{
	//the table didn't exist, we have to create it
					
	$result7 = mysql_query("CREATE TABLE crawlt_update (
  	idcrawlt_update INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  	update_id INTEGER UNSIGNED NULL,
	PRIMARY KEY(idcrawlt_update)
	)") or die("MySQL query error");
										
	if($result7==1)
		{
		$result8 =mysql_query("INSERT INTO crawlt_update VALUES (1,'0')") or die("MySQL query error");
		$idlastupdate=0;
		$testtable=1;
		}
	if($result7!=1 OR $result8!=1)
		{
		//table not created
		$testtable=0;
		}			
						
	}
else
	{
	
	//requete to get the actual liste id
	
	$sqlupdate = "SELECT * FROM crawlt_update";

	$requeteupdate = mysql_query($sqlupdate, $connexion) or die("MySQL query error");

	$idlastupdate=0;

	while ($ligne = mysql_fetch_object($requeteupdate))                                                                              
		{
		$update=$ligne->update_id; 
		if($update>$idlastupdate)
			{
			$idlastupdate=$update;
			}		
		}
	//table ok	
	$testtable=1;				
					
	}


if($testtable==0)
	{
	//case we had a problem during table creation
	echo"<br><br><h5>".$language['step1_install_no_ok3']."</h5><br><br>";
	}
else
	{
		echo"<br><br><h1>".$language['update_title']."</h1>\n";
		
		echo"<h2>".$language['your_list']."&nbsp;Crawlerlist $idlastupdate</h2>\n";
		echo"<h2>".$language['crawltrack_list']."&nbsp;<iframe name=\"I1\" src=\"http://www.crawltrack.fr/listcrawler/infolistid.htm\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"no\" border=\"0\" frameborder=\"0\" width=\"150px\" height=\"23px\"></iframe></h2>\n";
	
		
		echo"<div class=\"form\">\n";
	
		echo"<h2><form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='14'>\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo"<input name='ok' type='submit'  value='".$language['update_crawler']."' size='20'>\n";
		echo"</form><br>\n";

		echo"<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo"<input name='ok' type='submit'  value='".$language['no_update']."' size='20'>\n";
		echo"</form></h2>\n";		
		
		
		
		echo"</div><br>\n";

		}



?>