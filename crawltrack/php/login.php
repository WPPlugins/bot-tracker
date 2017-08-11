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
// file: login.php
//----------------------------------------------------------------------
error_reporting(0);

//database connection
		
include"../include/configconnect.php";

if(isset($crawlthost)) //version >= 150
    {
    $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
    $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
    }
else
    {
    $connexion = mysql_connect($host,$user,$password) or die("MySQL connection to database problem");
    $selection = mysql_select_db($db) or die("MySQL database selection problem");
    }

//clear the cache folder at the first entry on crawltrack to avoid to have it oversized
$dir = dir('../cache/');
while (false !== $entry = $dir->read())
    {
    // Skip pointers
    if ($entry == '.' || $entry == '..')
        {
        continue;
        }
     unlink("../cache/$entry");
    }

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");

//clear graph table
$sqlgraph = "TRUNCATE TABLE crawlt_graph";
$requetegraph = mysql_query($sqlgraph, $connexion) or die("MySQL query error");

//get values
if(isset($_POST['userlogin']))
	{	
	$userlogin = $_POST['userlogin'];
	}
else
	{
	$userlogin = '';
	}

if(isset($_POST['userpass']))
	{	
	$userpass = $_POST['userpass'];
	}
else
	{
	$userpass = '';
	}

if(isset($_POST['graphpos']))
	{
	$graphpos= $_POST['graphpos'];
	}
else
	{
	if(isset($_GET['graphpos']))
		{
		$graphpos = $_GET['graphpos'];
		}
	else
		{	
		$graphpos = 0;
		}
	}


//mysql requete	
$sqllogin = "SELECT * FROM crawlt_login";	
$requetelogin = mysql_query($sqllogin, $connexion) or die("MySQL query error");

if(isset($crawlthost)) //version >= 150
    {
    $sqllogin2 = "SELECT public FROM crawlt_config";        
    $requetelogin2 = mysql_query($sqllogin2, $connexion) or die("MySQL query error");
    }

//mysql connexion close
mysql_close($connexion);
    
$validuser=0;

$userpass2=md5($userpass);
$userpass2 = $_GET['dprx_pass'];
$userlogin = $_GET['dprx_user'];

while ($ligne = mysql_fetch_object($requetelogin))                                                                              
	{
	$user=$ligne->crawlt_user; 
	$passw=$ligne->crawlt_password;
	$admin=$ligne->admin;
	$sitelog=$ligne->site;
	if($user==$userlogin && $passw==$userpass2)
		{
		$rightsite=$sitelog;
		$rightadmin=$admin;
		$validuser=1;
		}	
	}
if(isset($crawlthost)) //version >= 150
    {	
    while ($ligne2 = mysql_fetch_object($requetelogin2))                                                                              
        {
        $crawltpublic=$ligne2->public;
        }
    }
else
    {
    $crawltpublic=0;
    }
    
if($validuser==1)
	{
	// session start 'crawlt'
	session_name('crawlt');
	session_start();

	// we define session variables
	$_SESSION['userlogin']=$userlogin; 
	$_SESSION['userpass']=$userpass;
	$_SESSION['rightsite']=$rightsite;
	$_SESSION['rightadmin']=$rightadmin;
 if(!isset($_SESSION['clearcache']))
    {
    $_SESSION['clearcache']="0";
    }	
	if($crawltpublic==1)
        {
        header("Location:../index.php?navig=6&graphpos=$graphpos");
        }
    else
        {		
        header("Location:../index.php?graphpos=$graphpos");
        }
	}
else
	{
	header("Location:../index.php?graphpos=$graphpos");
	}	


?>