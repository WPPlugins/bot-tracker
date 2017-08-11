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
// file: admin.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT'))
    {
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
    }

// do not modify
define('IN_CRAWLT_ADMIN', TRUE);
//database connection
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");            	
 //query to know the actual session id in the table
$sql = "SELECT sessionid FROM crawlt_sessionid";
$requete = mysql_query($sql, $connexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requete);
if($nbrresult>=1)
    {	
    while ($ligne = mysql_fetch_row($requete))
        {
        $listsessionid[]=$ligne[0];               
        }
    }
else
  {
  $listsessionid=array();
  }

//include menu 
include"include/menumain.php";
echo"<div class=\"content\">\n";

if(	$_SESSION['rightadmin']==1)
	{

	if($validform==6)
		{
		include"include/adminuser.php";
		}
	elseif($validform==7)
		{
		include"include/adminusersite.php";
		}
	elseif($validform==4)
		{
		include"include/adminsite.php";
		}	
	elseif($validform==3)
		{
		include"include/admintag.php";
		}	
	elseif($validform==2)
		{
		include"include/admincrawler.php";
		}
	elseif($validform==8)
		{
		include"include/adminusersuppress.php";
		}		
	elseif($validform==9)
		{
		include"include/adminsitesuppress.php";
		}		
	elseif($validform==10)
		{
		include"include/admincrawlersuppress.php";
		}
	elseif($validform==11)
		{
		include"include/testcrawlercreation.php";
		}		
	elseif($validform==12)
		{
		include"include/testcrawlersuppress.php";
		}		
	elseif($validform==13)
		{
		include"include/update.php";
		}		
	elseif($validform==14)
		{
		include"include/updateremote.php";
		}
	elseif($validform==15)
		{
		include"include/updatelocal.php";
		}		
	elseif($validform==16)
		{
		include"include/logochoice.php";
		}	
	elseif($validform==17)
		{
		include"include/admindatasuppress.php";
		}
	elseif($validform==18)
		{
		include"include/admintime.php";
		}
	elseif($validform==19)
		{
		include"include/adminipsuppress.php";
		}		
	elseif($validform==20)
		{
		include"include/adminmail.php";
		}
	elseif($validform==21)
		{
		include"include/adminpublicstats.php";
		}		
	elseif($validform==22)
		{
		include"include/adminfirstweekday.php";
		}	
	elseif($validform==23)
		{
		include"include/adminmodifsite.php";
		}
	elseif($validform==24)
		{
		include"include/admindatasuppress2.php";
		}
	elseif($validform==25)
		{
		include"include/adminlang.php";
		}
	elseif($validform==26)
		{
		include"include/admindatabase.php";
		}
	elseif($validform==27)
		{
		include"include/updateattack.php";
		}
	elseif($validform==28)
		{
		include"include/updateremoteattack.php";
		}
	elseif($validform==29)
		{
		include"include/updatelocalattack.php";
		}
	elseif($validform==30)
		{
		include"include/adminchangepassword.php";
		}
	elseif($validform==31)
		{
		include"include/admingoodsites.php";
		}											
	else
		{
		if($validform==96)
            {
            //update the crawlt_config table
            
            $sql ="UPDATE crawlt_config SET typecharset='".sql_quote($crawltcharset)."'";

            $requete = mysql_query($sql, $connexion) or die("MySQL query error");            
            }		
		if($validform==97)
            {
            //update the crawlt_config table
            
            $sql ="UPDATE crawlt_config SET typemail='".sql_quote($crawltmailishtml)."'";

            $requete = mysql_query($sql, $connexion) or die("MySQL query error");              
            }		
		if($validform==98)
            {
            //update the crawlt_config table
            
            $sql ="UPDATE crawlt_config SET rowdisplay='".sql_quote($rowdisplay)."'";

            $requete = mysql_query($sql, $connexion) or die("MySQL query error");              
            }		
		if($validform==99)
            {
            //update the crawlt_config table
            
            $sql ="UPDATE crawlt_config SET orderdisplay='".sql_quote($order)."'";

            $requete = mysql_query($sql, $connexion) or die("MySQL query error");              
            }
		if($validform==100)
            {
            //update the crawlt_config table
            
            $sql ="UPDATE crawlt_config SET blockattack='".sql_quote($crawltblockattack)."'";

            $requete = mysql_query($sql, $connexion) or die("MySQL query error");              
            }		
		if($validform==101)
            {
             //clear crawlt_sessionid table
            $sql = "TRUNCATE TABLE crawlt_sessionid";
            $requete = mysql_query($sql, $connexion) or die("MySQL query error");           
            
            $listsessionid=array();
            //insert new value in the crawlt_sessionid table
            $value="";
            $testsessionid=0;
            if($crawltsessionid1==1)
              {
              $value.="('PHPSESSID'),";
              $testsessionid=1;
              $listsessionid[]='PHPSESSID';
              }
             if($crawltsessionid2==1)
              {
              $value.="('phpsessid'),";
              $testsessionid=1;
              $listsessionid[]='phpsessid';
              } 
             if($crawltsessionid3==1)
              {
              $value.="('ID'),";
              $testsessionid=1;
              $listsessionid[]='ID';
              }               
             if($crawltsessionid4==1)
              {
              $value.="('id'),";
              $testsessionid=1;
              $listsessionid[]='id';
              }               
             if($crawltsessionid5==1)
              {
              $value.="('SID'),";
              $testsessionid=1;
              $listsessionid[]='SID';
              } 
             if($crawltsessionid6==1)
              {
              $value.="('sid'),";
              $testsessionid=1;
              $listsessionid[]='sid';
              }
             if($crawltsessionid7==1)
              {
              $value.="('S'),";
              $testsessionid=1;
              $listsessionid[]='S';
              } 
             if($crawltsessionid8==1)
              {
              $value.="('s'),";
              $testsessionid=1;
              $listsessionid[]='s';
              }  
                
              if( $testsessionid!=1)
                {
                $crawltsessionid=0;
                $listsessionid=array();
                }
              elseif($crawltsessionid==1)
                {
                  //update the crawlt_sessionid table  
                $value=rtrim($value,",");                              
                $sql ="INSERT INTO crawlt_sessionid (sessionid) VALUES $value";                
                $requete = mysql_query($sql, $connexion) or die("MySQL query error");                  
                }
              else
                {
                $listsessionid=array();
                }
                   
             //update the crawlt_config table 
            $sql ="UPDATE crawlt_config SET sessionid='".sql_quote($crawltsessionid)."'";
            $requete = mysql_query($sql, $connexion) or die("MySQL query error");   
  
            }
            
            		
		echo"<h1>".	$language['admin']."</h1>\n";
		echo"<table><tr><td width=\"550px\" valign=\"top\">";
		
		
		echo"<h5><img src=\"./images/page_white_php.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=16\">".$language['see_tag']."</a></h5><br>\n";
		
		
		echo"<h5><img src=\"./images/tick.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=11\">".$language['crawler_test_creation']."</a></h5>\n";		
		echo"<h5><img src=\"./images/cancel.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=12\">".$language['crawler_test_suppress']."</a></h5><br>\n";			

    echo"<h5><img src=\"./images/transmit_add.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=13\">".$language['update_crawler']."</a></h5>\n";
    echo"<h5><img src=\"./images/transmit_add.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=27\">".$language['update_attack']."</a></h5>\n";
    echo"<h5><img src=\"./images/database_add.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=31\">".$language['goodsite_update']."</a></h5><br>\n";
        
		echo"<h5><img src=\"./images/email.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=20\">".$language['mail']."</a></h5>\n";
		echo"<h5><img src=\"./images/clock.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=18\">".$language['time_set_up']."</a></h5>\n";
		echo"<h5><img src=\"./images/calendar_view_week.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=22\">".$language['firstweekday-title']."</a></h5>\n";
		echo"<h5><img src=\"./images/lock_open.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=21\">".$language['public']."</a></h5>\n";
 		echo"<h5><img src=\"./images/language.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=25\">".$language['choose_language']."</a></h5><br>\n";

 		echo"<h5><img src=\"./images/user_edit.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=30\">".$language['change_password']."</a></h5>\n";		
		echo"<h5><img src=\"./images/user_add.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=6\">".$language['user_create']."</a></h5>\n";
		echo"<h5><img src=\"./images/user_add.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=7\">".$language['user_site_create']."</a></h5>\n";
		echo"<h5><img src=\"./images/world_add.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=4\">".$language['new_site']."</a></h5>\n";
		echo"<h5><img src=\"./images/world_edit.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=23\">".$language['modif_site']."</a></h5>\n";					
		echo"<h5><img src=\"./images/database_add.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=2\">".$language['new_crawler']."</a></h5><br>\n";
				
		echo"<h5><img src=\"./images/user_delete.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=8\">".$language['user_suppress']."</a></h5>\n";
		echo"<h5><img src=\"./images/world_delete.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=9\">".$language['site_suppress']."</a></h5>\n";
		echo"<h5><img src=\"./images/database_delete.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=10\">".$language['crawler_suppress']."</a></h5>\n";
		echo"<h5><img src=\"./images/database_delete.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=19\">".$language['ip_suppress']."</a></h5><br>\n";		
		
		echo"<h5><img src=\"./images/database.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=26\">".$language['admin_database']."</a></h5>\n";		
		echo"<h5><img src=\"./images/compress.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=17\">".$language['data_suppress']."</a></h5>\n";			
		echo"<h5><img src=\"./images/database_delete.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=24\">".$language['data_human_suppress']."</a></h5><br>\n";
		
		echo"</td><td valign=\"top\" width=\"450px\">";
		
		if($crawltlang=='french' OR $crawltlang=='frenchiso')
            {
            echo"<h2>CrawlTrack infos<br><iframe name=\"I1\" src=\"http://www.crawltrack.fr/news/crawltrack-news-fr.htm\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"300px\" height=\"150px\"></iframe></h2>\n";
            }
        else
            {
            echo"<h2>CrawlTrack news<br><iframe name=\"I1\" src=\"http://www.crawltrack.fr/news/crawltrack-news-en.htm\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"300px\" height=\"150px\"></iframe></h2>\n";
            } 
                   

		echo"<br><h2>".$language['display_parameters']."</h2>";
		
		echo"<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px;\" >\n";
		echo"<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">\n";

		echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"98\">\n";		
		echo $language['numberrowdisplay']."<input name='rowdisplay'  value='$rowdisplay' type='text' maxlength='5' size='5px' style=\" font-size:13px; font-weight:bold; color: #003399;
		font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; float:left\"/><input name='ok' type='submit'  value=' OK ' size='20' style=\" float:left\">\n";
		echo"</form><br><br>\n";
					

		echo"<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">\n";

		echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"99\">\n";		
		echo $language['ordertype']."<select onchange=\"form.submit()\" size=\"1\" name=\"order\"  style=\" font-size:13px; font-weight:bold; color: #003399;
		font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; float:left\">\n";
	
        if($order==0)
            {
            echo"<option value=\"0\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbydate']."</option>\n";
            }
        else
            {
            echo"<option value=\"0\" style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbydate']."</option>\n";
            }		
		
        if($order==1 OR $order==4)
            {
            echo"<option value=\"1\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbypagesview']."</option>\n";
            }
        else
            {
            echo"<option value=\"1\" style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbypagesview']."</option>\n";
            }		
		
        if($order==2)
            {
            echo"<option value=\"2\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbyvisites']."</option>\n";
            }
        else
            {
            echo"<option value=\"2\" style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbyvisites']."</option>\n";
            }		
		
        if($order==3)
            {
            echo"<option value=\"3\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbyname']."</option>\n";
            }
        else
            {
            echo"<option value=\"3\" style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">".$language['orderbyname']."</option>\n";
            }		
		
		echo"</select></form><br>&nbsp;\n";
		
		
		echo"<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";

		echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"97\">\n";		
        echo "Email:<br>\n";		
		
		if($crawltmailishtml==1)
            {
            echo"<input type=\"radio\" name=\"typemail\" value=\"1\" checked>HTML &nbsp;&nbsp;\n";
            echo"<input type=\"radio\" name=\"typemail\" value=\"2\">Text\n";
            }
        else 
            {
            echo"<input type=\"radio\" name=\"typemail\" value=\"1\">HTML &nbsp;&nbsp;\n";
            echo"<input type=\"radio\" name=\"typemail\" value=\"2\" checked>Text\n";            
            }
        echo"<input name='ok' type='submit'  value=' OK ' size='20' >\n";
        echo"</form>&nbsp;\n";
		
		
		echo"<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";

		echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"96\">\n";			
        echo "Charset:<br>\n";		
		
		if($crawltcharset==1)
            {
            echo"<input type=\"radio\" name=\"charset\" value=\"1\" checked>utf-8 &nbsp;&nbsp;\n";
            echo"<input type=\"radio\" name=\"charset\" value=\"2\">iso-8859-1\n";
            }
        else 
            {
            echo"<input type=\"radio\" name=\"charset\" value=\"1\">utf-8 &nbsp;&nbsp;\n";
            echo"<input type=\"radio\" name=\"charset\" value=\"2\" checked>iso-8859-1\n";            
            }
        echo"<input name='ok' type='submit'  value=' OK ' size='20' >\n";
        echo"</form></div>&nbsp;\n";	
		
		
		echo"<br><h2>".$language['attack_parameters']."</h2>";
		echo"<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px;\" >\n";
				
		echo"<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";

		echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"100\">\n";		
    echo $language['attack_action'].":<br><br>\n";		
		
		if($crawltblockattack==1)
            {
            echo"<input type=\"radio\" name=\"blockattack\" value=\"1\" checked>".$language['attack_block']." <br>\n";
            echo"<input type=\"radio\" name=\"blockattack\" value=\"0\">".$language['attack_no_block']."\n";
            }
        else 
            {
            echo"<input type=\"radio\" name=\"blockattack\" value=\"1\">".$language['attack_block']." <br>\n";
            echo"<input type=\"radio\" name=\"blockattack\" value=\"0\" checked>".$language['attack_no_block']."\n";            
            }
        echo"<br><div width=\"100%\" align=\"right\"><input name='ok' type='submit'  value=' OK ' size='20' >&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
        echo"</form>&nbsp;\n";		
				echo"<br><h3>".$language['attack_block_alert']."</h3>";
		
	
		echo"</div>";
		
		
		echo"<br><h2>".$language['session_id_parameters']."</h2>";
		echo"<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px;\" >\n";
				
		echo"<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
            font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";

		echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"101\">\n";		
    echo $language['remove_session_id'].":<br><br>\n";		
		
		if($crawltsessionid==1)
            {
            echo"<input type=\"radio\" name=\"sessionid\" value=\"1\" checked>".$language['yes']." <br>\n";
            echo"<input type=\"radio\" name=\"sessionid\" value=\"0\">".$language['no']."\n";
            }
        else 
            {
            echo"<input type=\"radio\" name=\"sessionid\" value=\"1\">".$language['yes']." <br>\n";
            echo"<input type=\"radio\" name=\"sessionid\" value=\"0\" checked>".$language['no']."\n";            
            }
        echo "<br><br>".$language['session_id_used'].":";
        echo"<table width=\"100%\"><tr><td width=\"50%\" valign=\"top\">";
        if(in_array('PHPSESSID',$listsessionid))
            {
            echo"<input type=\"checkbox\" name=\"sessionid1\" value=\"1\" checked>PHPSESSID<br>\n";
            }
        else
            {
            echo"<input type=\"checkbox\" name=\"sessionid1\" value=\"1\">PHPSESSID<br>\n";
            }
        if(in_array('phpsessid',$listsessionid))
            {
            echo"<input  type=\"checkbox\" name=\"sessionid2\" value=\"1\" checked>phpsessid<br>\n";
            }
        else
            {
            echo"<input  type=\"checkbox\" name=\"sessionid2\" value=\"1\">phpsessid<br>\n";
            }       
        if(in_array('ID',$listsessionid))
            {
            echo"<input type=\"checkbox\" name=\"sessionid3\" value=\"1\" checked>ID<br>\n";
            }
        else
            {
            echo"<input type=\"checkbox\" name=\"sessionid3\" value=\"1\">ID<br>\n";
            }          
        if(in_array('id',$listsessionid))
            {
            echo"<input type=\"checkbox\" name=\"sessionid4\" value=\"1\" checked>id<br>\n";
            }
        else
            {
            echo"<input type=\"checkbox\" name=\"sessionid4\" value=\"1\">id<br>\n";
            }            
        echo"</td><td valign=\"top\">"; 
        if(in_array('SID',$listsessionid))
            {
            echo"<input type=\"checkbox\" name=\"sessionid5\" value=\"1\" checked>SID<br>\n";
            }
        else
            {
            echo"<input type=\"checkbox\" name=\"sessionid5\" value=\"1\">SID<br>\n";
            }                
        if(in_array('sid',$listsessionid))
            {
            echo"<input type=\"checkbox\" name=\"sessionid6\" value=\"1\" checked>sid<br>\n";
            }
        else
            {
            echo"<input type=\"checkbox\" name=\"sessionid6\" value=\"1\">sid<br>\n";
            }         
        if(in_array('S',$listsessionid))
            {
            echo"<input type=\"checkbox\" name=\"sessionid7\" value=\"1\" checked>S<br>\n";
            }
        else
            {
            echo"<input type=\"checkbox\" name=\"sessionid7\" value=\"1\">S<br>\n";
            }         
        if(in_array('s',$listsessionid))
            {
            echo"<input type=\"checkbox\" name=\"sessionid8\" value=\"1\" checked>s<br>\n";
            }
        else
            {
            echo"<input type=\"checkbox\" name=\"sessionid8\" value=\"1\">s<br>\n";
            }                 
         
        echo"</td></tr></table>"; 
             
        echo"<div width=\"100%\" align=\"right\"><input name='ok' type='submit'  value=' OK ' size='20' >&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
        echo"</form>&nbsp;\n";		
				echo"<br><h3>".$language['session_id_alert']."</h3>";
		
	
		echo"</div>";		
		
		
		
		
		
		
		echo"</td></tr></table>";
		
		

		
		
		}
	
	

	}
else
	{
	if($validform==3)
		{
		include"include/admintag.php";
		}
	elseif($validform==16)
		{
		include"include/logochoice.php";
		}
	elseif($validform==30)
		{
		include"include/adminchangepassword.php";
		}					
	else
		{
		echo"<h1>".	$language['admin']."</h1>\n";
		echo"<h5><img src=\"./images/page_white_php.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=16\">".$language['see_tag']."</a></h5><br><br>\n";
		echo"<h5><img src=\"./images/user_edit.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=30\">".$language['change_password']."</a></h5>\n";
		echo"<br><br><br><br><br><br><br><br>";
		}
	}
?>