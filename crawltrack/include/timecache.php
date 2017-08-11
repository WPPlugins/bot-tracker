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
// file: timecache.php
//----------------------------------------------------------------------
echo"<div class=\"cache\" >\n";
//to display the cache hour
$timecache = time()-($times*3600);
$timecache = date("H:i",$timecache);
echo"".$language['page_cache'] .$timecache." \n";
echo"</div>\n";
?>