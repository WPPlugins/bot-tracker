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
// file: menumain.php
//----------------------------------------------------------------------
// menu base on a www.alsacreations.com css menu 
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

$crawlencode=urlencode($crawler);

echo"<div class=\"menumain\">\n";
echo"<div id=\"menug\">\n";	
echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre('smenu1');\"><a href=\"index.php?navig=1&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">".$language['crawler_name']."</a></dt>\n";
echo"			<dd id=\"smenu1\">\n";
echo"				<ul>\n";

if ($navig==2)
	{
	echo"					<li><a href=\"index.php?navig=2&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";	
	echo"					<li><a href=\"index.php?navig=2&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=2&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=2&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=2&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";	
	echo"					<li><a href=\"index.php?navig=2&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";	
	echo"					<li><a href=\"index.php?navig=9&amp;site=$site\">".$language['archive']."</a></li>\n";
	}
else
	{
	echo"					<li><a href=\"index.php?navig=1&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=1&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";	
	echo"					<li><a href=\"index.php?navig=1&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=1&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=1&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=1&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";	   
    echo"					<li><a href=\"index.php?navig=9&amp;site=$site&amp;graphpos=$graphpos\">".$language['archive']."</a></li>\n";
	}
echo"				</ul>\n";
echo"			</dd>\n";

echo"<noscript>\n";
echo"			<div class=\"nojava\">\n";
echo"				<ul>\n";

if ($navig==2)
	{
	echo"					<li><a href=\"index.php?navig=2&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";	
	echo"					<li><a href=\"index.php?navig=2&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=2&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=2&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=2&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";	
	echo"					<li><a href=\"index.php?navig=2&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";	
	}
else
	{
	echo"					<li><a href=\"index.php?navig=1&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";	
	echo"					<li><a href=\"index.php?navig=1&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=1&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=1&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=1&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=1&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
	}
echo"				</ul>\n";
echo"			</dd>\n";
echo"</noscript>\n";

echo"	</dl>\n";
echo"</div>\n";
echo"<div id=\"menum\">\n";
	
echo"	<dl>\n";
echo"		<dt onmouseover=\"javascript:montre('smenu2');\"><a href=\"index.php?navig=3&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">".$language['nbr_pages']."</a></dt>\n";

echo"			<dd id=\"smenu2\" >\n";
echo"				<ul>\n";
if ($navig==4)
	{
	echo"					<li><a href=\"index.php?navig=4&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";	
	}
else
	{
	echo"					<li><a href=\"index.php?navig=3&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
	}	
echo"				</ul>\n";
echo"			</dd>\n";

echo"<noscript>\n";
echo"			<div class=\"nojava\">\n";
echo"				<ul>\n";
if ($navig==4)
	{
	echo"					<li><a href=\"index.php?navig=4&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=4&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=4&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=4&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
	}
else
	{
	echo"					<li><a href=\"index.php?navig=3&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
	echo"					<li><a href=\"index.php?navig=3&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
	}	
echo"				</ul>\n";
echo"			</dd>\n";
echo"</noscript>\n";

echo"	</dl>\n";
echo"</div>\n";


echo"<div id=\"menum2\">\n";
	
echo"	<dl>\n";
echo"		<dt onmouseover=\"javascript:montre('smenu3');\"><a href=\"index.php?navig=8&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">".$language['origin']."</a></dt>\n";

echo"			<dd id=\"smenu3\">\n";
echo"				<ul>\n";

echo"					<li><a href=\"index.php?navig=8&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
echo"				</ul>\n";
echo"			</dd>\n";

echo"<noscript>\n";
echo"			<div class=\"nojava\">\n";
echo"				<ul>\n";

echo"					<li><a href=\"index.php?navig=8&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=8&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
echo"				</ul>\n";
echo"			</dd>\n";
echo"</noscript>\n";

echo"	</dl>\n";	
echo"</div>\n";



echo"<div id=\"menug2\">\n";	
echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre('smenu4');\"><a href=\"index.php?navig=11&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">".$language['index']."</a></dt>\n";

echo"			<dd id=\"smenu4\">\n";
echo"				<ul>\n";

echo"					<li><a href=\"index.php?navig=11&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
echo"				</ul>\n";
echo"			</dd>\n";

echo"<noscript>\n";
echo"			<div class=\"nojava\">\n";
echo"				<ul>\n";

echo"					<li><a href=\"index.php?navig=11&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
echo"					<li><a href=\"index.php?navig=11&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
echo"				</ul>\n";
echo"			</dd>\n";
echo"</noscript>\n";






echo"	</dl>\n";

echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre('smenu5');\"><a href=\"index.php?navig=12&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">".$language['keyword']."</a></dt>\n";
echo"			<dd id=\"smenu5\">\n";
echo"				<ul>\n";

if ($navig==16)
	{
    echo"					<li><a href=\"index.php?navig=16&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }
else
	{
    echo"					<li><a href=\"index.php?navig=12&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }
echo"				</ul>\n";
echo"			</dd>\n";

echo"<noscript>\n";
echo"			<div class=\"nojava\">\n";
echo"				<ul>\n";
if ($navig==16)
	{
    echo"					<li><a href=\"index.php?navig=16&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=16&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }
else
	{
    echo"					<li><a href=\"index.php?navig=12&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=12&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }
	
echo"				</ul>\n";
echo"			</dd>\n";
echo"</noscript>\n";
echo"	</dl>\n";
echo"</div>\n";
echo"<div id=\"menug3\">\n";
echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre('smenu6');\"><a href=\"index.php?navig=13&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">".$language['entry-page']."</a></dt>\n";
echo"			<dd id=\"smenu6\">\n";
echo"				<ul>\n";


if ($navig==14)
	{
    echo"					<li><a href=\"index.php?navig=14&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }
else
	{
    echo"					<li><a href=\"index.php?navig=13&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }



echo"				</ul>\n";
echo"			</dd>\n";

echo"<noscript>\n";
echo"			<div class=\"nojava\">\n";
echo"				<ul>\n";

if ($navig==14)
	{
    echo"					<li><a href=\"index.php?navig=14&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=14&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }
else
	{
    echo"					<li><a href=\"index.php?navig=13&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
    echo"					<li><a href=\"index.php?navig=13&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
    }
echo"				</ul>\n";
echo"			</dd>\n";
echo"</noscript>\n";
echo"	</dl>\n";

echo"</div>\n";

echo"<div id=\"menum3\">\n";
	
echo"	<dl>\n";
echo"		<dt onmouseover=\"javascript:montre('smenu7');\"><a href=\"index.php?navig=17&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">".$language['hacking']."</a></dt>\n";

echo"			<dd id=\"smenu7\">\n";
echo"				<ul>\n";

if ($navig==18)
	{
  echo"					<li><a href=\"index.php?navig=18&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
  }
elseif ($navig==19)
	{
  echo"					<li><a href=\"index.php?navig=19&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
  }
else
	{
  echo"					<li><a href=\"index.php?navig=17&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
  }
echo"				</ul>\n";
echo"			</dd>\n";

echo"<noscript>\n";
echo"			<div class=\"nojava\">\n";
echo"				<ul>\n";

if ($navig==17)
	{
  echo"					<li><a href=\"index.php?navig=17&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=17&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
  }
elseif ($navig==18)
	{
  echo"					<li><a href=\"index.php?navig=18&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=18&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
  }
elseif ($navig==19)
	{
  echo"					<li><a href=\"index.php?navig=19&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">".$language['today']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">".$language['8days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">".$language['days']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">".$language['month']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">".$language['one_year']."</a></li>\n";
  echo"					<li><a href=\"index.php?navig=19&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">".$language['since_beginning']."</a></li>\n";
  }
echo"				</ul>\n";
echo"			</dd>\n";
echo"</noscript>\n";

echo"	</dl>\n";	
echo"</div>\n";


echo"<div id=\"menud2\">\n";

echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=5&amp;site=$site&amp;graphpos=$graphpos\"><img src=\"./images/magnifier.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
echo"	</dl>\n";

echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre();\"><a href=\"./php/refresh.php?navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
echo"	</dl>\n";
	
echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=6&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/wrench.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
echo"	</dl>\n";


echo"	<dl>\n";
echo"		<dt onmouseover=\"javascript:montre();\" onclick=\"window.print()\"><a href=\"#\"><img src=\"./images/printer.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
echo"	</dl>\n";





echo"	<dl>\n";
if($crawltlang=='french' OR $crawltlang=='frenchiso')
	{	
	echo"		<dt onmouseover=\"javascript:montre();\"><a href=\"http://www.crawltrack.fr/fr/documentation.php\"><img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
	}
else
	{
	echo"		<dt onmouseover=\"javascript:montre();\"><a href=\"http://www.crawltrack.fr/documentation.php\"><img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
	}
echo"	</dl>\n";


if($crawltlang=='french' OR $crawltlang=='frenchiso')
	{
	echo"	<dl>\n";	
	echo"		<dt onmouseover=\"javascript:montre();\" onclick=\"return window.open('./html/infofr.htm','CrawlTrack','top=300,left=350,height=200,width=350')\"><a href=\"#\"><img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
	echo"	</dl>\n";
	}
else
	{
	echo"	<dl>\n";	
	echo"		<dt onmouseover=\"javascript:montre();\" onclick=\"return window.open('./html/infoen.htm','CrawlTrack','top=300,left=350,height=200,width=350')\"><a href=\"#\"><img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
	echo"	</dl>\n";
	}

echo"	<dl>\n";	
echo"		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=7\"><img src=\"./images/cross.png\" width=\"16\" height=\"16\" border=\"0\" ></a></dt>\n";
echo"	</dl>\n";
	
echo"</div>\n";
echo"<br><br><br>\n";
echo"</div>\n";

?>