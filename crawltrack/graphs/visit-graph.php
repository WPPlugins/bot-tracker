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
// file: visit-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------
error_reporting(0);
//initialize array
$listlangcrawlt=array();
//get graph infos
$graphname= urlencode($_GET['graphname']);
$period = $_GET['period'];
$navig = $_GET['navig'];
 
//database connection		
include"../include/configconnect.php";
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//get the listlang files
include"../include/listlang.php";
//get the functions files
$times=0;//give value just to avoid error in functions.php
$firstdayweek='Monday'; //give value just to avoid error in functions.php
include"../include/functions.php";
//get graph values
$sql = "SELECT   graph_values FROM crawlt_graph
WHERE  name='".sql_quote($graphname)."'";

$requete = mysql_query($sql, $connexion) or die("MySQL query error");

$nbrresult=mysql_num_rows($requete);
if($nbrresult>=1)
    {	
    $ligne = mysql_fetch_array($requete,MYSQL_ASSOC);
    $data = $ligne['graph_values'];
    }
else
    {
	echo"<h1>No Graph values available !!!!</h1>";
	exit();    
    }
$datatransfert= unserialize(urldecode(stripslashes($data)));
//get language to use
$crawltlang = $_GET['crawltlang'];
//language file include
if(file_exists("../language/".$crawltlang.".php") && in_array($crawltlang,$listlangcrawlt))
    {
    include "../language/".$crawltlang.".php";
    }
else
    {
	echo"<h1>No language files available !!!!</h1>";
	exit();
    } 

//legend text
if($navig==17 OR $navig==18 OR $navig==19)
    {
    $legend1=$language['hacking'];
    $legend2=$language['crawler_ip_used'];    
    }
else
    {
    $legend1=$language['nbr_visits'];
    $legend2=$language['nbr_pages']; 
    $legend3=$language['crawler_name'];
    }

foreach ($datatransfert as $key => $value)
  {
  $axex[] = $key;
  }

//graph creation
//create table for graph

foreach($axex as $data)
	{
	$cutdata=explode("-",$datatransfert[$data]);
	$page2[]=$cutdata[0];
	$visit[]=$cutdata[1];	
	$crawl[]=$cutdata[2];
	}

//test to see if ttf font is available
$fontttf= gd_info();

if( @$fontttf['FreeType Linkage']=='with freetype')
    {
    $ttf='ok';
    }
else
    {
    $ttf='no-ok';
    }


require_once "artichow/BarPlot.class.php";
require_once "artichow/LinePlot.class.php";

$graph = new Graph(700, 300);




$group = new PlotGroup();

$group->setBackgroundColor(new Color(173, 216, 230, 60));

$group->setSpace(2, 2, 0.1, 0);


if($period==2 OR $period==3 OR ($period>=100 && $period<300) )
	{
	$group->setPadding(50, 85, 30, 60);
	}
else
	{
	$group->setPadding(50, 85, 30, 20);
	}

//visits

if($navig==2 OR $navig==4 OR $navig==17  OR $navig==18 OR $navig==19)
	{
	$plot = new BarPlot($visit,1,2);	
	}
else
	{
	$plot = new BarPlot($visit,1,3);
	}

$debut = new Color(0, 51, 153);
$fin = new Color(0, 191, 255);
   

$plot->setBarGradient(
	new LinearGradient(
      $debut,
      $fin,
		90
	)
);

$plot->setXAxisZero(TRUE);
$plot->setSpace(2, 2, 20, 0);
$plot->barShadow->setSize(2);
$plot->barShadow->setPosition(SHADOW_RIGHT_TOP);
$plot->barShadow->setColor(new Color(180, 180, 180, 10));
$plot->barShadow->smooth(TRUE);

//legend

$group->legend->add($plot, $legend1, LEGEND_BACKGROUND); 

$group->add($plot);

if($navig==4)
	{}
else
	{
	//pages viewed

if($navig==2)
	{
	$plot = new BarPlot($page2,2,2);	
	}
elseif($navig==17 OR $navig==18 OR $navig==19)
	{
	$plot = new BarPlot($crawl,2,2);	
	}	
else
	{
	$plot = new BarPlot($page2,2,3);
	}

	$debut = new Color(255, 0, 0);
	$fin = new Color(255, 215, 0);
   

	$plot->setBarGradient(
		new LinearGradient(
		$debut,
		$fin,
			90
		)
	);


	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(SHADOW_RIGHT_TOP);
	$plot->barShadow->setColor(new Color(180, 180, 180, 10));
	$plot->barShadow->smooth(TRUE);
 
	//legend
	$group->legend->add($plot, $legend2, LEGEND_BACKGROUND); 
	if ($ttf=='ok')
        {
        $group->legend->setTextFont(new Tuffy(10));
        }
    else
        {
        $group->legend->setTextFont(new Font(2));
        }	
    
	$group->add($plot);
	}

if($navig==2 OR $navig==17 OR $navig==18 OR $navig==19)
	{}
else
	{
	//crawler
	
	if($navig==4)
		{
		$plot = new BarPlot($crawl,2,2);	
		}
	else
		{
		$plot = new BarPlot($crawl,3,3);
		}


	$debut = new Color(0, 128, 0);
	$fin = new Color(144, 238, 144);
   

	$plot->setBarGradient(
		new LinearGradient(
		$debut,
		$fin,
			90
		)
	);

	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(SHADOW_RIGHT_TOP);
	$plot->barShadow->setColor(new Color(180, 180, 180, 10));
	$plot->barShadow->smooth(TRUE);

	

	//legend
	$group->legend->add($plot, $legend3, LEGEND_BACKGROUND); 

	$group->add($plot);
}

$group->legend->setBackgroundColor(new Color(255,255,255,0));
$group->legend->setPosition(0.995,0.2); 




//X axis label
if($period == 0 OR $period >= 1000)
    {
    for($i=0;$i<24;$i++)
        {
        $axex[$i]=$axex[$i]."h";
        }
    }

$group->axis->bottom->setLabelText($axex);

    
if ($ttf=='ok')
    {
    $group->axis->left->label->setFont(new Tuffy(8));
    $group->axis->bottom->label->setFont(new Tuffy(8));
    }
else
    {
    $group->axis->left->label->setFont(new Font(2));
    $group->axis->bottom->label->setFont(new Font(2));
    }
if($period==2 OR $period==3 OR ($period>=100 && $period<300))
	{
	$group->axis->bottom->label->setAngle(45);
    if ($ttf=='ok')
        {
        $group->axis->bottom->label->move(-10, 0);
        }
    else
        {
        $group->axis->bottom->label->move(20, 0);
        }	
	}

$graph->add($group);
$graph->draw();

?>