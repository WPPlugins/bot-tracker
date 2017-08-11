<?php
//----------------------------------------------------------------------
//  CrawlTrack 2.2.1
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.fr
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: configconnect.php
//----------------------------------------------------------------------

$gn = $_GET['graphname'];
$pr = $_GET['period'];
$nav = $_GET['navig'];
$tgr = $_GET['typegraph'];
include(dirname(__FILE__)."/../../../../../wp-config.php");
$crawltuser = DB_USER;
$crawltpassword = DB_PASSWORD;
$crawltdb = DB_NAME;
$crawlthost = DB_HOST;
$lang = "english";
$graphname = $gn;
$period = $pr;
$navig = $nav;
$typegraph = $tgr;
?>
