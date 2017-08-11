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
// file: index.php
//----------------------------------------------------------------------
error_reporting(0);
//initialize array
$listlangcrawlt=array();
//if already install get all the config datas
if(file_exists('include/configconnect.php'))
    {
    //connection file include
    require_once"include/configconnect.php";
    
    if(!isset($crawlthost)) //case old version (before 150)
        {
        $crawlthost=$host;
        $crawltuser=$user;
        $crawltpassword=$password;
        $crawltdb=$db;
        $crawltlang=$lang;
        $crawltpublic=0;
        $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
        $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");        
        
        } 
    else
        {
        $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
        $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
        
        $sqlconfig = "SELECT * FROM crawlt_config";
        
        $requeteconfig = mysql_query($sqlconfig, $connexion) or die("MySQL query error");
        
        $nbrresult=mysql_num_rows($requeteconfig);
        if($nbrresult>=1)
            {	
            $ligne = mysql_fetch_array($requeteconfig,MYSQL_ASSOC);
            $times = $ligne['timeshift'];
            $crawltpublic = $ligne['public'];
            $crawltmail = $ligne['mail'];
            $crawltlastday = $ligne['datelastmail'];
            $crawltdest = $ligne['addressmail'];
            $crawltlang = $ligne['lang'];
            $version = $ligne['version'];
            if($version>160)
                {
                $firstdayweek = $ligne['firstdayweek'];
                }
            if($version>171)
                {
                $datecleaning= $ligne['datelastcleaning'];                    
                }
            if($version>210)
                {
                $rowdisplay= $ligne['rowdisplay'];
                $order= $ligne['orderdisplay'];                    
                } 
             else
                {
                $rowdisplay= 30;
                $order= 0;                    
                } 
             if($version>220)
                {
                $crawltmailishtml= $ligne['typemail'];
                $crawltcharset= $ligne['typecharset'];                                    
                } 
             else
                {
                $crawltmailishtml= 1;
                $crawltcharset= 1;                   
                } 
             if($version>221)
                {
                $crawltblockattack= $ligne['blockattack'];
                $crawltsessionid= $ligne['sessionid'];                              
                } 
             else
                {
                $crawltblockattack= 0;
                $crawltsessionid=0;                   
                }                                                                                
            } 
        } 
    $charset=1;                                                          
    } 
else
    {
    $charset= 0;
    $crawltcharset= 1;
    }
require_once"include/post.php";
if($charset==1)
    {
    if( $crawltcharset !=1)
        { 
         $crawltlang = $crawltlang."iso";
        } 
    }
//for the install we need to give a value to $times
 if(!isset($times))
    { 
    $times=0;
    }
    
require_once"include/listlang.php";    
require_once"include/functions.php";

//language file include
if(file_exists("language/".$crawltlang.".php") && in_array($crawltlang,$listlangcrawlt))
    {
    require_once "language/".$crawltlang.".php";
    }
else
    {
    echo"<h1>No language files available !!!!</h1>";
    exit();
    } 

//version id
$versionid="230";
// do not modify
define('IN_CRAWLT', TRUE);
// session start 'crawlt'
session_name('crawlt');
session_start();
//if already install
if(file_exists('include/configconnect.php')  && $navig!=15 )
	{
	//mysql connexion close
    mysql_close($connexion);
    
	if($navig == 1)
		{
		$main="include/display-all-crawlers.php";
		}
	elseif($navig == 2)
		{
		$main="include/display-one-crawler.php";
		}
	elseif($navig == 3)
		{
		$main="include/display-all-pages.php";
		}
	elseif($navig == 4)
		{
		$main="include/display-one-page.php";
		}
	elseif($navig == 5)
		{
		$main="include/search.php";
		}
	elseif($navig == 6)
		{
		$main="include/admin.php";
		}
	elseif($navig == 7)
		{
		$main="include/index.htm"; // to avoid notice error in Apache logs
		session_destroy();
		header("Location:index.php");
		}	
	elseif($navig == 8)
		{
		$main="include/display-crawlers-info.php";
		}		
	elseif($navig == 9)
		{
		$main="include/archive.php";
		}
	elseif($navig == 10)
		{
		$main="include/updateurl.php";
		}
	elseif($navig == 11)
		{
		$main="include/display-seo.php";
		}
	elseif($navig == 12)
		{
		$main="include/display-keyword.php";
		}
	elseif($navig == 13)
		{
		$main="include/display-entrypage.php";
		}
	elseif($navig == 14)
		{
		$main="include/display-one-entrypage.php";
		}
	// 15 is used for installation	
	elseif($navig == 16)
		{
		$main="include/display-one-keyword.php";
		}
	elseif($navig == 17)
		{
		$main="include/display-hacking.php";
		}
	elseif($navig == 18)
		{
		$main="include/display-hacking-css.php";
		}	
	elseif($navig == 19)
		{
		$main="include/display-hacking-sql.php";
		}						
	else			
		{
		$main="include/display-all-crawlers.php";
		}					
	//  IF NO SESSION LOGIN
	if( !isset($_SESSION['userlogin']) && !isset($_SESSION['userpass']))
		{
		if($crawltpublic==1 && $navig !=6)
            {
            //case free access to the stats
            if(!isset($_SESSION['rightsite']))
                {            
                //clear the cache folder at the first entry on crawltrack to avoid to have it oversized
                $dir = dir('cache/');
                while (false !== $entry = $dir->read())
                    {
                    // Skip pointers
                    if ($entry == '.' || $entry == '..')
                        {
                        continue;
                        }
                     unlink("cache/$entry");
                    }            
                }            
            	// session start 'crawlt'
            if(!isset($_SESSION))
                {
                session_name('crawlt');
                session_start();
                $_SESSION['rightsite']="0";
                }
            else
                {
                $_SESSION['rightsite']="0";              
                }           
            //test to see if version is up-to-date
            if (!isset($version))
                {
                $version=100;
                }
            if($version==$versionid )
                {
                include"include/nocache.php";
                //installation is up-to-date, display stats		
                include"include/header.php";
                include"$main";
                include"include/footer.php";
                echo"</body>\n";
                echo"</html>\n";			
                
               if($navig==1 OR $navig==2 OR $navig==3 OR $navig==4 OR $navig==8 OR $navig==11 OR $navig==12 OR $navig==13 OR $navig==14 OR $navig==16 OR $navig==17 OR $navig==18 OR $navig==19) 
                    {
                    //close the cache function 
                    if(function_exists('fopen'))
                        {
                        close();
                        }
                    }                
                }
            else
                {
                //update the installation
                include"include/header.php";
                include"include/updatecrawltrack.php";
                include"include/footer.php";
                echo"</body>\n";
                echo"</html>\n";
                }
            }
        else
            {	
            //get values
            if(isset($_POST['userlogin']))
                {	
                $userlogin = htmlentities($_POST['userlogin']);
                }
            else
                {
                $userlogin = '';
                }
    
            if(isset($_POST['userpass']))
                {	
                $userpass = htmlentities($_POST['userpass']);
                }
            else
                {
                $userpass = '';
                }
            
            //access form
            include"include/header.php";
            
            echo"<div class=\"content\">\n";		
            if($crawltpublic==1)
                {
                echo"<h1>".$language['admin_protected']."</h1>\n";
                }
            else
                {
                echo"<h1>".$language['restrited_access']."</h1>\n";
                }
                        
            echo"<h2>".$language['enter_login']."</h2>\n";		
            echo"<div class=\"form\">\n";
            echo"<form action=\"php/login.php\" method=\"POST\" name=\"login\" >\n";
            echo"<table align=\"left\" width=\"400px\">\n";
            echo"<tr>\n";		
            echo"<td >".$language['login']."&nbsp;<input name='userlogin' value='$userlogin' type='text' maxlength='20' size='20'/></td></tr>\n";		
            echo"<tr><td></td></tr>\n";	
            echo"<tr><td>".$language['password']."&nbsp;<input name='userpass'  value='$userpass' type='password' maxlength='20' size='20'/></td</tr>\n";					
            if(isset($lang))
                {
                echo "<input type=\"hidden\" name ='lang' value='$lang'>\n";
                }
            else
                {
                echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
                }
            echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n"; 
            echo"<tr><td><input name='ok' type='submit'  value='OK' size='20'></td></tr>\n";
            echo"</table></form>\n";
            echo"<script type=\"text/javascript\"> document.forms[\"login\"].elements[\"userlogin\"].focus()</script>\n";
            echo"<br><br><br><br><br>\n";		
            echo"</div>\n";
            
            include"include/footer.php";	
            echo"</body>\n";
            echo"</html>\n";            
			}			
		}
	else
		{
		//test to see if version is up-to-date
		if (!isset($version))
			{
			$version=100;
			}
		if($version==$versionid)
			{
			include"include/nocache.php";
			//installation is up-to-date, display stats		
			include"include/header.php";
			include"$main";
			include"include/footer.php";
			echo"</body>\n";
            echo"</html>\n";			
			
           if($navig==1 OR $navig==2 OR $navig==3 OR $navig==4 OR $navig==8 OR $navig==11 OR $navig==12 OR $navig==13 OR $navig==14 OR $navig==16 OR $navig==17 OR $navig==18 OR $navig==19) 
                {
                //close the cache function 
                if(function_exists('fopen'))
                    {
                    close();
                    }
                }
			}
		else
			{
			//update the installation
			include"include/header.php";
			include"include/updatecrawltrack.php";
			include"include/footer.php";
			echo"</body>\n";
            echo"</html>\n";
			}			
			
		}
	}
else
	{	
	//display install
    $navig='';
	include"include/header.php";
	include"include/install.php";
	include"include/footer.php";	
	echo"</body>\n";
    echo"</html>\n";
	}

?>