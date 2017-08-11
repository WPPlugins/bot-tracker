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
// file: origine-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------
error_reporting(0);
//get graph infos
$graphname= $_GET['graphname'];
//database connection		
include"../include/configconnect.php";
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//get the functions files
$times=0;//give value just to avoid error in functions.php
$firstdayweek='Monday'; //give value just to avoid error in functions.php
$period=0;//give value just to avoid error in functions.php
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

foreach ($datatransfert as $key => $value)
  {
  $legend[] = $key;
  $values[]=$value;
  }
//build the graph
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

require_once "artichow/Pie.class.php";


$graph = new Graph(450, 175);

if(function_exists('imageantialias'))
    {
    $graph->setAntiAliasing(TRUE);
    }
else
    {
     $graph->setAntiAliasing(FALSE);   
    }
$graph->border->hide(TRUE);

$graph->shadow->setSize(5);
$graph->shadow->smooth(TRUE);

$graph->shadow->setPosition('SHADOW_LEFT_BOTTOM');
$graph->shadow->setColor(new DarkBlue);



$plot = new Pie($values);
$plot->setCenter(0.35, 0.35);
$plot->setSize(0.55, 0.6);
$plot->set3D(15);
$plot->setLabelPosition(10);
$plot->label->setColor(new DarkBlue);
if ($ttf=='ok')
    {
    $plot->label->setFont(new Tuffy(10));
    }
else
    {
    $plot->label->setFont(new Font(2));
    }
$plot->setBorder(new DarkBlue);

$plot->setLegend($legend);

$plot->legend->setPosition(1.7);
$plot->legend->shadow->setSize(0);
$plot->legend->setBackgroundColor(new White);
$plot->legend->border->hide(TRUE);
$plot->legend->setTextColor(new DarkBlue);
if ($ttf=='ok')
    {
    $plot->legend->setTextFont(new Tuffy(10));
    }
else
    {
    $plot->legend->setTextFont(new Font(2));
    }
$graph->add($plot);
$graph->draw();

?>