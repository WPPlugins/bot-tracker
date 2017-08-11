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
// file: testcrawlercreation.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}

//valid form

if($validlogin==1)
	{
		
	//database connection
    $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
    $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");		
		
	//check if crawler already exist
		
	$sqlexist = "SELECT * FROM crawlt_crawler
	WHERE crawler_name='Test-CrawlTrack'";
	
	$requeteexist = mysql_query($sqlexist, $connexion) or die("MySQL query error");
		
	$nbrresult=mysql_num_rows($requeteexist);
		
	if($nbrresult>=1)
		{
		$ligne = mysql_fetch_object($requeteexist);
		//crawler already exist
		echo"<br><br><h2>".$language['crawler_test_creation']."</h2>\n";			
		echo"<h1>".$language['exist']."</h1>\n";
			
		echo"<div class=\"form\">\n";
		echo"<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";			
		echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
		echo"</form>\n";
		echo"</div>\n";				
							
		}
	else
		{
		//crawler didn't exist we can add the crawler in the database	
		
		//find user agent to use
		if (!isset($_SERVER))
			{
			$_SERVER = $HTTP_SERVER_VARS;
			}

		$agent2 =$_SERVER['HTTP_USER_AGENT'];
		
		$sqlcrawler="INSERT INTO crawlt_crawler (crawler_user_agent,crawler_name,crawler_url,crawler_info,crawler_ip) VALUES ('".sql_quote($agent2)."','Test-Crawltrack','no-url','me','')";
		$requetecrawler = mysql_query($sqlcrawler, $connexion) or die("MySQL query error");



		//determine the path to the nocache file
		if (isset($_SERVER['PATH_TRANSLATED']) && !empty($_SERVER['PATH_TRANSLATED']))
			{
			$path = dirname( $_SERVER['PATH_TRANSLATED'] );
			}
		 elseif (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME']))
			{
			$path = dirname( $_SERVER['SCRIPT_FILENAME'] );
			}
		else
			{
			$path = '.';
			}
		$filename=$path.'/include/nocache.php';
		
		
    //chmod the directory
   if(function_exists('chmod'))
        {
        @chmod($path.'/include', 0777);
        }		
		
        //suppress the files
        unlink($filename);	
        
        //recreate the new file to avoid caching to be able to see the test crawler
        @$content.="<?php\n";
        @$content.="\$nocachetest=1;\n";
		@$content.="?>\n";

				
		if ( $file = fopen($filename, "w") )
			{
			fwrite($file, $content);
			fclose($file);
			}
       if(function_exists('chmod'))
            {
            @chmod($path.'/include', 0705);
            }
            
            //emptied the cache table
			$sqlcache = "TRUNCATE TABLE crawlt_cache";
			$requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");


        //check is requete is successfull
		
		if($requetecrawler)
			{
			echo"<br><br><h1>".$language['crawler_test_creation']."</h1>\n";			
			echo"<p>".$language['crawler_ok']."</p>\n";				
			
			echo"<div class=\"form\">\n";
			echo"<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";			
			echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo"</form>\n";
			echo"</div>\n";				
			}
		else
			{
			echo"<br><br><h1>".$language['crawler_test_creation']."</h1>\n";			
			echo"<p>".$language['crawler_no_ok2']."</p>";
			
			echo"<div class=\"form\">\n";
			echo"<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";			
			echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo"</form>\n";
			echo"</div>\n";	
			}
		}

	}

//form

else
	{

		echo"<br><br><h1>".$language['crawler_test_creation']."</h1>\n";
		echo"<p>".$language['crawler_test_text']."</p>\n";
		echo"<p>".$language['crawler_test_text2']."</p><br>\n";

		
			echo"<div class=\"form\">\n";
			echo"<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='validform' value=\"11\">";
			echo "<input type=\"hidden\" name ='validlogin' value=\"1\">";									
			echo"<input name='ok' type='submit'  value='".$language['crawler_test_creation']."' size='20'>\n";
			echo"</form>\n";
			echo"</div>\n";
	}

?>