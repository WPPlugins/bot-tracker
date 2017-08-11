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
// file: page-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------
error_reporting(0);
//initialize array
$listlangcrawlt=array();
//get graph tvalues	
$nbrpageview = $_GET['nbrpageview'];	
$nbrpagestotal = $_GET['nbrpagestotal'];
$crawltlang = $_GET['crawltlang'];
if(isset($_GET['navig']))
  {
  $navig = (int)$_GET['navig'];
  }
else
  {	
  $navig = 1;
  }
//get the listlang files
include"../include/listlang.php";
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

$values[0]=$nbrpageview;
$values[1]=($nbrpagestotal-$nbrpageview);

if($navig==17)
  {
  $values[0]=$nbrpageview;
  $values[1]=$nbrpagestotal;  
  $legend[0]=$language['hacking3'];
  $legend[1]=$language['hacking4'];
  }
else
  {
  $values[0]=$nbrpageview;
  $values[1]=($nbrpagestotal-$nbrpageview);
  $legend[0]=$language['pc-page-view'];
  $legend[1]=$language['pc-page-noview'];
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


$graph = new Graph(500, 175);

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
$plot->setCenter(0.3, 0.35);
$plot->setSize(0.52, 0.55);
$plot->set3D(15);
$plot->setLabelPosition(10);
$plot->label->setColor(new DarkBlue);
if ($ttf=='ok')
    {
    $plot->label->setFont(new Tuffy(9));
    }
else
    {
    $plot->label->setFont(new Font(2));
    }
$plot->setBorder(new DarkBlue);

$plot->setLegend($legend);

$plot->legend->setPosition(1.84);
$plot->legend->shadow->setSize(0);
$plot->legend->setBackgroundColor(new White);
$plot->legend->border->hide(TRUE);
$plot->legend->setTextColor(new DarkBlue);
if ($ttf=='ok')
    {
    $plot->legend->setTextFont(new Tuffy(9));
    }
else
    {
    $plot->legend->setTextFont(new Font(2));
    }
$graph->add($plot);
$graph->draw();

?>