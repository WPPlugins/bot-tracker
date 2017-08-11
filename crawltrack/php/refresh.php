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
// file: refresh.php
//----------------------------------------------------------------------
error_reporting(0);
//access control
// session start 'crawlt'
session_name('crawlt');
session_start();

if( !isset($_SESSION['rightsite']))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

//get url data

if(isset($_GET['navig']))
    {
    $navig = (int)$_GET['navig'];
    }
else
    {	
    echo"<h1>Hacking attempt !!!!</h1>";
	exit();
    }
if(isset($_GET['period']))
    {
    $period = (int)$_GET['period'];
    }
else
    {	
    echo"<h1>Hacking attempt !!!!</h1>";
	exit();
    }
if(isset($_GET['site']))
    {
    $site= (int)$_GET['site'];
    }
else
    {	
    echo"<h1>Hacking attempt !!!!</h1>";
	exit();
    }
if(isset($_GET['crawler']))
    {
    $crawler= $_GET['crawler'];
    }
else
    {	
    echo"<h1>Hacking attempt !!!!</h1>";
	exit();
    }
if(isset($_GET['graphpos']))
    {
    $graphpos= $_GET['graphpos'];
    }
else
    {	
    echo"<h1>Hacking attempt !!!!</h1>";
	exit();
    }


// include
include"../include/configconnect.php";

//database connection
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");

//clear graph table
$sqlgraph = "TRUNCATE TABLE crawlt_graph";
$requetegraph = mysql_query($sqlgraph, $connexion) or die("MySQL query error");

//mysql connexion close
mysql_close($connexion);
    
//clear the cache folder
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

// Clean up
$dir->close();

//call back the page
$crawlencode = urlencode($crawler);
$urlrefresh ="../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos";
header("Location:$urlrefresh");


?>