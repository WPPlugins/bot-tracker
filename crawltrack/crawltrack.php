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
// file: crawltrack.php
//----------------------------------------------------------------------
error_reporting(0);
$crawltattack=0;
//connection to database
include("/home/desscesl/www.designpraxis.at/wordpress/wp-content/plugins/bot-tracker/crawltrack/include/configconnect.php");
$crawltconnexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword);
$selection = mysql_select_db($crawltdb);
//query to get the good site list
$sql = "SELECT host_site FROM crawlt_good_sites";
$requete = mysql_query($sql, $crawltconnexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requete);
if($nbrresult>=1)
{
while ($ligne = mysql_fetch_row($requete))
{
$crawltlistgoodsite[]=$ligne[0];
}
}
else
{
$crawltlistgoodsite=array();
}
 //query to get the session id list
$sql = "SELECT sessionid FROM crawlt_sessionid";
$requete = mysql_query($sql, $crawltconnexion) or die("MySQL query error");
$nbrresult=mysql_num_rows($requete);
if($nbrresult>=1)
{
while ($ligne = mysql_fetch_row($requete))
{
$crawltlistsessionid[]=$ligne[0];
}
}
else
{
$crawltlistsessionid=array();
}
//mysql escape function
function crawlt_sql_quote( $value )
{
if( get_magic_quotes_gpc() )
{
$value = stripslashes( $value );
}
//check if this function exists
if( function_exists( "mysql_real_escape_string" ) )
{
$value = mysql_real_escape_string( $value );
}
//for PHP version < 4.3.0 use addslashes
else
{
$value = addslashes( $value );
}
return $value;
}
//function url treatment (base on phpMyVisites processParams function)
function crawlturltreatment($url)
{
global $crawltlistsessionid;
if(count($crawltlistsessionid)==0)
{
$toReturn=$url;
}
else
{
$url2=ltrim($url,"/");
$urltreated =0;
$parseurl = parse_url('http://site.com/'.$url2);
if(isset($parseurl['query']))
{
$chaine=$parseurl['query'];
if(strpos($chaine, '&amp;'))
{
$queryEx = explode('&amp;', $chaine);
$separator='&amp;';
}
else
{
$queryEx = explode('&', $chaine);
$separator='&';
}
$return = $parseurl['path'] . '?';
foreach($queryEx as $value)
{
$varAndValue = explode('=', $value);
// include only parameters
if( sizeof($varAndValue) >= 2  && in_array($varAndValue[0], $crawltlistsessionid))
{
$urltreated=1;
}
elseif( sizeof($varAndValue) >= 2)
{
$return .= $varAndValue[0]."=";
for($i=1; $i< sizeof($varAndValue);$i++)
{
$return .= $varAndValue[$i]."=";
}
$return = rtrim($return,"=").$separator;
}
}
if(substr($return, strlen($return)-strlen($separator)) == $separator && $urltreated==1)
{
$toReturn = substr($return, 0, strlen($return)-strlen($separator));
}
else if(substr($return, strlen($return)-1) == '?'  && $urltreated==1)
{
$toReturn = substr($return, 0, strlen($return)-1);
}
elseif($urltreated==0)
{
$toReturn=$url;
}
}
else
{
$toReturn = $url;
}
}
return $toReturn;
}
//get information
if (!isset($_SERVER))
	{
	$_SERVER = $HTTP_SERVER_VARS;
	}
if(isset($_POST['agent']))
	{
	$crawltagent = $_POST['agent'];
	}
else
	{
	$crawltagent =$_SERVER['HTTP_USER_AGENT'];
	}
if(isset($_POST['ip']))
	{
	$crawltip = $_POST['ip'];
	}
else
	{
$crawltip = $_SERVER['REMOTE_ADDR'];
	}
if(isset($_POST['url']))
	{
	$crawlturl2 = $_POST['url'];
$crawltpostrequest=1;
	}
else
	{
	$crawlturl2 =$_SERVER['REQUEST_URI'];
$crawltpostrequest=0;
	}
	$crawlturl = crawlturltreatment($crawlturl2);
if(isset($_POST['site']))
	{
	$crawltsite = $_POST['site'];
	}
else
	{
   if(!isset($crawltsite))
	    {
       $crawltsite=$site;
	    }
	}
//treatment of ip to prepare the mysql request
$crawltcptip=1;
$crawltlgthip=strlen($crawltip);
while($crawltcptip <=$crawltlgthip)
{
$crawlttableip[]=substr($crawltip,0,$crawltcptip);
$crawltcptip++;
}
$crawltlistip=implode("','",$crawlttableip);
//get config parameters
$sqlcrawltconfig = "SELECT mail, datelastmail, timeshift, lang, addressmail, datelastseorequest, loop1, loop2, typemail, typecharset, blockattack FROM crawlt_config";
$requetecrawltconfig = mysql_query($sqlcrawltconfig, $crawltconnexion);
$nbrresultcrawlt=mysql_num_rows($requetecrawltconfig);
if($nbrresultcrawlt>=1)
    {
    $lignecrawlt = mysql_fetch_row($requetecrawltconfig);
    $crawltmail=$lignecrawlt[0];
    $crawltdatemail=$lignecrawlt[1];
    $crawlttime=$lignecrawlt[2];
    $crawltlang=$lignecrawlt[3];
    $crawltdest=$lignecrawlt[4];
    $crawltdatelastseorequest=$lignecrawlt[5];
    $crawltloop=$lignecrawlt[6];
    $crawltloop2=$lignecrawlt[7];
    $crawltmailishtml=$lignecrawlt[8];
    $crawltcharset=$lignecrawlt[9];
    $crawltblockattack=$lignecrawlt[10];
    if( $crawltcharset !=1)
       {
       $crawltlang = $crawltlang."iso";
       }
    $crawltcheck=1;
    }
// check if the user agent or the ip exist in the crawler table
$result = mysql_query("SELECT crawler_user_agent, crawler_ip,id_crawler FROM crawlt_crawler
 WHERE INSTR('".crawlt_sql_quote($crawltagent)."',crawler_user_agent) > 0
OR crawler_ip IN ('$crawltlistip') ",$crawltconnexion);
$num_rows = mysql_num_rows($result);
if ($num_rows>0)
{
if(ereg('http\:|%20select%20|%20like%20|%20or%20|%20where%20', strtolower(ltrim($crawlturl2,"h"))))
{
$crawltattack=1;
$crawlturl=$crawlturl2;
$crawltnbrattack=substr_count($crawlturl,'http:');
$crawltnbrgoodsite=0;
foreach($crawltlistgoodsite as $crawltgoodsite)
{
if(strpos($crawlturl, $crawltgoodsite))
{
$crawltnbrgoodsite++;
}
}
if($crawltnbrgoodsite == $crawltnbrattack && $crawltnbrattack !=0)
{
$crawltattack=0;
}
}
$crawltdata = mysql_fetch_row($result);
$crawltcrawler = $crawltdata[2];
$crawltdate  = date("Y-m-d H:i:s");
//check if the page already exist, if not add it to the table
$result2 = mysql_query("SELECT id_page FROM crawlt_pages WHERE url_page='".crawlt_sql_quote($crawlturl)."'",$crawltconnexion);
$num_rows2 = mysql_num_rows($result2);
if ($num_rows2>0)
{
$crawltdata2 = mysql_fetch_row($result2);
$crawltpage= $crawltdata2[0];
}
else
{
mysql_query("INSERT INTO crawlt_pages (url_page) VALUES ('".crawlt_sql_quote($crawlturl)."')",$crawltconnexion);
$crawltid_insert = mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID()",$crawltconnexion));
$crawltpage = $crawltid_insert[0];
}
//insertion of the visit datas in the visits database
mysql_query("INSERT INTO crawlt_visits (crawlt_site_id_site, crawlt_pages_id_page, crawlt_crawler_id_crawler, date, crawlt_ip_used) VALUES ('".crawlt_sql_quote($crawltsite)."', '".crawlt_sql_quote($crawltpage)."', '".crawlt_sql_quote($crawltcrawler)."', '".crawlt_sql_quote($crawltdate)."', '".crawlt_sql_quote($crawltip)."')",$crawltconnexion);
}
elseif(ereg('http\:|%20select%20|%20like%20|%20or%20|%20where%20', strtolower(ltrim($crawlturl2,"h"))))
{
$crawltattack=1;
$crawlturl=$crawlturl2;
$crawltnbrattack=substr_count($crawlturl,'http:');
$crawltnbrgoodsite=0;
foreach($crawltlistgoodsite as $crawltgoodsite)
{
if(strpos($crawlturl, $crawltgoodsite))
{
$crawltnbrgoodsite++;
}
}
if($crawltnbrgoodsite == $crawltnbrattack && $crawltnbrattack !=0)
{
$crawltattack=0;
}
$crawltdate  = date("Y-m-d H:i:s");
//check if the page already exist, if not add it to the table
$result2 = mysql_query("SELECT id_page FROM crawlt_pages WHERE url_page='".crawlt_sql_quote($crawlturl)."'",$crawltconnexion);
$num_rows2 = mysql_num_rows($result2);
if ($num_rows2>0)
{
$crawltdata2 = mysql_fetch_row($result2);
$crawltpage= $crawltdata2[0];
}
else
{
mysql_query("INSERT INTO crawlt_pages (url_page) VALUES ('".crawlt_sql_quote($crawlturl)."')",$crawltconnexion);
$crawltid_insert = mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID()",$crawltconnexion));
$crawltpage = $crawltid_insert[0];
}
//insertion of the visit datas in the visits database
mysql_query("INSERT INTO crawlt_visits (crawlt_site_id_site, crawlt_pages_id_page, crawlt_crawler_id_crawler, date, crawlt_ip_used) VALUES ('".crawlt_sql_quote($crawltsite)."', '".crawlt_sql_quote($crawltpage)."', '0', '".crawlt_sql_quote($crawltdate)."', '".crawlt_sql_quote($crawltip)."')",$crawltconnexion);
}
else
{
//case human visit
$crawltdate  = date("Y-m-d H:i:s");
if (!isset($_SERVER))
{
$_SERVER = $HTTP_SERVER_VARS;
}
if(isset($_POST['referer']))
{
$crawltreferer = $_POST['referer'];
$crawltrefereok=1;
}
elseif(isset($_SERVER['HTTP_REFERER']))
{
$crawltreferer=$_SERVER['HTTP_REFERER'];
$crawltrefereok=1;
}
else
{
$crawltrefereok=0;
}
if($crawltrefereok==1)
{
$crawltreferertreatment = parse_url($crawltreferer);
include("/home/desscesl/www.designpraxis.at/wordpress/wp-content/plugins/bot-tracker/crawltrack/include/searchenginelist.php");
$crawltsearchengine=0;
//test google
if( in_array("$crawltreferertreatment[host]",$crawltgooglelist)=='True')
{
$crawltsearchengine=1;
parse_str($crawltreferertreatment['query'],$crawlttabvar);
$crawltkeyword = $crawlttabvar['q'];
if($crawltkeyword=='')
{
$crawltsearchengine=0;
}
}
//test yahoo
elseif(in_array("$crawltreferertreatment[host]",$crawltyahoolist)=='True')
{
$crawltsearchengine=2;
parse_str($crawltreferertreatment['query'],$crawlttabvar);
$crawltkeyword = $crawlttabvar['p'];
if($crawltkeyword=='')
{
$crawltsearchengine=0;
}
}
//test msn
elseif(in_array("$crawltreferertreatment[host]",$crawltmsnlist)=='True')
{
$crawltsearchengine=3;
parse_str($crawltreferertreatment['query'],$crawlttabvar);
$crawltkeyword = $crawlttabvar['q'];
if($crawltkeyword=='')
{
$crawltsearchengine=0;
}
}
//test ask
elseif(in_array("$crawltreferertreatment[host]",$crawltasklist)=='True')
{
$crawltsearchengine=4;
parse_str($crawltreferertreatment['query'],$crawlttabvar);
$crawltkeyword = $crawlttabvar['q'];
if($crawltkeyword=='')
{
$crawltsearchengine=0;
}
}
//case visit send by one of the 4 searchengine
if($crawltsearchengine !=0)
{
//check if the keyword already exist, if not add it to the table
$result4 = mysql_query("SELECT id_keyword FROM crawlt_keyword WHERE keyword='".crawlt_sql_quote($crawltkeyword)."'");
$num_rows4 = mysql_num_rows($result4);
if ($num_rows4>0)
{
$crawltdata4 = mysql_fetch_row($result4);
$crawltkeywordid= $crawltdata4[0];
}
else
{
mysql_query("INSERT INTO crawlt_keyword (keyword) VALUES ('".crawlt_sql_quote($crawltkeyword)."')");
$crawltid_insert2 = mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID()"));
$crawltkeywordid = $crawltid_insert2[0];
}
//check if the page already exist, if not add it to the table
$result2 = mysql_query("SELECT id_page FROM crawlt_pages WHERE url_page='".crawlt_sql_quote($crawlturl)."'");
$num_rows2 = mysql_num_rows($result2);
if ($num_rows2>0)
{
$crawltdata2 = mysql_fetch_row($result2);
$crawltpage= $crawltdata2[0];
}
else
{
mysql_query("INSERT INTO crawlt_pages (url_page) VALUES ('".crawlt_sql_quote($crawlturl)."')");
$crawltid_insert = mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID()"));
$crawltpage = $crawltid_insert[0];
}
//insertion of the visit datas in the visits_human database
mysql_query("INSERT INTO crawlt_visits_human (crawlt_site_id_site, crawlt_keyword_id_keyword, crawlt_id_crawler, date, crawlt_id_page) VALUES ('".crawlt_sql_quote($crawltsite)."', '".crawlt_sql_quote($crawltkeywordid)."', '".crawlt_sql_quote($crawltsearchengine)."', '".crawlt_sql_quote($crawltdate)."', '".crawlt_sql_quote($crawltpage)."')");
}
}
}
//Email daily stats
//take in account timeshift
$crawltts = strtotime("today")-($crawlttime*3600);
$crawltdatetoday = date("j",$crawltts);
$crawltdatetoday2 = date("Y-m-d",$crawltts);
$url_crawlt="http://wordpress.designpraxis.at/wp-content/plugins/bot-tracker/crawltrack/";
if(($crawltdatetoday != $crawltdatelastseorequest) && $crawltcheck==1)
{
include("/home/desscesl/www.designpraxis.at/wordpress/wp-content/plugins/bot-tracker/crawltrack/include/searchenginesposition.php");
}
if(($crawltdatetoday != $crawltdatemail) && $crawltmail==1 && ($crawltdatetoday == $crawltdatelastseorequest) && $crawltcheck==1)
{
$crawltpath="/home/desscesl/www.designpraxis.at/wordpress/wp-content/plugins/bot-tracker/crawltrack";
include("/home/desscesl/www.designpraxis.at/wordpress/wp-content/plugins/bot-tracker/crawltrack/include/mail.php");
}
mysql_close($crawltconnexion);
if($crawltattack==1 && $crawltblockattack==1 && $crawltpostrequest==1)
{
echo "crawltrack1";
}
elseif($crawltattack==1 && $crawltblockattack==1)
{
$GLOBALS = array();
$_COOKIES = array();
$_FILES = array();
$_ENV = array();
$_REQUEST = array();
$_POST = array();
$_GET = array();
$_SERVER = array();
$_SESSION = array();
@session_destroy();
@mysql_close();
@header("Location:http://wordpress.designpraxis.at/wp-content/plugins/bot-tracker/crawltrack/html/noacces.htm");
echo"<head>";
echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=http://wordpress.designpraxis.at/wp-content/plugins/bot-tracker/crawltrack/html/noacces.htm'>";
echo"</head>";
} 
?>
