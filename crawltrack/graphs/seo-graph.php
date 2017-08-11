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
// file:seo-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------
error_reporting(0);
//initialize array
$listlangcrawlt=array();
//get graph info
$typegraph = $_GET['typegraph'];
$period = $_GET['period'];
$graphname= $_GET['graphname'];

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
	echo"<h1>No Graph values availabe !!!!</h1>";
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

//legend and title text
$legend0= $language['ask'];
$legend1 = $language['google'];
$legend2= $language['msn']; 
$legend3= $language['yahoo'];

foreach ($datatransfert as $key => $value)
  {
  $axex[] = $key;
  }
if($period==1 or ($period>=300 && $period<400))
    {
    $today1=date("d", strtotime("today"));    
    }
if($period==3)
    {
    $today1=date("m", strtotime("today"));    
    }    
else
    {
    $today1=date("d-m-y", strtotime("today"));
    }  
  
  
  
if($typegraph =='link')
    {
        $titlegraph = $language['nbr_tot_link'];
    $i=0;
     foreach($axex as $data)
        {
        if($period==1)
            {
            $data2=explode(' ',$data);
            $data3=$data2[1];
            if($today1<7 && $data3>25)
                {
                $data3=$data3-31;
                }            
            if($data3<=$today1)
                {
                $cutdata=explode("-",$datatransfert[$data]);
                $msn[$i]=$cutdata[1];	
                $yahoo[$i]=$cutdata[0];
                }            
            }
        elseif($period==2)
            {
            if($data<=$today1)
                {
                $cutdata=explode("-",$datatransfert[$data]);
                $msn[$i]=$cutdata[1];	
                $yahoo[$i]=$cutdata[0];
                }            
            } 
        elseif($period==3)
            {
            $data2=explode('/',$data);
            $data3=$data2[0];      
            if($data3<=$today1)
                {
                $cutdata=explode("-",$datatransfert[$data]);
                $msn[$i]=$cutdata[1];	
                $yahoo[$i]=$cutdata[0];
                }            
            }                       
        else
            {
            $cutdata=explode("-",$datatransfert[$data]);
            $msn[$i]=$cutdata[1];	
            $yahoo[$i]=$cutdata[0];
            }
        $google[$i]=0;
        $i++;        
        }   
    }
elseif($typegraph =='bookmark')
    {
        $titlegraph = $language['nbr_tot_bookmark'];
    $i=0;
     foreach($axex as $data)
        {
        if($period==1)
            {
            $data2=explode(' ',$data);
            $data3=$data2[1];
            if($today1<7 && $data3>25)
                {
                $data3=$data3-31;
                }            
            if($data3<=$today1)
                {            
                $delicious[$i]=$datatransfert[$data];               	
                }            
            }
        elseif($period==2)
            {
           
            if($data<=$today1)
                {
                $delicious[$i]=$datatransfert[$data]; 
                }            
            }
        elseif($period==3)
            {
            $data2=explode('/',$data);
            $data3=$data2[0];      
            if($data3<=$today1)
                {
                $delicious[$i]=$datatransfert[$data]; 
                }            
            }                          
        else
            {
            $delicious[$i]=$datatransfert[$data]; 
            }
        $google[$i]=0;
        $i++;        
        }   
    }    
elseif($typegraph =='entry' OR $typegraph =='email')
    {
    $titlegraph = $language['nbr_tot_visit_seo'];
    foreach($axex as $data)
        {
        $cutdata=explode("-",$datatransfert[$data]);
        $google[]=$cutdata[0];
        $msn[]=$cutdata[1];	
        $yahoo[]=$cutdata[2];
        $ask[]=$cutdata[3];	
        }    
    }    
else
    {
    $titlegraph = $language['nbr_tot_pages_index'];
    $i=0;
     foreach($axex as $data)
        {
        if($period==1)
            {
            $data2=explode(' ',$data);
            $data3=$data2[1];
            if($today1<7 && $data3>25)
                {
                $data3=$data3-31;
                }
            if($data3<=$today1)
                {
                $cutdata=explode("-",$datatransfert[$data]);
                $msn[$i]=$cutdata[1];	
                $yahoo[$i]=$cutdata[0];
                }            
            }
        elseif($period==2)
            {           
            if($data<=$today1)
                {
                $cutdata=explode("-",$datatransfert[$data]);
                $msn[$i]=$cutdata[1];	
                $yahoo[$i]=$cutdata[0];
                }            
            } 
        elseif($period==3)
            {
            $data2=explode('/',$data);
            $data3=$data2[0];      
            if($data3<=$today1)
                {
                $cutdata=explode("-",$datatransfert[$data]);
                $msn[$i]=$cutdata[1];	
                $yahoo[$i]=$cutdata[0];
                }            
            }                        
        else
            {
            $cutdata=explode("-",$datatransfert[$data]);
            $msn[$i]=$cutdata[1];	
            $yahoo[$i]=$cutdata[0];
            }
        $google[$i]=0;
        $i++;       
        } 
           
    }  

//graph creation



//graph creation


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

$graph = new Graph(900, 300);


$graph->title->set($titlegraph);
if ($ttf=='ok')
    {
    $graph->title->setFont(new Tuffy(12));
    }
else
    {
    $graph->title->setFont(new Font(3));
    }


$graph->title->setColor(new DarkBlue);


$group = new PlotGroup();

$group->setBackgroundColor(new Color(173, 216, 230, 60));

$group->setSpace(2, 2, 0.1, 0);


$group->setPadding(50, 20, 30, 90);
if($typegraph =='link' OR $typegraph =='page')
    {
    require_once "artichow/LinePlot.class.php";
    if(function_exists('imageantialias'))
        {
        $graph->setAntiAliasing(TRUE);
        }
    else
        {
         $graph->setAntiAliasing(FALSE);   
        }  
    $plot = new LinePlot($msn);
    // Change line color
    $plot->setColor(new Color(0, 128, 0));
    // Change mark type
    $plot->mark->setType(MARK_SQUARE);
    $plot->mark->border->show();
    $plot->setThickness(4);   
    $group->add($plot);     
 	$group->legend->add($plot, $language['msn'], LEGEND_LINE);  
           
    $plot = new LinePlot($yahoo);
    // Change line color
    $plot->setColor(new Color(0, 0, 150));
    // Change mark type
    $plot->mark->setType(MARK_CIRCLE);
    $plot->mark->border->show();   
    $plot->setThickness(4);   
    $group->add($plot); 
 	$group->legend->add($plot, $language['yahoo'], LEGEND_LINE);     
  
    
    $plot = new LinePlot($google); 
    $group->add($plot);
    $group->legend->setBackgroundColor(new Color(255,255,255,0));
    $group->legend->setModel(LEGEND_MODEL_BOTTOM);
    $group->legend->setPosition(NULL, 0.87); 
    if ($ttf=='ok')
        {
        $group->legend->setTextFont(new Tuffy(10));
        }
    else
        {
        $group->legend->setTextFont(new Font(2));
        }       
    }
elseif($typegraph =='bookmark' )
    {
    require_once "artichow/LinePlot.class.php";
    if(function_exists('imageantialias'))
        {
        $graph->setAntiAliasing(TRUE);
        }
    else
        {
         $graph->setAntiAliasing(FALSE);   
        }  
    $plot = new LinePlot($delicious);
    // Change line color
    $plot->setColor(new Color(0, 128, 0));
    // Change mark type
    $plot->mark->setType(MARK_SQUARE);
    $plot->mark->border->show();
    $plot->setThickness(4);   
    $group->add($plot);     
 	$group->legend->add($plot, $language['delicious'], LEGEND_LINE); 

    $plot = new LinePlot($google); 
    $group->add($plot);
    $group->legend->setBackgroundColor(new Color(255,255,255,0));
    $group->legend->setModel(LEGEND_MODEL_BOTTOM);
    $group->legend->setPosition(NULL, 0.87); 
    if ($ttf=='ok')
        {
        $group->legend->setTextFont(new Tuffy(10));
        }
    else
        {
        $group->legend->setTextFont(new Font(2));
        }       
    }    
else
    {

//ask


$plot = new BarPlot($ask,1,4);


$debut = new Color(255, 255,0 );
$fin = new Color(215, 200, 0);
   

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

$group->legend->add($plot, $legend0, LEGEND_BACKGROUND); 

$group->add($plot);
//google


$plot = new BarPlot($google,2,4);


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

$group->legend->add($plot, $legend1, LEGEND_BACKGROUND); 

$group->add($plot);


//msn


$plot = new BarPlot($msn,3,4);


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



//yahoo


$plot = new BarPlot($yahoo,4,4);

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
$group->legend->add($plot, $legend3, LEGEND_BACKGROUND); 

$group->add($plot);


$group->legend->setBackgroundColor(new Color(255,255,255,0));
$group->legend->setModel(LEGEND_MODEL_BOTTOM);
$group->legend->setPosition(NULL, 0.87);

    }
//X axis label  
$group->axis->bottom->setLabelText($axex);
if($period==2 OR ($period>=100 && $period<200))
	{
    $group->axis->bottom->label->setAngle(45);	
	}


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


if ($ttf=='ok')
    {
    $group->axis->bottom->label->move(-10, 0);
    }
else
    {
    $group->axis->bottom->label->move(20, 0);
    }	
	

$graph->add($group);
$graph->draw();

?>