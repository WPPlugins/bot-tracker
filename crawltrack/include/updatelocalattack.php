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
// file: updatelocalattack.php
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


if(file_exists('include/attacklist.php'))
	{
	include "include/attacklist.php";
	
	//databaseconnection
    
    $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
    $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
	//requete to get the actual liste id

	$sqlupdate = "SELECT * FROM crawlt_update_attack";

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
		$tabdata=explode("crawltototrack",$attacklist);

		$nbr=count($tabdata)/4;

						//we treat the file content
						$i=0;
						for ($j=1;$j<=$nbr;$j++)
							{
							$updatelistid[$j]=$tabdata[$i];
							$i=$i+1;
							$updatelistattack[$j]=$tabdata[$i];
							$i=$i+1;
							$updatelistscript[$j]=$tabdata[$i];							
							$i=$i+1;
							$updatelisttype[$j]=$tabdata[$i];							
							$i=$i+1;							
							}
		
		
						$sqlexist = "SELECT * FROM crawlt_attack";
	
						$requeteexist = mysql_query($sqlexist, $connexion);
	
	
						while ($ligne = mysql_fetch_object($requeteexist))                                                                              
							{
							$attackid=$ligne->id_attack;
							$listattack[]=$attackid;
							}

		
						$nbrdata =count($updatelistid);
						$nbrupdate=0;	
	
						for ($k=1;$k<=$nbrdata;$k++)
							{
							$id=$updatelistid[$k];
							$attack=$updatelistattack[$k];
							$script=$updatelistscript[$k];
              $type=$updatelisttype[$k];
		

							if(in_array($id,$listattack))
								{
								}
							else
								{
								$sqlinsert ="INSERT INTO crawlt_attack (id_attack,attack, script, type)
								VALUES ('".sql_quote($id)."','".sql_quote($attack)."','".sql_quote($script)."','".sql_quote($type)."')";	
		
								$requeteinsert = mysql_query($sqlinsert, $connexion) or die("MySQL query error");
								$nbrupdate=$nbrupdate+1;
								$crawlernameadd[] = $attack;
								$crawleruaadd[] = $script;
								$crawlertypeadd[] = $type;
								}				
								
				
			}

						echo"<h1><br><br>$nbrupdate&nbsp;".$language['attack_add']."<br></h1>";
						$sqlinsertid ="INSERT INTO crawlt_update_attack (update_id) VALUES ('".sql_quote($idlist)."')";	
		
						$requeteinsertid = mysql_query($sqlinsertid, $connexion) or die("MySQL query error");
						
						
						echo"<div align='center'><table cellpadding='0px' cellspacing='0' width='750px'><tr><td class='tableau1'>".$language['parameter']."</td><td class='tableau1'>".$language['script']."</td><td class='tableau2'>".$language['attack_type']."</td></tr>\n";
                        for ($l=0;$l<$nbrupdate;$l++)
                            {
                            $crawlnamedisplay=htmlentities($crawlernameadd[$l]);
                            $crawluadisplay=htmlentities($crawleruaadd[$l]);
                            $crawltypedisplay=htmlentities($crawlertypeadd[$l]);
                            
                            if ($l%2 ==0)
                                {
                                echo"<tr><td class='tableau3'>$crawlnamedisplay</td>\n";
                                echo"<td class='tableau3'>$crawluadisplay</td>\n";
                                echo"<td class='tableau5'>$crawltypedisplay</td></tr>\n";
                                }
                            else
                                {
                                echo"<tr><td class='tableau30'>$crawlnamedisplay</td>\n";
                                echo"<td class='tableau30'>$crawluadisplay</td>\n";
                                echo"<td class='tableau50'>$crawltypedisplay</td></tr>\n";
                                } 
                            }
                        echo"</tr></table></div><br><br>";		
		
				
		}
	}
else
	{
	echo"<br><br><h1>".$language['no_attack_list']."</h1><br>";
	}



?>