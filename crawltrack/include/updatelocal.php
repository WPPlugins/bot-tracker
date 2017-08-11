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
// file: updatelocal.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$updatelistua=array();
$updatelistname=array();
$updatelisturl=array();
$updatelistuser=array();
$listcrawler=array();
$crawlernameadd=array();
$crawleruaadd=array();	


if(file_exists('include/crawlerlist.php'))
	{
	include "include/crawlerlist.php";
	
	//databaseconnection
    
    $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
    $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
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
	
	//test to know is the crawler list is up to date.
	if($idlist==$idlastupdate)
		{
		//the list is up to date
		echo"<br><br><h1>".$language['list_up_to_date']."</h1><br><br>";	
		}
	else
		{	
		$tabdata=explode("crawltrack",$crawlerlist);

		$nbr=count($tabdata)/4;

		//we treat the file content
		$i=0;
		for ($j=1;$j<=$nbr;$j++)
			{
			$updatelistua[$j]=$tabdata[$i];
			$i=$i+1;
			$updatelistname[$j]=$tabdata[$i];
			$i=$i+1;
			$updatelisturl[$j]=$tabdata[$i];
			$i=$i+1;
			$updatelistuser[$j]=$tabdata[$i];
			$i=$i+1;
			}
		
		
		$sqlexist = "SELECT * FROM crawlt_crawler";
	
		$requeteexist = mysql_query($sqlexist, $connexion) or die("MySQL query error");
	
	
		while ($ligne = mysql_fetch_object($requeteexist))                                                                              
			{
			$crawlerua=$ligne->crawler_user_agent;
			$listcrawler[]=$crawlerua;
			}	

		
		$nbrdata =count($updatelistua);
		$nbrupdate=0;	
	
		for ($k=1;$k<=$nbrdata;$k++)
			{
			$uatest=stripslashes($updatelistua[$k]);
			$ua=$updatelistua[$k];
			$name=$updatelistname[$k];
			$url=$updatelisturl[$k];
			$user=$updatelistuser[$k];
		

			if(in_array($uatest,$listcrawler))
				{
				}
			else
				{
				$sqlinsert ="INSERT INTO crawlt_crawler (crawler_user_agent,crawler_name, crawler_url, crawler_info, crawler_ip)
				VALUES ('".sql_quote($ua)."','".sql_quote($name)."','".sql_quote($url)."','".sql_quote($user)."','')";	
		
				$requeteinsert = mysql_query($sqlinsert, $connexion) or die("MySQL query error");
				$nbrupdate=$nbrupdate+1;
                $crawlernameadd[] = $name;
                $crawleruaadd[] = $ua;				
				}				
				
			}

		echo"<h1><br><br>$nbrupdate&nbsp;".$language['crawler_add']."<br></h1>";
		$sqlinsertid ="INSERT INTO crawlt_update (update_id) VALUES ('".sql_quote($idlist)."')";	
		
		$requeteinsertid = mysql_query($sqlinsertid, $connexion) or die("MySQL query error");
		
        echo"<div align='center'><table cellpadding='0px' cellspacing='0' width='750px'><tr><td class='tableau1'>".$language['crawler_name']."</td><td class='tableau2'>".$language['user_agent']."</td></tr>\n";
        for ($l=0;$l<$nbrupdate;$l++)
            {
            $crawlnamedisplay=htmlentities($crawlernameadd[$l]);
            $crawluadisplay=htmlentities($crawleruaadd[$l]);
            if ($l%2 ==0)
                {
                echo"<tr><td class='tableau3'>$crawlnamedisplay</td>\n";
                echo"<td class='tableau5'>$crawluadisplay</td></tr>\n";
                }
            else
                {
                echo"<tr><td class='tableau30'>$crawlnamedisplay</td>\n";
                echo"<td class='tableau50'>$crawluadisplay</td></tr>\n";
                } 
            }
        echo"</tr></table></div><br><br>";		
		
				
		}
	}
else
	{
	echo"<br><br><h1>".$language['no_crawler_list']."</h1><br>";
	}



?>