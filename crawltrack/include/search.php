<?php
//----------------------------------------------------------------------
//  CrawlTrack 2.3.0a
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.fr
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: search.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT'))
{
	echo"<h1>Hacking attempt !!!!</h1>";
	exit();
}
//initialize array
$list=array();
//database connection
$connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
//include menu 
include"include/menumain.php";
include"include/menusite.php";

echo"<div class=\"content\">\n";

//test if form valid
if($crawler=="" && $validform==1)
	{
	$validform=0;
	}

//test form for navigation

if($validform==0)
	{
	if($crawler==0)
		{
		$crawler="";
		}
	echo"<h1>".$language['search2']."</h1>\n";	
	echo"<table width=\"720px\" align=\"center\">\n";
	echo"<tr><td>\n";	
	echo"<div class=\"form2\" align=\"centrer\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"1\">";
	echo "<input type=\"hidden\" name ='site' value=\"$site\">";			
	echo "<input type=\"hidden\" name ='period' value=\"$period\">";
	echo"<table align=\"centrer\" width=\"300px\">\n";
	echo"<tr>\n";
	echo"<td><h1>".$language['search_crawler']."</h1></td></tr>\n";
	echo"<tr><td align='center'>".$language['crawler_name'].":<input name='crawler'  value='$crawler' type='text' size='20'/></td>\n";
	echo"</tr>\n";	
	echo"<tr>\n";
	echo"<td align='center'>\n";
	echo"<br>\n";
	echo"<input name='ok' type='submit'  value=' ".$language['go_search']." ' size='20'>\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"</table>\n";
	echo"</form></div>\n";
	
	echo"</td><td>\n";

	echo"<div class=\"form2\" align=\"centrer\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"2\">";
	echo "<input type=\"hidden\" name ='site' value=\"$site\">";	
	echo "<input type=\"hidden\" name ='period' value=\"$period\">";				
	echo"<table align=\"centrer\" width=\"300px\">\n";
	echo"<tr>\n";
	echo"<td><h1>".$language['search_page']."</h1></td></tr>\n";
	echo"<tr><td align='center'>".$language['page'].":<input name='crawler'  value='$crawler' type='text' size='20'/></td>\n";
	echo"</tr>\n";	
	echo"<tr>\n";
	echo"<td align='center'>\n";
	echo"<br>\n";
	echo"<input name='ok' type='submit'  value=' ".$language['go_search']." ' size='20'>\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"</table>\n";
	echo"</form></div>\n";

	echo"</td></tr><tr><td>&nbsp;</td></tr><tr><td>\n";	

	echo"<div class=\"form2\" align=\"centrer\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"5\">";
	echo "<input type=\"hidden\" name ='site' value=\"$site\">";			
	echo "<input type=\"hidden\" name ='period' value=\"$period\">";
	echo"<table align=\"centrer\" width=\"300px\">\n";
	echo"<tr>\n";
	echo"<td><h1>".$language['search_user_agent']."</h1></td></tr>\n";
	echo"<tr><td align='center'>".$language['crawler_user_agent']."<input name='crawler'  value='$crawler' type='text' size='20'/></td>\n";
	echo"</tr>\n";	
	echo"<tr>\n";
	echo"<td align='center'>\n";
	echo"<br>\n";
	echo"<input name='ok' type='submit'  value=' ".$language['go_search']." ' size='20'>\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"</table>\n";
	echo"</form></div>\n";

	echo"</td><td>\n";
	
	echo"<div class=\"form2\" align=\"centrer\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"3\">";
	echo "<input type=\"hidden\" name ='site' value=\"$site\">";	
	echo "<input type=\"hidden\" name ='period' value=\"$period\">";				
	echo"<table align=\"centrer\" width=\"300px\">\n";
	echo"<tr>\n";
	echo"<td><h1>".$language['search_user']."</h1></td></tr>\n";
	echo"<tr><td align='center'>".$language['Origin'].":<input name='crawler'  value='$crawler' type='text' size='20'/></td>\n";
	echo"</tr>\n";	
	echo"<tr>\n";
	echo"<td  align='center'>\n";
	echo"<br>\n";
	echo"<input name='ok' type='submit'  value=' ".$language['go_search']." ' size='20'>\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"</table>\n";
	echo"</form></div>\n";
	
	
	echo"</td></tr><tr><td>&nbsp;</td></tr><tr><td colspan=\"2\">\n";	
	
	echo"<div class=\"form2\" align=\"centrer\">\n";
	echo"<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"7\">";
	echo "<input type=\"hidden\" name ='site' value=\"$site\">";	
	echo "<input type=\"hidden\" name ='period' value=\"$period\">";				
	echo"<table align=\"centrer\" width=\"300px\">\n";
	echo"<tr>\n";
	echo"<td><h1>".$language['search_ip']."</h1></td></tr>\n";
	echo"<tr><td align='center'>".$language['crawler_ip']."<input name='crawler'  value='$crawler' type='text' size='20'/></td>\n";
	echo"</tr>\n";	
	echo"<tr>\n";
	echo"<td  align='center'>\n";
	echo"<br>\n";
	echo"<input name='ok' type='submit'  value=' ".$language['go_search']." ' size='20'>\n";
	echo"</td>\n";
	echo"</tr>\n";
	echo"</table>\n";
	echo"</form></div>\n";	

	echo"</td></tr></table><br><br>\n";	
		
	}
else
	{
	if($search==7)
        {
        //test to see if the IP address is correct
        $modele="^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$";
        $crawler=strtolower($crawler);
        if (ereg($modele, $crawler))
            {
            $validaddress=1;
            }
        else    
            {
            $validaddress=0;
            }
            
        if($validaddress==0)
            {
            echo"<h1>".$language['search_ip']."</h1><br><br>\n";
            echo"<p>".$language['ip_no_ok']."</p><br><br>\n";
            
             //continue    
            
            echo"<form action=\"index.php\" method=\"POST\" >\n";
            echo "<input type=\"hidden\" name ='navig' value='5'>\n";
            echo "<input type=\"hidden\" name ='crawler' value='$crawler'>\n";
            echo"<table class=\"centrer\">\n";	
            echo"<tr>\n";
            echo"<td colspan=\"2\">\n";
            echo"<input name='ok' type='submit'  value='OK ' size='20'>\n";
            echo"</td>\n";
            echo"</tr>\n";
            echo"</table>\n";
            echo"</form><br>\n";
            
            
            
            }
        else
            {
            //ip search
            $ipexplode= explode('.',$crawler); 
            
            if($ipexplode[0]>255 OR $ipexplode[1]>255 OR $ipexplode[2]>255 OR $ipexplode[3]>255)
                {
                
                echo"<h1>".$language['search_ip']."</h1><br><br>\n";
                echo"<p>".$language['ip_no_ok']."</p><br><br>\n";
                
                 //continue    
                
                echo"<form action=\"index.php\" method=\"POST\" >\n";
                echo "<input type=\"hidden\" name ='navig' value='5'>\n";
                echo "<input type=\"hidden\" name ='crawler' value='$crawler'>\n";
                echo"<table class=\"centrer\">\n";	
                echo"<tr>\n";
                echo"<td colspan=\"2\">\n";
                echo"<input name='ok' type='submit'  value='OK ' size='20'>\n";
                echo"</td>\n";
                echo"</tr>\n";
                echo"</table>\n";
                echo"</form><br>\n";                
                              
                }
            else
                {
                 
                //maxMind GeoIp calculation formula						
                $ip2=(16777216*$ipexplode[0]) + (65536*$ipexplode[1]) + (256*$ipexplode[2]) + $ipexplode[3];
                
                $sqlstats = "SELECT country_code FROM crawlt_ip_data
                WHERE ip_from <= '".sql_quote($ip2)."'
                AND ip_to >= '".sql_quote($ip2)."'";        
                
                               
                $requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");
        
                $nbrresult1=mysql_num_rows($requetestats);
                
                if($nbrresult1>=1)
                    {	
                    $ligne = mysql_fetch_row($requetestats);
                    $code=$ligne[0];
                    }
                else
                    {
                    $code='xx';
                    }   
                
                 $crawlerdisplay= htmlentities( $crawler); 
                 
                 echo"<h1>".$language['search_ip']."</h1><br><br>\n";
                
                echo"<div class='tableau' align='center'>\n";	
                echo"<table   cellpadding='0px' cellspacing='0' width='100%'>\n";       
                echo"<tr><th class='tableau1'>\n";
                echo"".$language['ip']."\n";		
                echo"</th>\n";
                echo"<th class='tableau2'>\n";		
                echo"".$language['crawler_country']."\n";
                echo"</th></tr>\n";                    
                echo"<td class='tableau3'>".$crawlerdisplay."</td>\n"; 
                echo"<td class='tableau5'>\n";                
                echo"<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
                echo"</td></tr> \n";
                echo"</table></div><br>\n";
                echo"<p align='center'><span class='smalltext'>".$language['maxmind']." <a href='http://maxmind.com'>http://maxmind.com</a></span></p>\n";                
                }
            }
        }
	else
        {
        //mysql requete
        if($search!=2)  
            {
            //case crawler, we search in the whole crawler database
            $sqlstats = "SELECT crawler_name, crawler_info, crawler_user_agent FROM crawlt_crawler
            ORDER BY crawler_name ASC";
            }
        else
            {
            //case page, we search in the visit database
            
            
            $sqlstats = "SELECT crawler_name, crawler_info, crawler_user_agent, url_page FROM crawlt_visits,crawlt_crawler,crawlt_pages 
            WHERE crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
            AND crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
            AND crawlt_visits.crawlt_site_id_site='".sql_quote($site)."'
            ORDER BY crawlt_visits.date ASC";
            }		
            
        $requetestats = mysql_query($sqlstats, $connexion) or die("MySQL query error");
        
        $nbrresult=mysql_num_rows($requetestats);
        if($nbrresult>=1)
            {
                
            if($search==1)
                {
        
                while ($ligne = mysql_fetch_row($requetestats))                                                                              
                    {
                    $crawlername=$ligne[0];
                    
                  if($crawltcharset==1)
                    {                    
                    $crawler2= mb_convert_encoding($crawler, "ISO-8859-1", "auto");                     
                    }
                  else
                    {
                    $crawler2=$crawler;
                    }
                    
                                         
                    if(eregi($crawler2,$crawlername))
                        {
                        if($crawltcharset==1)
                          {
                          if( !isutf8($crawlername))
                            {                           
                            $list[$crawlername]=mb_convert_encoding($crawlername, "UTF-8", "ISO-8859-1");
                            }
                          else
                            {
                            $list[$crawlername]=$crawlername;
                            }                           
                          }
                        else
                          {
                          $list[$crawlername]=$crawlername;
                          }                        
                        }
                    }


                if($crawltcharset==1)
                  {
                  if( !isutf8($crawler))
                    {                                          
                    $crawlerdisplay =mb_convert_encoding($crawler, "UTF-8", "ISO-8859-1");
                    }
                 else
                    {
                    $crawlerdisplay = $crawler;
                    }                     
                  }
               else
                  {
                  $crawlerdisplay = $crawler;
                  }                       
                 
                    
                echo"<br><br><h1>".$language['search2']."</h1>\n";				
                echo"<h1>".$language['search_crawler']."</h1>\n";
                echo"<h2>".$language['result_crawler_1']."".$crawlerdisplay."</h2><br>\n";
    
    
                if(isset($list))
                    {                  		
                    asort($list);
                    
                    //change text if more than 100 answers	
                    $nbrtotanswer=sizeof($list);
                    if($nbrtotanswer>100)
                        {
                        echo"<br><br><h2>".$language['to_many_answer']."</h2>\n";
                        }
    
                    echo"<div class='tableau' align='center'>";
                    echo"<table   cellpadding='0px' cellspacing='0' width='450px'>\n";			
                    echo"<tr><td class='tableau2'>".$language['result_crawler']."</td><tr>\n";
                    
                    //counter for alternate color lane
                    $comptligne=2;
        
                    //counter to limite number of datas displayed
                    $comptdata=0;
                    
                                
                    foreach ($list as $key => $crawl)
                        {                        
                        if($crawltcharset==1)
                          {
                          if( !isutf8($crawl))
                            {                                                  
                            $crawldisplay =mb_convert_encoding($crawl, "UTF-8", "ISO-8859-1");
                            }
                         else
                            {
                            $crawldisplay = $crawl;
                            }                             
                          }
                       else
                          {
                          $crawldisplay = $crawl;
                          }                        
                        
                       
                        $crawlencode = urlencode($key);
    
                        if($comptdata<100)
                            {
                            if ($comptligne%2 ==0)
                                {	
                                echo"<tr><td class='tableau5'><a href='index.php?navig=2&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            else
                                {
                                echo"<tr><td class='tableau50'><a href='index.php?navig=2&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            }
                            
                        $comptligne++;
                        $comptdata++;	
                        }
                    echo"</table></div><br>";
                    }
                else
                    {
                    echo"<br><br><h2>".$language['no_answer']."</h2>\n";
                    }							
                }
            elseif($search==2)
                {
                while ($ligne = mysql_fetch_row($requetestats))                                                                              
                    {
                    $pagename=$ligne[3];
                    
                    
                   if($crawltcharset==1)
                    {
                    if( !isutf8($crawler))
                      {                    
                      $crawler2= mb_convert_encoding($crawler, "ISO-8859-1", "auto");
                      }
                    else
                      {
                      $crawler2=$crawler;
                      }                      
                    }
                  else
                    {
                    $crawler2=$crawler;
                    }
                    
                                         
                    if(eregi($crawler2,$pagename))
                        {
                        if($crawltcharset==1)
                          {
                          if( !isutf8($pagename))
                            {                         
                            $list[$pagename]=mb_convert_encoding($pagename, "UTF-8", "ISO-8859-1");
                            }
                          else
                            {
                            $list[$pagename]=$pagename;
                            }                          
                          }
                        else
                          {
                          $list[$pagename]=$pagename;
                          }                        
                        }                   
                    }

               
                  
                
                    
                echo"<br><br><h1>".$language['search2']."</h1>\n";	
                echo"<h1>".$language['search_page']."</h1>\n";
                echo"<h2>".$language['result_crawler_1']."".htmlspecialchars($crawler2)."</h2><br>\n";
                
                if(isset($list))
                    {
                    asort($list);
                    //change text if more than 100 answers	
                    $nbrtotanswer=sizeof($list);
                    if($nbrtotanswer>100)
                        {
                        echo"<br><br><h2>".$language['to_many_answer']."</h2>\n";
                        }				
    
                    echo"<div class='tableau' align='center'>";
                    echo"<table   cellpadding='0px' cellspacing='0' width='450px'>\n";			
                    echo"<tr><td class='tableau2'>".$language['result_page']."</td><tr>\n";	
                    
                    //counter for alternate color lane
                    $comptligne=2;
        
                    //counter to limite number of datas displayed
                    $comptdata=0;				
                    
                            
                    foreach ($list as $key => $crawl)
                        {
                        if($crawltcharset==1)
                          { 
                         if( !isutf8($crawl))
                            {                                                                
                            $crawl2 =mb_convert_encoding($crawl, "UTF-8", "ISO-8859-1");
                            }
                          else
                            {
                            $crawl2=$crawl;
                            }                            
                          }
                        else
                          {
                          $crawl2=$crawl;
                          }                        
                        
                        
                         $crawldisplay = crawltcutkeyword($crawl2,'80');   
    
                        $crawlencode=urlencode($key);						
                        
                        if($comptdata<100)
                            {
                            if ($comptligne%2 ==0)
                                {						
                                echo"<tr><td class='tableau5'><a href='index.php?navig=4&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            else
                                {
                                echo"<tr><td class='tableau50'><a href='index.php?navig=4&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            }					
                        $comptligne++;
                        $comptdata++;															
                        }
                    echo"</table></div><br>";	
                    }
                else
                    {
                    echo"<br><br><h2>".$language['no_answer']."</h2>\n";
                    }								
                }
            elseif($search==3)
                {
                while ($ligne = mysql_fetch_row($requetestats))                                                                              
                    {
                    $crawlerinfo=$ligne[1];
                    
                   if($crawltcharset==1)
                    {                     
                    $crawler2= mb_convert_encoding($crawler, "ISO-8859-1", "auto");                      
                    }
                  else
                    {
                    $crawler2=$crawler;
                    }
                    
                                         
                    if(eregi($crawler2,$crawlerinfo))
                        {
                        if($crawltcharset==1)
                          {                          
                          $list[$crawlerinfo]=mb_convert_encoding($crawlerinfo, "UTF-8", "ISO-8859-1");                        
                          }
                        else
                          {
                          $list[$crawlerinfo]=$crawlerinfo;
                          }                        
                        }                     

                    } 
                    
                      
                  
                    
                echo"<br><br><h1>".$language['search2']."</h1>\n";	
                echo"<h1>".$language['search_user']."</h1>\n";
                echo"<h2>".$language['result_crawler_1']."".htmlspecialchars($crawler)."</h2><br>\n";
    
    
                if(isset($list))
                    {				
                    asort($list);
                    //change text if more than 100 answers	
                    $nbrtotanswer=sizeof($list);
                    if($nbrtotanswer>100)
                        {
                        echo"<br><br><h2>".$language['to_many_answer']."</h2>\n";
                        }
    
                    echo"<div class='tableau' align='center'>";
                    echo"<table   cellpadding='0px' cellspacing='0' width='450px'>\n";			
                    echo"<tr><td class='tableau2'>".$language['result_user']."</td><tr>\n";
                    
                    //counter for alternate color lane
                    $comptligne=2;
        
                    //counter to limite number of datas displayed
                    $comptdata=0;
                    
                                
                    foreach ($list as $key => $crawl)
                        {
                        if($crawltcharset==1)
                          { 
                           if( !isutf8($crawl))
                              {                                                  
                              $crawldisplay =mb_convert_encoding($crawl, "UTF-8", "ISO-8859-1");
                              }
                           else
                              {
                              $crawldisplay = $crawl;
                              }                              
                          }
                       else
                          {
                          $crawldisplay = $crawl;
                          }     
                          $crawlencode=urlencode($key);	  
                        
                        if($comptdata<100)
                            {
                            if ($comptligne%2 ==0)
                                {	
                                echo"<tr><td class='tableau5'><a href='index.php?validform=1&amp;search=4&amp;navig=5&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            else
                                {
                                echo"<tr><td class='tableau50'><a href='index.php?validform=1&amp;search=4&amp;navig=5&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            }
                            
                        $comptligne++;
                        $comptdata++;	
                        }
                    echo"</table></div><br>";
                    }
                else
                    {
                    echo"<br><br><h2>".$language['no_answer']."</h2>\n";
                    }	
                
                }
            elseif($search==5)
                {
                while ($ligne = mysql_fetch_row($requetestats))                                                                              
                    {
                    $crawlerua2=$ligne[2]; 
                    if(eregi($crawler,$crawlerua2))
                        {
                        $list[]=$crawlerua2;
                        }
                    }
                if($crawltcharset==1)
                  {
                  if( !isutf8($crawler))
                      {                                                         
                      $crawler2 =mb_convert_encoding($crawler, "UTF-8", "ISO-8859-1");
                      }
                  else
                    {
                    $crawler2=$crawler;
                    }                    
                  }
                else
                  {
                  $crawler2=$crawler;
                  }

                    
                echo"<br><br><h1>".$language['search2']."</h1>\n";	
                echo"<h1>".$language['search_user_agent']."</h1>\n";
                echo"<h2>".$language['result_crawler_1']."".htmlspecialchars($crawler2)."</h2><br>\n";
    
    
                if(isset($list))
                    {
                    $list=array_unique($list);				
                    sort($list);
                    //change text if more than 100 answers	
                    $nbrtotanswer=sizeof($list);
                    if($nbrtotanswer>100)
                        {
                        echo"<br><br><h2>".$language['to_many_answer']."</h2>\n";
                        }
    
                    echo"<div class='tableau' align='center'>";
                    echo"<table   cellpadding='0px' cellspacing='0' width='450px'>\n";			
                    echo"<tr><td class='tableau2'>".$language['result_ua']."</td><tr>\n";
                    
                    //counter for alternate color lane
                    $comptligne=2;
        
                    //counter to limite number of datas displayed
                    $comptdata=0;
                    
                                
                    foreach ($list as $crawl)
                        {
                         if($crawltcharset==1)
                          { 
                           if( !isutf8($crawl))
                              {                                                                
                              $crawl2 =mb_convert_encoding($crawl, "UTF-8", "ISO-8859-1");
                              }
                            else
                              {
                              $crawl2=$crawl;
                              }                             
                          }
                        else
                          {
                          $crawl2=$crawl;
                          }                       
                        $crawldisplay = crawltcutkeyword($crawl2,'80');
                        
                        if($comptdata<100)
                            {
                            $crawl2=urlencode($crawl);
                            if ($comptligne%2 ==0)
                                {	
                                echo"<tr><td class='tableau5'><a href='index.php?validform=1&amp;search=6&amp;navig=5&amp;period=3&amp;site=".$site."&amp;crawler=".$crawl2."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            else
                                {
                                echo"<tr><td class='tableau50'><a href='index.php?validform=1&amp;search=6&amp;navig=5&amp;period=3&amp;site=".$site."&amp;crawler=".$crawl2."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            }
                            
                        $comptligne++;
                        $comptdata++;	
                        }
                    echo"</table></div><br>";
                    }
                else
                    {
                    echo"<br><br><h2>".$language['no_answer']."</h2>\n";
                    }	
                
                }				
    
            elseif($search == 6)
                {			
                //database connection    
                $connexion = mysql_connect($crawlthost,$crawltuser,$crawltpassword) or die("MySQL connection to database problem");
                $selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
                
                $sqlexist = "SELECT crawler_name,crawler_user_agent, crawler_info, crawler_url FROM crawlt_crawler
                WHERE crawler_user_agent='".sql_quote($crawler)."'";
        
                $requeteexist = mysql_query($sqlexist, $connexion or die("MySQL query error"));
            
                $ligne2 = mysql_fetch_row($requeteexist);
                //crawler already exist
                $crawlernamedisplay=htmlentities($ligne2[0]);
                $useragdisplay=htmlentities($ligne2[1]);
                $crawlerinfodisplay=htmlentities($ligne2[2]);
                $crawlerurldisplay=htmlentities($ligne2[3]);
                
                echo"<br><br><h1>".$language['search2']."</h1>\n";
                echo"<h1>".$language['search_user_agent']."</h1>\n";			
                echo"<p>".$language['exist_data']."</p>\n";	
                echo"<h5>".$language['crawler_name2']."&nbsp;&nbsp;<a href='index.php?navig=2&amp;period=3&amp;site=".$site."&amp;crawler=$ligne2[0]'>".$crawlernamedisplay."</a></h5>";			
                echo"<h5>".$language['crawler_user_agent']."&nbsp;&nbsp;".$useragdisplay."</h5>";			
                echo"<h5>".$language['crawler_user']."&nbsp;&nbsp;".$crawlerinfodisplay."</h5>";	
                echo"<h5>".$language['crawler_url2']."&nbsp;&nbsp;<a href=\"$ligne->crawler_url\">".$crawlerurldisplay."</a></h5>";	
                
                echo"<div class=\"form\">\n";
                echo"<form action=\"index.php\" method=\"POST\" >\n";
                echo "<input type=\"hidden\" name ='navig' value='5'>\n";			
                echo"<input name='ok' type='submit'  value='OK' size='20'>\n";
                echo"</form>\n";
                echo"</div>\n";
                }
            else
                {
                
                $crawler=urldecode($crawler);
                while ($ligne = mysql_fetch_row($requetestats))                                                                              
                    {
                    $crawlerinfo=$ligne[1];
                    $crawlername=$ligne[0]; 

                  
                    
                    if($crawlerinfo == $crawler)
                        {
                        if($crawltcharset==1)
                          {
                          if( !isutf8($crawlername))
                            {                                                 
                            $list[$crawlername]=mb_convert_encoding($crawlername, "UTF-8", "ISO-8859-1");
                            }
                         else
                            {
                            $list[$crawlername]= $crawlername;
                            }                          
                          }
                       else
                          {
                          $list[$crawlername]= $crawlername;
                          }
                        }
                    }
                    

                if($crawltcharset==1)
                  { 
                   if( !isutf8($crawler))
                      {                                          
                      $crawlerdisplay =mb_convert_encoding($crawler, "UTF-8", "ISO-8859-1");
                      }
                   else
                      {
                      $crawlerdisplay = $crawler;
                      }                               
                  }
               else
                  {
                  $crawlerdisplay = $crawler;
                  }                       
                            
                    
                echo"<br><br><h1>".$language['search2']."</h1>\n";	
                echo"<h1>".$language['search_user']."</h1>\n";
                echo"<h2>".$language['result_user_1']."".$crawlerdisplay."</h2><br>\n";
    
    
                if(isset($list))
                    {			
                    asort($list);
                    //change text if more than 100 answers	
                    $nbrtotanswer=sizeof($list);
                    if($nbrtotanswer>100)
                        {
                        echo"<br><br><h2>".$language['to_many_answer']."</h2>\n";
                        }
    
                    echo"<div class='tableau' align='center'>";
                    echo"<table   cellpadding='0px' cellspacing='0' width='450px'>\n";			
                    echo"<tr><td class='tableau2'>".$language['result_user_crawler']."</td><tr>\n";
                    
                    //counter for alternate color lane
                    $comptligne=2;
        
                    //counter to limite number of datas displayed
                    $comptdata=0;
                    
                                
                    foreach ($list as $key => $crawl)
                        {
                        if($crawltcharset==1)
                          {  
                         if( !isutf8($crawl))
                            {                                                 
                            $crawldisplay =mb_convert_encoding($crawl, "UTF-8", "ISO-8859-1");
                            }
                         else
                            {
                            $crawldisplay = $crawl;
                            }                           
                          }
                       else
                          {
                          $crawldisplay = $crawl;
                          } 
                            
                        $crawlencode=urlencode($key);
                        if($comptdata<100)
                            {
                            if ($comptligne%2 ==0)
                                {	
                                echo"<tr><td class='tableau5'><a href='index.php?navig=2&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            else
                                {
                                echo"<tr><td class='tableau50'><a href='index.php?navig=2&amp;period=3&amp;site=".$site."&amp;crawler=".$crawlencode."'>".$crawldisplay."</a></td><tr>\n";
                                }
                            }
                            
                        $comptligne++;
                        $comptdata++;	
                        }
                    echo"</table></div><br>";
                    }
                else
                    {
                    echo"<br><br><h2>".$language['no_answer']."</h2>\n";
                    }	
                }		
                
                
            }
    
        }
	}


?>