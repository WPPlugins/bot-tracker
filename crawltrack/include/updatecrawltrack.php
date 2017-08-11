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
// file: updatecrawltrack.php
//----------------------------------------------------------------------

//this file is needed to update from a previous release 

if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//connexion to database
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//----------------------------------------------------------------------------------------------------
//check if the crawlt_config table exit and if not create it
$table_exist1 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion) or die("MySQL query error"); 

while (list($tablename)=mysql_fetch_array($tables)) 
    {
    if($tablename == 'crawlt_config')
        {
        $table_exist1 = 1;
        }
    }
if($table_exist1 == 0)
    {
    //the table didn't exist, we can create it
    $result1 = mysql_query("CREATE TABLE crawlt_config (
      id_config SMALLINT UNSIGNED NOT NULL,
      timeshift SMALLINT  NULL,
      public SMALLINT UNSIGNED NULL,
      mail SMALLINT UNSIGNED NULL,
      datelastmail SMALLINT UNSIGNED NULL,
      addressmail VARCHAR(255) NULL,
      lang VARCHAR(20) NULL,
      version INTEGER UNSIGNED NULL,
      firstdayweek ENUM('Monday','Sunday') NOT NULL default 'Monday',
      datelastseorequest smallint(5) NOT NULL default '0',
      loop1 smallint(5) NOT NULL default '0',
      loop2 smallint(5) NOT NULL default '0',
      datelastcleaning datetime NOT NULL default '0000-00-00 00:00:00',
      rowdisplay smallint(5)  NULL default '30',
      orderdisplay smallint(5) NULL default '0',
      typemail smallint(5) NULL default '1',
      typecharset smallint(5) NULL default '1',
      blockattack smallint(5) NULL default '0', 
      sessionid smallint(5) NULL default '0',                         
      PRIMARY KEY(id_config)
    )") or die("MySQL query error");
    
    //insert the data in the table
    
        //give a value to $time for release before 1.4.0
    if(!isset($times))
        {
        $times=0;
        }
    //give a value to $mail for release before 1.5.0
    if(!isset($mail))
        {
        $mail=0;
        }
    //give a value to $dest for release before 1.5.0
    if(!isset($dest))
        {
        $dest='';
        }	           	
    //give a value to $public for release before 1.5.0
    if(!isset($public))
        {
        $public=0;
        }            
    //insert the data in the table
    if($result1==1)
        {
        $result2 =mysql_query("INSERT INTO crawlt_config (id_config, timeshift, public, mail, datelastmail,addressmail, lang, version, firstdayweek, rowdisplay, orderdisplay, typemail, typecharset, blockattack, sessionid) VALUES ('1','0','0','0','0','','".sql_quote($crawltlang)."','230','Monday','30','0','1','1','0','0')");
        }    
    }
else
    {
    $result1=1;
    $result2=1;
    }
 //----------------------------------------------------------------------------------------------------
// check if the firstdayweek column exist in the config table and if not add it
$result3=0;
$tableinfo1 = mysql_query("SHOW COLUMNS FROM crawlt_config");
while ($table1= mysql_fetch_assoc($tableinfo1))
    {
    $listcolumn1[]=$table1['Field'];
    }
if(in_array('firstdayweek',$listcolumn1))
    {
    $result3=1;
    }	
else
    {    
    //add the column  in the config table    
    $sqlupdate3="ALTER TABLE crawlt_config ADD firstdayweek ENUM('Monday','Sunday') NOT NULL default 'Monday'";
    $result3 = mysql_query($sqlupdate3, $connexion);
    }  
//----------------------------------------------------------------------------------------------------
// check if the datelastseorequest column exist in the config table and if not add it
if(in_array('datelastseorequest',$listcolumn1))
    {
    $result4=1;
    }	
else
    {    
    //add the  column  in the config table    
    $sqlupdate4="ALTER TABLE crawlt_config ADD datelastseorequest smallint(5) NOT NULL default '0'";
    $result4 = mysql_query($sqlupdate4, $connexion);
    } 
//----------------------------------------------------------------------------------------------------
// check if the loop column exist in the config table and if not add it
if(in_array('loop1',$listcolumn1))
    {
    $result5=1;
    }	
else
    {    
    //add the  column  in the config table    
    $sqlupdate5="ALTER TABLE crawlt_config ADD loop1 smallint(5) NOT NULL default '0'";
    $result5 = mysql_query($sqlupdate5, $connexion);
    } 
//----------------------------------------------------------------------------------------------------
// check if the loop2 column exist in the config table and if not add it
if(in_array('loop2',$listcolumn1))
    {
    $result6=1;
    }	
else
    {    
    //add the  column  in the config table    
    $sqlupdate6="ALTER TABLE crawlt_config ADD loop2 smallint(5) NOT NULL default '0'";
    $result6 = mysql_query($sqlupdate6, $connexion);
    } 
//----------------------------------------------------------------------------------------------------
// check if the datelastcleaning column exist in the config table and if not add it
if(in_array('datelastcleaning',$listcolumn1))
    {
    $result7=1;
    }	
else
    {    
    //add the  column  in the config table    
    $sqlupdate7="ALTER TABLE crawlt_config ADD datelastcleaning datetime NOT NULL default '0000-00-00 00:00:00'";
    $result7 = mysql_query($sqlupdate7, $connexion);
    } 
//----------------------------------------------------------------------------------------------------
//check if the crawlt_cache table exit and if not create it
$table_exist2 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion); 
while (list($tablename)=mysql_fetch_array($tables)) 
    {
    if($tablename == 'crawlt_cache')
        {
        $table_exist2 = 1;
        }
    }
if($table_exist2 == 0)
    {
    //the table didn't exist, we can create it
    $result8 = mysql_query("CREATE TABLE crawlt_cache (
      cachename VARCHAR(255) NOT NULL,
      time INT NULL,
      PRIMARY KEY(cachename)
    )");
    }
else
    {
    $result8=1;
    }  
//----------------------------------------------------------------------------------------------------
// check if the crawlt_ip_used column exist in the visits table and if not add it
$tableinfo2 = mysql_query("SHOW COLUMNS FROM crawlt_visits");
while ($table2= mysql_fetch_assoc($tableinfo2))
    {
    $listcolumn2[]=$table2['Field'];
    }
if(in_array('crawlt_ip_used',$listcolumn2))
    {
    $result9=1;
    $result12=1;    
    }	
else
    {
    //add the  column in the visits table
    $sqlupdate9="ALTER TABLE crawlt_visits ADD crawlt_ip_used VARCHAR(16)";
    $result9 = mysql_query($sqlupdate9, $connexion);

    $sqlupdate12="UPDATE crawlt_visits SET crawlt_ip_used=''";
    $result12 = mysql_query($sqlupdate12, $connexion);
    } 
//----------------------------------------------------------------------------------------------------
//check if the crawlt_ip_data table exit and if not create it and fill it (use $result 10 and 11)
include"include/createtableip.php";        
//----------------------------------------------------------------------------------------------------
//check if the crawlt_archive table exit and if not create it
$table_exist3 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion); 

while (list($tablename)=mysql_fetch_array($tables)) 
    {
    if($tablename == 'crawlt_archive')
        {
        $table_exist3 = 1;
        }
    }
if($table_exist3 == 0)
    {
    //the table didn't exist, we can create it
    $result13 = mysql_query("CREATE TABLE crawlt_archive (
      mois VARCHAR(20) NOT NULL,
      nbr_visits INTEGER UNSIGNED NULL,
      pages_view INTEGER UNSIGNED NULL,
      top_visits_1 VARCHAR(45) NULL,
      top_visits_2 VARCHAR(45) NULL,
      top_visits_3 VARCHAR(45) NULL,
      top_pages_view_1 VARCHAR(45) NULL,
      top_pages_view_2 VARCHAR(45) NULL,
      top_pages_view_3 VARCHAR(45) NULL,
      PRIMARY KEY(mois)
    )");
    }
else
    {
    $result13=1;
    }
//----------------------------------------------------------------------------------------------------
// check if the url column exist in the crawlt_site table and if not add it
$tableinfo3 = mysql_query("SHOW COLUMNS FROM crawlt_site");
while ($table3= mysql_fetch_assoc($tableinfo3))
    {
    $listcolumn3[]=$table3['Field'];
    }
if(in_array('url',$listcolumn3))
    {
    $urlok=1;
    $result14=1;
    }	
else
    {
    $urlok=0;
    //add the url column  in the crawlt_site table
    $sqlupdate14="ALTER TABLE crawlt_site ADD url VARCHAR(255)";
    $result14 = mysql_query($sqlupdate14, $connexion);
    }  
//----------------------------------------------------------------------------------------------------
//check if the crawlt_graph table exit and if not create it
$table_exist13 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion); 
while (list($tablename)=mysql_fetch_array($tables)) 
    {

    if($tablename == 'crawlt_graph')
        {
        $table_exist13 = 1;
        }
    }
if($table_exist13 == 0)
    {
    //the table didn't exist, we can create it
    $result15 = mysql_query("CREATE TABLE crawlt_graph (
      name varchar(255) NOT NULL default '',
      graph_values text NOT NULL,
      KEY name (name)
    )");
    }
else
    {
    $result15=1;
    }				
//----------------------------------------------------------------------------------------------------
//check if the crawlt_keyword table exit and if not create it				
$table_exist14 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion); 
while (list($tablename)=mysql_fetch_array($tables)) 
    {

    if($tablename == 'crawlt_keyword')
        {
        $table_exist14 = 1;
        }
    }
if($table_exist14 == 0)
    {
    //the table didn't exist, we can create it
    $result16 = mysql_query("CREATE TABLE crawlt_keyword (
    id_keyword int(10) unsigned NOT NULL auto_increment,
    keyword varchar(255) default NULL,
    PRIMARY KEY  (id_keyword)
    )");
    }
else
    {
    $result16=1;
    }
//----------------------------------------------------------------------------------------------------
//check if the crawlt_seo_position table exit and if not create it	
$table_exist15 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion); 
while (list($tablename)=mysql_fetch_array($tables)) 
    {
    if($tablename == 'crawlt_seo_position')
        {
        $table_exist15 = 1;
        }
    }
if($table_exist15 == 0)
    {
    //the table didn't exist, we can create it
    $result17 = mysql_query("CREATE TABLE crawlt_seo_position (
    date date default NULL,
    id_site smallint(5) NOT NULL default '0',
    linkyahoo int(10) unsigned default NULL,
    pageyahoo int(10) unsigned default NULL,
    linkmsn int(10) unsigned default NULL,
    pagemsn int(10) unsigned default NULL,
    nbrdelicious int(10) unsigned default '0',
    tagdelicious varchar(255) NOT NULL default ''
    )");
    }
else
    {
    $result17=1;
    }
//----------------------------------------------------------------------------------------------------
//check if the crawlt_visits_human table exit and if not create it	
$table_exist16 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion); 
while (list($tablename)=mysql_fetch_array($tables)) 
    {
    if($tablename == 'crawlt_visits_human')
        {
        $table_exist16 = 1;
        }
    }
if($table_exist16 == 0)
    {
    //the table didn't exist, we can create it
    $result18 = mysql_query("CREATE TABLE crawlt_visits_human (
    id_visit int(10) unsigned NOT NULL auto_increment,
    crawlt_site_id_site smallint(5) unsigned NOT NULL default '0',
    crawlt_keyword_id_keyword int(10) unsigned NOT NULL default '0',
    crawlt_id_crawler smallint(5) unsigned NOT NULL default '0',
    date datetime default NULL,
    crawlt_id_page int(10) NOT NULL default '0',
    PRIMARY KEY  (id_visit,crawlt_site_id_site,crawlt_keyword_id_keyword,crawlt_id_crawler),
    KEY crawlt_visits_human_FKIndex1 (crawlt_site_id_site),
    KEY crawlt_visits_human_FKIndex2 (crawlt_keyword_id_keyword),
    KEY crawlt_visits_human_FKIndex3 (crawlt_id_crawler)
    )");
    }
else
    {
    $result18=1;
    }
//----------------------------------------------------------------------------------------------------
//update configconnect.php file if version <150
$result19=0;
if ($version<150)
    {
    //update the configconnect file    
    //determine the path to the file
    if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME']))
        {
        $path = dirname( $_SERVER['SCRIPT_FILENAME'] );
        }
    else
        {
        $path = '.';
        }
    $filename=$path.'/include/configconnect.php';    
    $filedir=$path.'/include';
    
    //suppress existing file
    if(function_exists('chmod'))
        {
        @chmod($filedir, 0777);
        }
    unlink($filename);
    
    //create the new configconnect file    
    @$content.="<?php\n";
    @$content.="//----------------------------------------------------------------------\n";
    @$content.="//  CrawlTrack 2.3.0\n";
    @$content.="//----------------------------------------------------------------------\n";
    @$content.="// Crawler Tracker for website\n";
    @$content.="//----------------------------------------------------------------------\n";
    @$content.="// Author: Jean-Denis Brun\n";
    @$content.="//----------------------------------------------------------------------\n";
    @$content.="// Website: www.crawltrack.fr\n";
    @$content.="//----------------------------------------------------------------------\n";
    @$content.="// That script is distributed under GNU GPL license\n";
    @$content.="//----------------------------------------------------------------------\n";
    @$content.="// file: configconnect.php\n";
    @$content.="//----------------------------------------------------------------------\n";
    @$content.="\$crawltuser=\"$crawltuser\";\n";
    @$content.="\$crawltpassword=\"$crawltpassword\";\n";
    @$content.="\$crawltdb=\"$crawltdb\";\n";
    @$content.="\$crawlthost=\"$crawlthost\";\n";
    @$content.="?>\n";    
    
    if ( $file = fopen($filename, "w") )
        {
        fwrite($file, $content);
        fclose($file);
        $result19=1;
        }	
    }
else
    {
    $result19=1;
    }    
//----------------------------------------------------------------------------------------------------
//update crawltrack.php file if version <230
if ($version<230)
    {
    //update the crawltrack file    
    //determine the path to the file
    if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME']))
        {
        $path = dirname( $_SERVER['SCRIPT_FILENAME'] );
        }
    else
        {
        $path = '.';
        }
    $filename=$path.'/crawltrack.php';
    $filedir=$path;    
    //suppress existing file
    if(function_exists('chmod'))
        {
        @chmod($filedir, 0777);
        }
        
     if( @unlink($filename))  
        {
    
        //url calculation    
        $dom=$_SERVER["HTTP_HOST"]; 
        $file1=$_SERVER["PHP_SELF"];
        $size= strlen($file1);  
        $file1=substr($file1,-$size,-9);       
        $url_crawlt="http://".$dom.$file1; 
           
        @$content2.="<?php\n";
        @$content2.="//----------------------------------------------------------------------\n";
        @$content2.="//  CrawlTrack 2.3.0\n";
        @$content2.="//----------------------------------------------------------------------\n";
        @$content2.="// Crawler Tracker for website\n";
        @$content2.="//----------------------------------------------------------------------\n";
        @$content2.="// Author: Jean-Denis Brun\n";
        @$content2.="//----------------------------------------------------------------------\n";
        @$content2.="// Website: www.crawltrack.fr\n";		
        @$content2.="//----------------------------------------------------------------------\n";
        @$content2.="// That script is distributed under GNU GPL license\n";
        @$content2.="//----------------------------------------------------------------------\n";
        @$content2.="// file: crawltrack.php\n";
        @$content2.="//----------------------------------------------------------------------\n";
        @$content2.="error_reporting(0);\n";
        @$content2.="\$crawltattack=0;\n"; 
        @$content2.="//connection to database\n";	
        @$content2.="include(\"$path/include/configconnect.php\");\n";   	
        @$content2.="\$crawltconnexion = mysql_connect(\$crawlthost,\$crawltuser,\$crawltpassword);\n";
        @$content2.="\$selection = mysql_select_db(\$crawltdb);\n";	
        @$content2.="//query to get the good site list\n";
        @$content2.="\$sql = \"SELECT host_site FROM crawlt_good_sites\";\n";
        @$content2.="\$requete = mysql_query(\$sql, \$crawltconnexion) or die(\"MySQL query error\");\n";
        @$content2.="\$nbrresult=mysql_num_rows(\$requete);\n";
        @$content2.="if(\$nbrresult>=1)\n";
        @$content2.="{\n";	
        @$content2.="while (\$ligne = mysql_fetch_row(\$requete))\n";
        @$content2.="{\n";
        @$content2.="\$crawltlistgoodsite[]=\$ligne[0];\n";               
        @$content2.="}\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="\$crawltlistgoodsite=array();\n";
        @$content2.="}\n";
        @$content2.=" //query to get the session id list\n";
        @$content2.="\$sql = \"SELECT sessionid FROM crawlt_sessionid\";\n";
        @$content2.="\$requete = mysql_query(\$sql, \$crawltconnexion) or die(\"MySQL query error\");\n";
        @$content2.="\$nbrresult=mysql_num_rows(\$requete);\n";
        @$content2.="if(\$nbrresult>=1)\n";
        @$content2.="{\n";	
        @$content2.="while (\$ligne = mysql_fetch_row(\$requete))\n";
        @$content2.="{\n";
        @$content2.="\$crawltlistsessionid[]=\$ligne[0];\n";               
        @$content2.="}\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="\$crawltlistsessionid=array();\n";
        @$content2.="}\n";       				
        @$content2.="//mysql escape function\n";		
        @$content2.="function crawlt_sql_quote( \$value )\n";
        @$content2.="{\n";
        @$content2.="if( get_magic_quotes_gpc() )\n";
        @$content2.="{\n";
        @$content2.="\$value = stripslashes( \$value );\n";
        @$content2.="}\n";
        @$content2.="//check if this function exists\n";
        @$content2.="if( function_exists( \"mysql_real_escape_string\" ) )\n";
        @$content2.="{\n";
        @$content2.="\$value = mysql_real_escape_string( \$value );\n";
        @$content2.="}\n";
        @$content2.="//for PHP version < 4.3.0 use addslashes\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="\$value = addslashes( \$value );\n";
        @$content2.="}\n";
        @$content2.="return \$value;\n";
        @$content2.="}\n";
        @$content2.="//function url treatment (base on phpMyVisites processParams function)\n";
        @$content2.="function crawlturltreatment(\$url)\n";
        @$content2.="{\n";
        @$content2.="global \$crawltlistsessionid;\n";
        @$content2.="if(count(\$crawltlistsessionid)==0)\n";
        @$content2.="{\n";
        @$content2.="\$toReturn=\$url;\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";                
        @$content2.="\$url2=ltrim(\$url,\"/\");\n";
        @$content2.="\$urltreated =0;\n"; 
        @$content2.="\$parseurl = parse_url('http://site.com/'.\$url2);\n";    
        @$content2.="if(isset(\$parseurl['query']))\n";
        @$content2.="{\n";
        @$content2.="\$chaine=\$parseurl['query'];\n";
        @$content2.="if(strpos(\$chaine, '&amp;'))\n";
        @$content2.="{\n";
        @$content2.="\$queryEx = explode('&amp;', \$chaine);\n";
        @$content2.="\$separator='&amp;';\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="\$queryEx = explode('&', \$chaine);\n";
        @$content2.="\$separator='&';\n";
        @$content2.="}\n"; 
        @$content2.="\$return = \$parseurl['path'] . '?';\n";		
        @$content2.="foreach(\$queryEx as \$value)\n";
        @$content2.="{\n";
        @$content2.="\$varAndValue = explode('=', \$value);\n";        
        @$content2.="// include only parameters\n";
        @$content2.="if( sizeof(\$varAndValue) >= 2  && in_array(\$varAndValue[0], \$crawltlistsessionid))\n";
        @$content2.="{\n";
        @$content2.="\$urltreated=1;\n";	
        @$content2.="}\n";
        @$content2.="elseif( sizeof(\$varAndValue) >= 2)\n";
        @$content2.="{\n";
        @$content2.="\$return .= \$varAndValue[0].\"=\";\n";
        @$content2.="for(\$i=1; \$i< sizeof(\$varAndValue);\$i++)\n";
        @$content2.="{\n";
        @$content2.="\$return .= \$varAndValue[\$i].\"=\";\n";
        @$content2.="}\n";
        @$content2.="\$return = rtrim(\$return,\"=\").\$separator;\n";			
        @$content2.="}\n";
        @$content2.="}\n";		
        @$content2.="if(substr(\$return, strlen(\$return)-strlen(\$separator)) == \$separator && \$urltreated==1)\n";
        @$content2.="{\n";
        @$content2.="\$toReturn = substr(\$return, 0, strlen(\$return)-strlen(\$separator));\n";
        @$content2.="}\n";
        @$content2.="else if(substr(\$return, strlen(\$return)-1) == '?'  && \$urltreated==1)\n";
        @$content2.="{\n";
        @$content2.="\$toReturn = substr(\$return, 0, strlen(\$return)-1);\n";
        @$content2.="}\n";
        @$content2.="elseif(\$urltreated==0)\n";
        @$content2.="{\n";
        @$content2.="\$toReturn=\$url;\n";
        @$content2.="}\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="\$toReturn = \$url;\n";
        @$content2.="}\n"; 
        @$content2.="}\n";               
        @$content2.="return \$toReturn;\n";
        @$content2.="}\n";       		
        @$content2.="//get information\n";
        @$content2.="if (!isset(\$_SERVER))\n";
        @$content2.="	{\n";
        @$content2.="	\$_SERVER = \$HTTP_SERVER_VARS;\n";	
        @$content2.="	}\n";		
        @$content2.="if(isset(\$_POST['agent']))\n";
        @$content2.="	{\n";
        @$content2.="	\$crawltagent = \$_POST['agent'];\n";	
        @$content2.="	}\n";
        @$content2.="else\n";
        @$content2.="	{\n";
        @$content2.="	\$crawltagent =\$_SERVER['HTTP_USER_AGENT'];\n";	
        @$content2.="	}\n";
        @$content2.="if(isset(\$_POST['ip']))\n";
        @$content2.="	{\n";
        @$content2.="	\$crawltip = \$_POST['ip'];\n";	
        @$content2.="	}\n";
        @$content2.="else\n";
        @$content2.="	{\n";
        @$content2.="\$crawltip = \$_SERVER['REMOTE_ADDR'];\n";
        @$content2.="	}\n";													
        @$content2.="if(isset(\$_POST['url']))\n";
        @$content2.="	{\n";
        @$content2.="	\$crawlturl2 = \$_POST['url'];\n";        
        @$content2.="\$crawltpostrequest=1;\n";
        @$content2.="	}\n";
        @$content2.="else\n";
        @$content2.="	{\n";
        @$content2.="	\$crawlturl2 =\$_SERVER['REQUEST_URI'];\n";
        @$content2.="\$crawltpostrequest=0;\n";        	
        @$content2.="	}\n";
        @$content2.="	\$crawlturl = crawlturltreatment(\$crawlturl2);\n";        	
        @$content2.="if(isset(\$_POST['site']))\n";
        @$content2.="	{\n";
        @$content2.="	\$crawltsite = \$_POST['site'];\n";	
        @$content2.="	}\n";
        @$content2.="else\n";
        @$content2.="	{\n";
        @$content2.="   if(!isset(\$crawltsite))\n";
        @$content2.="	    {\n";
        @$content2.="       \$crawltsite=\$site;\n";
        @$content2.="	    }\n";					
        @$content2.="	}\n";			
        @$content2.="//treatment of ip to prepare the mysql request\n";
        @$content2.="\$crawltcptip=1;\n";
        @$content2.="\$crawltlgthip=strlen(\$crawltip);\n";
        @$content2.="while(\$crawltcptip <=\$crawltlgthip)\n";
        @$content2.="{\n";
        @$content2.="\$crawlttableip[]=substr(\$crawltip,0,\$crawltcptip);\n";
        @$content2.="\$crawltcptip++;\n";
        @$content2.="}\n";
        @$content2.="\$crawltlistip=implode(\"','\",\$crawlttableip);\n";
        @$content2.="//get config parameters\n";
        @$content2.="\$sqlcrawltconfig = \"SELECT mail, datelastmail, timeshift, lang, addressmail, datelastseorequest, loop1, loop2, typemail, typecharset, blockattack FROM crawlt_config\";\n";
        @$content2.="\$requetecrawltconfig = mysql_query(\$sqlcrawltconfig, \$crawltconnexion);\n";
        @$content2.="\$nbrresultcrawlt=mysql_num_rows(\$requetecrawltconfig);\n";
        @$content2.="if(\$nbrresultcrawlt>=1)\n";
        @$content2.="    {\n";	
        @$content2.="    \$lignecrawlt = mysql_fetch_row(\$requetecrawltconfig);\n";
        @$content2.="    \$crawltmail=\$lignecrawlt[0];\n";
        @$content2.="    \$crawltdatemail=\$lignecrawlt[1];\n";
        @$content2.="    \$crawlttime=\$lignecrawlt[2];\n";
        @$content2.="    \$crawltlang=\$lignecrawlt[3];\n";
        @$content2.="    \$crawltdest=\$lignecrawlt[4];\n";
        @$content2.="    \$crawltdatelastseorequest=\$lignecrawlt[5];\n";
        @$content2.="    \$crawltloop=\$lignecrawlt[6];\n"; 
        @$content2.="    \$crawltloop2=\$lignecrawlt[7];\n";
        @$content2.="    \$crawltmailishtml=\$lignecrawlt[8];\n";
        @$content2.="    \$crawltcharset=\$lignecrawlt[9];\n";
        @$content2.="    \$crawltblockattack=\$lignecrawlt[10];\n";
        @$content2.="    if( \$crawltcharset !=1)\n";
        @$content2.="       {\n"; 
        @$content2.="       \$crawltlang = \$crawltlang.\"iso\";\n";
        @$content2.="       }\n";                         
        @$content2.="    \$crawltcheck=1;\n";                       
        @$content2.="    }\n";        	             
        @$content2.="// check if the user agent or the ip exist in the crawler table\n";		
        @$content2.="\$result = mysql_query(\"SELECT crawler_user_agent, crawler_ip,id_crawler FROM crawlt_crawler\n"; 
        @$content2.=" WHERE INSTR('\".crawlt_sql_quote(\$crawltagent).\"',crawler_user_agent) > 0\n";
        @$content2.="OR crawler_ip IN ('\$crawltlistip') \",\$crawltconnexion);\n";
        @$content2.="\$num_rows = mysql_num_rows(\$result);\n";
        @$content2.="if (\$num_rows>0)\n";
        @$content2.="{\n";
        @$content2.="if(ereg('http\:|%20select%20|%20like%20|%20or%20|%20where%20', strtolower(ltrim(\$crawlturl2,\"h\"))))\n";
        @$content2.="{\n";
        @$content2.="\$crawltattack=1;\n";
        @$content2.="\$crawlturl=\$crawlturl2;\n";        
        @$content2.="\$crawltnbrattack=substr_count(\$crawlturl,'http:');\n";
        @$content2.="\$crawltnbrgoodsite=0;\n";
        @$content2.="foreach(\$crawltlistgoodsite as \$crawltgoodsite)\n";
        @$content2.="{\n";
        @$content2.="if(strpos(\$crawlturl, \$crawltgoodsite))\n";
        @$content2.="{\n";
        @$content2.="\$crawltnbrgoodsite++;\n";
        @$content2.="}\n";
        @$content2.="}\n";
        @$content2.="if(\$crawltnbrgoodsite == \$crawltnbrattack && \$crawltnbrattack !=0)\n";
        @$content2.="{\n";
        @$content2.="\$crawltattack=0;\n";
        @$content2.="}\n";
        @$content2.="}\n";         
        @$content2.="\$crawltdata = mysql_fetch_row(\$result);\n";
        @$content2.="\$crawltcrawler = \$crawltdata[2];\n";
        @$content2.="\$crawltdate  = date(\"Y-m-d H:i:s\");\n";
        @$content2.="//check if the page already exist, if not add it to the table\n";
        @$content2.="\$result2 = mysql_query(\"SELECT id_page FROM crawlt_pages WHERE url_page='\".crawlt_sql_quote(\$crawlturl).\"'\",\$crawltconnexion);\n";
        @$content2.="\$num_rows2 = mysql_num_rows(\$result2);\n";
        @$content2.="if (\$num_rows2>0)\n";
        @$content2.="{\n";
        @$content2.="\$crawltdata2 = mysql_fetch_row(\$result2);\n";	
        @$content2.="\$crawltpage= \$crawltdata2[0];\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="mysql_query(\"INSERT INTO crawlt_pages (url_page) VALUES ('\".crawlt_sql_quote(\$crawlturl).\"')\",\$crawltconnexion);\n";
        @$content2.="\$crawltid_insert = mysql_fetch_row(mysql_query(\"SELECT LAST_INSERT_ID()\",\$crawltconnexion));\n";
        @$content2.="\$crawltpage = \$crawltid_insert[0];\n";
        @$content2.="}\n";
        @$content2.="//insertion of the visit datas in the visits database\n";
        @$content2.="mysql_query(\"INSERT INTO crawlt_visits (crawlt_site_id_site, crawlt_pages_id_page, crawlt_crawler_id_crawler, date, crawlt_ip_used) VALUES ('\".crawlt_sql_quote(\$crawltsite).\"', '\".crawlt_sql_quote(\$crawltpage).\"', '\".crawlt_sql_quote(\$crawltcrawler).\"', '\".crawlt_sql_quote(\$crawltdate).\"', '\".crawlt_sql_quote(\$crawltip).\"')\",\$crawltconnexion);\n";      
        @$content2.="}\n";
        @$content2.="elseif(ereg('http\:|%20select%20|%20like%20|%20or%20|%20where%20', strtolower(ltrim(\$crawlturl2,\"h\"))))\n";  
        @$content2.="{\n";    
        @$content2.="\$crawltattack=1;\n";
        @$content2.="\$crawlturl=\$crawlturl2;\n";         
        @$content2.="\$crawltnbrattack=substr_count(\$crawlturl,'http:');\n";
        @$content2.="\$crawltnbrgoodsite=0;\n";
        @$content2.="foreach(\$crawltlistgoodsite as \$crawltgoodsite)\n";
        @$content2.="{\n";
        @$content2.="if(strpos(\$crawlturl, \$crawltgoodsite))\n";
        @$content2.="{\n";
        @$content2.="\$crawltnbrgoodsite++;\n";
        @$content2.="}\n";
        @$content2.="}\n";
        @$content2.="if(\$crawltnbrgoodsite == \$crawltnbrattack && \$crawltnbrattack !=0)\n";
        @$content2.="{\n";
        @$content2.="\$crawltattack=0;\n";
        @$content2.="}\n";              
        @$content2.="\$crawltdate  = date(\"Y-m-d H:i:s\");\n";        
        @$content2.="//check if the page already exist, if not add it to the table\n";
        @$content2.="\$result2 = mysql_query(\"SELECT id_page FROM crawlt_pages WHERE url_page='\".crawlt_sql_quote(\$crawlturl).\"'\",\$crawltconnexion);\n";
        @$content2.="\$num_rows2 = mysql_num_rows(\$result2);\n";
        @$content2.="if (\$num_rows2>0)\n";
        @$content2.="{\n";
        @$content2.="\$crawltdata2 = mysql_fetch_row(\$result2);\n";	
        @$content2.="\$crawltpage= \$crawltdata2[0];\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="mysql_query(\"INSERT INTO crawlt_pages (url_page) VALUES ('\".crawlt_sql_quote(\$crawlturl).\"')\",\$crawltconnexion);\n";
        @$content2.="\$crawltid_insert = mysql_fetch_row(mysql_query(\"SELECT LAST_INSERT_ID()\",\$crawltconnexion));\n";
        @$content2.="\$crawltpage = \$crawltid_insert[0];\n";
        @$content2.="}\n";
        @$content2.="//insertion of the visit datas in the visits database\n";
        @$content2.="mysql_query(\"INSERT INTO crawlt_visits (crawlt_site_id_site, crawlt_pages_id_page, crawlt_crawler_id_crawler, date, crawlt_ip_used) VALUES ('\".crawlt_sql_quote(\$crawltsite).\"', '\".crawlt_sql_quote(\$crawltpage).\"', '0', '\".crawlt_sql_quote(\$crawltdate).\"', '\".crawlt_sql_quote(\$crawltip).\"')\",\$crawltconnexion);\n";
        @$content2.="}\n";         
        @$content2.="else\n";
        @$content2.="{\n";        
        @$content2.="//case human visit\n";    
        @$content2.="\$crawltdate  = date(\"Y-m-d H:i:s\");\n";     
        @$content2.="if (!isset(\$_SERVER))\n";
        @$content2.="{\n";
        @$content2.="\$_SERVER = \$HTTP_SERVER_VARS;\n";
        @$content2.="}\n";
        @$content2.="if(isset(\$_POST['referer']))\n";
        @$content2.="{\n";
        @$content2.="\$crawltreferer = \$_POST['referer'];\n";
        @$content2.="\$crawltrefereok=1;\n";
        @$content2.="}\n";
        @$content2.="elseif(isset(\$_SERVER['HTTP_REFERER']))\n";
        @$content2.="{\n";
        @$content2.="\$crawltreferer=\$_SERVER['HTTP_REFERER'];\n";
        @$content2.="\$crawltrefereok=1;\n";        
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="\$crawltrefereok=0;\n";        
        @$content2.="}\n"; 
        @$content2.="if(\$crawltrefereok==1)\n";
        @$content2.="{\n";            
        @$content2.="\$crawltreferertreatment = parse_url(\$crawltreferer);\n";
        @$content2.="include(\"$path/include/searchenginelist.php\");\n";     
        @$content2.="\$crawltsearchengine=0;\n";    
        @$content2.="//test google\n";
        @$content2.="if( in_array(\"\$crawltreferertreatment[host]\",\$crawltgooglelist)=='True')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=1;\n";
        @$content2.="parse_str(\$crawltreferertreatment['query'],\$crawlttabvar);\n";
        @$content2.="\$crawltkeyword = \$crawlttabvar['q'];\n";
        @$content2.="if(\$crawltkeyword=='')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=0;\n";
        @$content2.="}\n";
        @$content2.="}\n";
        @$content2.="//test yahoo\n";
        @$content2.="elseif(in_array(\"\$crawltreferertreatment[host]\",\$crawltyahoolist)=='True')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=2;\n";
        @$content2.="parse_str(\$crawltreferertreatment['query'],\$crawlttabvar);\n";
        @$content2.="\$crawltkeyword = \$crawlttabvar['p'];\n";
        @$content2.="if(\$crawltkeyword=='')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=0;\n";
        @$content2.="}\n";       
        @$content2.="}\n";
        @$content2.="//test msn\n";
        @$content2.="elseif(in_array(\"\$crawltreferertreatment[host]\",\$crawltmsnlist)=='True')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=3;\n";
        @$content2.="parse_str(\$crawltreferertreatment['query'],\$crawlttabvar);\n";
        @$content2.="\$crawltkeyword = \$crawlttabvar['q'];\n";
        @$content2.="if(\$crawltkeyword=='')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=0;\n";
        @$content2.="}\n";      
        @$content2.="}\n";       
        @$content2.="//test ask\n";
        @$content2.="elseif(in_array(\"\$crawltreferertreatment[host]\",\$crawltasklist)=='True')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=4;\n";
        @$content2.="parse_str(\$crawltreferertreatment['query'],\$crawlttabvar);\n";
        @$content2.="\$crawltkeyword = \$crawlttabvar['q'];\n";
        @$content2.="if(\$crawltkeyword=='')\n";
        @$content2.="{\n";
        @$content2.="\$crawltsearchengine=0;\n";
        @$content2.="}\n";      
        @$content2.="}\n";        
        @$content2.="//case visit send by one of the 4 searchengine\n";     
        @$content2.="if(\$crawltsearchengine !=0)\n";
        @$content2.="{\n";            
        @$content2.="//check if the keyword already exist, if not add it to the table\n";
        @$content2.="\$result4 = mysql_query(\"SELECT id_keyword FROM crawlt_keyword WHERE keyword='\".crawlt_sql_quote(\$crawltkeyword).\"'\");\n";
        @$content2.="\$num_rows4 = mysql_num_rows(\$result4);\n";
        @$content2.="if (\$num_rows4>0)\n";
        @$content2.="{\n";
        @$content2.="\$crawltdata4 = mysql_fetch_row(\$result4);\n";
        @$content2.="\$crawltkeywordid= \$crawltdata4[0];\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="mysql_query(\"INSERT INTO crawlt_keyword (keyword) VALUES ('\".crawlt_sql_quote(\$crawltkeyword).\"')\");\n";
        @$content2.="\$crawltid_insert2 = mysql_fetch_row(mysql_query(\"SELECT LAST_INSERT_ID()\"));\n";
        @$content2.="\$crawltkeywordid = \$crawltid_insert2[0];\n";
        @$content2.="}\n";           
        @$content2.="//check if the page already exist, if not add it to the table\n";
        @$content2.="\$result2 = mysql_query(\"SELECT id_page FROM crawlt_pages WHERE url_page='\".crawlt_sql_quote(\$crawlturl).\"'\");\n";
        @$content2.="\$num_rows2 = mysql_num_rows(\$result2);\n";
        @$content2.="if (\$num_rows2>0)\n";
        @$content2.="{\n";
        @$content2.="\$crawltdata2 = mysql_fetch_row(\$result2);\n";
        @$content2.="\$crawltpage= \$crawltdata2[0];\n";
        @$content2.="}\n";
        @$content2.="else\n";
        @$content2.="{\n";
        @$content2.="mysql_query(\"INSERT INTO crawlt_pages (url_page) VALUES ('\".crawlt_sql_quote(\$crawlturl).\"')\");\n";
        @$content2.="\$crawltid_insert = mysql_fetch_row(mysql_query(\"SELECT LAST_INSERT_ID()\"));\n";
        @$content2.="\$crawltpage = \$crawltid_insert[0];\n";
        @$content2.="}\n";
        @$content2.="//insertion of the visit datas in the visits_human database\n";
        @$content2.="mysql_query(\"INSERT INTO crawlt_visits_human (crawlt_site_id_site, crawlt_keyword_id_keyword, crawlt_id_crawler, date, crawlt_id_page) VALUES ('\".crawlt_sql_quote(\$crawltsite).\"', '\".crawlt_sql_quote(\$crawltkeywordid).\"', '\".crawlt_sql_quote(\$crawltsearchengine).\"', '\".crawlt_sql_quote(\$crawltdate).\"', '\".crawlt_sql_quote(\$crawltpage).\"')\");\n";
        @$content2.="}\n";
        @$content2.="}\n"; 
        @$content2.="}\n";            
        @$content2.="//Email daily stats\n";
        @$content2.="//take in account timeshift\n";
        @$content2.="\$crawltts = strtotime(\"today\")-(\$crawlttime*3600);\n";
        @$content2.="\$crawltdatetoday = date(\"j\",\$crawltts);\n";
        @$content2.="\$crawltdatetoday2 = date(\"Y-m-d\",\$crawltts);\n";        
        @$content2.="\$url_crawlt=\"$url_crawlt\";\n";        
        @$content2.="if((\$crawltdatetoday != \$crawltdatelastseorequest) && \$crawltcheck==1)\n";
        @$content2.="{\n";
        @$content2.="include(\"$path/include/searchenginesposition.php\");\n"; 		
        @$content2.="}\n";
        @$content2.="if((\$crawltdatetoday != \$crawltdatemail) && \$crawltmail==1 && (\$crawltdatetoday == \$crawltdatelastseorequest) && \$crawltcheck==1)\n";
        @$content2.="{\n";
        @$content2.="\$crawltpath=\"$path\";\n"; 	      	         		      		
        @$content2.="include(\"$path/include/mail.php\");\n"; 		
        @$content2.="}\n";
        @$content2.="mysql_close(\$crawltconnexion);\n";
        @$content2.="if(\$crawltattack==1 && \$crawltblockattack==1 && \$crawltpostrequest==1)\n";
        @$content2.="{\n";
        @$content2.="echo \"crawltrack1\";\n";  
        @$content2.="}\n";
        @$content2.="elseif(\$crawltattack==1 && \$crawltblockattack==1)\n";
        @$content2.="{\n";
        @$content2.="\$GLOBALS = array();\n"; 
        @$content2.="\$_COOKIES = array();\n";
        @$content2.="\$_FILES = array();\n";
        @$content2.="\$_ENV = array();\n";
        @$content2.="\$_REQUEST = array();\n";         
        @$content2.="\$_POST = array();\n";
        @$content2.="\$_GET = array();\n";
        @$content2.="\$_SERVER = array();\n";
        @$content2.="\$_SESSION = array();\n";
        @$content2.="@session_destroy();\n";
        @$content2.="@mysql_close();\n";
        @$content2.="@header(\"Location:".$url_crawlt."html/noacces.htm\");\n";
        @$content2.="echo\"<head>\";\n";
        @$content2.="echo\"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$url_crawlt."html/noacces.htm'>\";\n";
        @$content2.="echo\"</head>\";\n"; 
        @$content2.="} \n";
        @$content2.="?>\n";
        
        $filename2=$path.'/crawltrack.php';		
        $filedir=$path;        
        
                
        if ( $file2 = fopen($filename2, "w") )
            {
            fwrite($file2, $content2);
            fclose($file2);
            $result26=1;
            }
        }
    else
        {
        echo"<h1>".$language['chmod_no_ok']."</h1>";
        $result1=0;
        $result2=0;
        }
    }
else
    {
    $result26=1;
    }
//----------------------------------------------------------------------------------------------------   
//set the correct chmod level to all folder
//determine the path to the file
if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME']))
    {
    $path = dirname( $_SERVER['SCRIPT_FILENAME'] );
    }
else
    {
    $path = '.';
    }
if(function_exists('chmod'))
    {
    @chmod($path, 0705);
    @chmod($path.'/cache', 0705);
    @chmod($path.'/graphs', 0705);    
    @chmod($path.'/html', 0705);    
    @chmod($path.'/images', 0705);    
    @chmod($path.'/include', 0705);    
    @chmod($path.'/language', 0705);
    @chmod($path.'/php', 0705);    
    @chmod($path.'/phpmailer', 0705);    
    @chmod($path.'/styles', 0705);            
    }    
//----------------------------------------------------------------------------------------------------
// check if the rowdisplay column exist in the config table and if not add it
$result27=0;
$tableinfo1 = mysql_query("SHOW COLUMNS FROM crawlt_config");
while ($table1= mysql_fetch_assoc($tableinfo1))
    {
    $listcolumn1[]=$table1['Field'];
    }
if(in_array('rowdisplay',$listcolumn1))
    {
    $result27=1;
    }	
else
    {    
    //add the column  in the config table    
    $sqlupdate27="ALTER TABLE crawlt_config ADD rowdisplay smallint(5)  NULL default '30'";
    $result27 = mysql_query($sqlupdate27, $connexion);
    } 
//----------------------------------------------------------------------------------------------------
// check if the orderdisplay column exist in the config table and if not add it
$result22=0;
$tableinfo1 = mysql_query("SHOW COLUMNS FROM crawlt_config");
while ($table1= mysql_fetch_assoc($tableinfo1))
    {
    $listcolumn1[]=$table1['Field'];
    }
if(in_array('orderdisplay',$listcolumn1))
    {
    $result22=1;
    }	
else
    {    
    //add the column  in the config table    
    $sqlupdate22="ALTER TABLE crawlt_config ADD orderdisplay smallint(5)  NULL default '0'";
    $result22 = mysql_query($sqlupdate22, $connexion);
    }  
//----------------------------------------------------------------------------------------------------
// check if the typemail column exist in the config table and if not add it
$result23=0;
$tableinfo1 = mysql_query("SHOW COLUMNS FROM crawlt_config");
while ($table1= mysql_fetch_assoc($tableinfo1))
    {
    $listcolumn1[]=$table1['Field'];
    }
if(in_array('typemail',$listcolumn1))
    {
    $result23=1;
    }	
else
    {    
    //add the column  in the config table    
    $sqlupdate23="ALTER TABLE crawlt_config ADD typemail smallint(5)  NULL default '1'";
    $result23 = mysql_query($sqlupdate23, $connexion);
    } 
//----------------------------------------------------------------------------------------------------
// check if the typecharset column exist in the config table and if not add it
$result24=0;
$tableinfo1 = mysql_query("SHOW COLUMNS FROM crawlt_config");
while ($table1= mysql_fetch_assoc($tableinfo1))
    {
    $listcolumn1[]=$table1['Field'];
    }
if(in_array('typecharset',$listcolumn1))
    {
    $result24=1;
    }	
else
    {    
    //add the column  in the config table    
    $sqlupdate24="ALTER TABLE crawlt_config ADD typecharset smallint(5)  NULL default '1'";
    $result24 = mysql_query($sqlupdate24, $connexion);
    }              
//----------------------------------------------------------------------------------------------------    	
//check if the crawlt_good_sites table exit and if not create it
$table_exist18 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion);
while (list($tablename)=mysql_fetch_array($tables)) 
  {
  if($tablename == 'crawlt_good_sites')
    {
    $table_exist18 = 1;
    }
  }
if($table_exist18 == 0)
  {
  //the table didn't exist, we can create it
  $result25 = mysql_query("CREATE TABLE crawlt_good_sites (
    id_site INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    host_site VARCHAR(255) NULL,
  PRIMARY KEY(id_site)
  )");
  }
else
  {
  $result25=1;
  }	
//----------------------------------------------------------------------------------------------------
//check if the crawlt_attack table exit and if not create it and fill it (use $result 20 and 21)
include"include/createtableattack.php";
//----------------------------------------------------------------------------------------------------  
// check if the blockattack column exist in the config table and if not add it
$result28=0;
$tableinfo1 = mysql_query("SHOW COLUMNS FROM crawlt_config");
while ($table1= mysql_fetch_assoc($tableinfo1))
    {
    $listcolumn1[]=$table1['Field'];
    }
if(in_array('blockattack',$listcolumn1))
    {
    $result28=1;
    }	
else
    {    
    //add the column  in the config table    
    $sqlupdate28="ALTER TABLE crawlt_config ADD blockattack smallint(5)  NULL default '0'";
    $result28 = mysql_query($sqlupdate28, $connexion);
    }       
//----------------------------------------------------------------------------------------------------   	
//check if the crawlt_sessionid table exit and if not create it
$table_exist19 = 0;
$sql = "SHOW TABLES ";                                                
$tables = mysql_query($sql, $connexion);
while (list($tablename)=mysql_fetch_array($tables)) 
  {
  if($tablename == 'crawlt_sessionid')
    {
    $table_exist19 = 1;
    }
  }
if($table_exist19 == 0)
  {
  //the table didn't exist, we can create it
  $result29 = mysql_query("CREATE TABLE crawlt_sessionid (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    sessionid VARCHAR(45) NULL,
  PRIMARY KEY(id)
  )");
  }
else
  {
  $result29=1;
  }		
//---------------------------------------------------------------------------------------------------- 
// check if the sessionid column exist in the config table and if not add it
$result30=0;
$tableinfo1 = mysql_query("SHOW COLUMNS FROM crawlt_config");
while ($table1= mysql_fetch_assoc($tableinfo1))
    {
    $listcolumn1[]=$table1['Field'];
    }
if(in_array('sessionid',$listcolumn1))
    {
    $result30=1;
    }	
else
    {    
    //add the column  in the config table    
    $sqlupdate30="ALTER TABLE crawlt_config ADD sessionid smallint(5)  NULL default '0'";
    $result30 = mysql_query($sqlupdate30, $connexion);
    }       
//----------------------------------------------------------------------------------------------------   
//normally the update is done....

//emptied the cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");

if( $result1==1 && $result2==1 && $result3==1 && $result4==1 && $result5==1 && $result6==1 && $result7==1 && $result8==1 && $result9==1 && $result10==1 && $result11==1 && $result12==1 && $result13==1 && $result14==1 && $result15==1 && $result16==1 && $result17==1 && $result18==1 && $result19==1 && $result20==1 && $result21==1 && $result22==1 && $result23==1 && $result24==1 && $result25==1 && $result26==1 && $result27==1 && $result28==1 && $result29==1 && $result30==1)
    {      
    $sqlupdateversion="UPDATE crawlt_config SET version='230'";    
    $requeteupdateversion = mysql_query($sqlupdateversion, $connexion);    
    
    echo"<div class=\"content\">\n";
    $a=substr($versionid,0,1);
    $b=substr($versionid,1,1);
    $c=substr($versionid,2,1);
    echo"<h1>".$language['update_crawltrack_ok']."&nbsp;$a.$b.$c</h1>";
    
    if($urlok==0) //we need to add the site url in the table
        {
        echo"<form action=\"index.php\" method=\"POST\" >\n";
        echo "<input type=\"hidden\" name ='navig' value='10'>\n";
        echo"<table width=\"100%\" align=\"center\">\n";	
        echo"<tr>\n";
        echo"<td width=\"100%\" align=\"center\">\n";
        echo"<input name='ok' type='submit'  value=' ".$language['url_update']." ' size='20'>\n";
        echo"</td>\n";
        echo"</tr>\n";
        echo"</table>\n";
        echo"</form><br><br><br>\n";           
        }
    else
        {
         //continue    
        echo"<div class=\"form\">\n";
        echo"<form action=\"index.php\" method=\"POST\" >\n";
        echo "<input type=\"hidden\" name ='navig' value='1'>\n";
        echo"<table width=\"100%\" align=\"center\">\n";	
        echo"<tr>\n";
        echo"<td width=\"100%\" align=\"center\">\n";
        echo"<input name='ok' type='submit'  value=' OK ' size='20'>\n";
        echo"</td>\n";
        echo"</tr>\n";
        echo"</table>\n";
        echo"</form>\n";
        echo"</div><br><br><br>";            
        }        
    }
else
    {
    echo"<h1>".$language['update_crawltrack_no_ok']."</h1>";
    
        //continue

    echo"<div class=\"form\">\n";
    echo"<form action=\"index.php\" method=\"POST\" >\n";
    echo "<input type=\"hidden\" name ='navig' value='1'>\n";
    if (isset($crawltlang))
        {
        echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
        }
    else
        {
        echo "<input type=\"hidden\" name ='lang' value='$lang'>\n";
        } 
    echo"<table width=\"100%\" align=\"center\">\n";	
    echo"<tr>\n";
    echo"<td width=\"100%\" align=\"center\">\n";
    echo"<input name='ok' type='submit'  value=' OK ' size='20'>\n";
    echo"</td>\n";
    echo"</tr>\n";
    echo"</table>\n";
    echo"</form>\n";
    echo"</div><br><br><br>";          
    }


?>