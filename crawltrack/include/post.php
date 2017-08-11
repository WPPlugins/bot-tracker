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
// file: post.php
//----------------------------------------------------------------------

if(!isset($crawltlang))
    {
    if(isset($_POST['lang']))
        {	
        $crawltlang = $_POST['lang'];
        }
    else
        {
        $crawltlang = 'english';
        }
    }


if(isset($_POST['newlang']))
    {	
    $crawltnewlang = $_POST['newlang'];
    }
else
    {
    $crawltnewlang = 'english';
    }


if(isset($_POST['idmysql']))
	{	
	$idmysql = $_POST['idmysql'];
	}
else
	{
	$idmysql = '';
	}


if(isset($_POST['passwordmysql']))
	{	
	$passwordmysql = $_POST['passwordmysql'];
	}
else
	{
	$passwordmysql = '';
	}


if(isset($_POST['hostmysql']))
	{	
	$hostmysql = $_POST['hostmysql'];
	}
else
	{
	$hostmysql = 'localhost';
	}

if(isset($_POST['basemysql']))
	{	
	$basemysql = $_POST['basemysql'];
	}
else
	{
	$basemysql = '';
	}




if(isset($_POST['order']))
	{
	$order= (int)$_POST['order'];
	}
else
	{
	if(isset($_GET['order']))
		{
		$order = (int)$_GET['order'];
		}
	}

if(isset($_POST['rowdisplay']))
	{
	$rowdisplay= (int)$_POST['rowdisplay'];
	}
else
	{
	if(isset($_GET['rowdisplay']))
		{
		$rowdisplay = (int)$_GET['rowdisplay'];
		}
	}

if(isset($_POST['typemail']))
	{
	$crawltmailishtml= (int)$_POST['typemail'];
	}
else
	{
	if(isset($_GET['typemail']))
		{
		$crawltmailishtml = (int)$_GET['typemail'];
		}
	}

if(isset($_POST['charset']))
	{
	$crawltcharset= (int)$_POST['charset'];
	}
else
	{
	if(isset($_GET['charset']))
		{
		$crawltcharset = (int)$_GET['charset'];
		}
	}
if(isset($_POST['blockattack']))
	{
	$crawltblockattack= (int)$_POST['blockattack'];
	}
else
	{
	if(isset($_GET['blockattack']))
		{
		$crawltblockattack = (int)$_GET['blockattack'];
		}
	}
if(isset($_POST['sessionid']))
	{
	$crawltsessionid= (int)$_POST['sessionid'];
	}
else
	{
	if(isset($_GET['sessionid']))
		{
		$crawltsessionid = (int)$_GET['sessionid'];
		}
	}	
if(isset($_POST['sessionid1']))
	{
	$crawltsessionid1= (int)$_POST['sessionid1'];
	}
else
	{
	if(isset($_GET['sessionid1']))
		{
		$crawltsessionid1 = (int)$_GET['sessionid1'];
		}
  else
    {
    $crawltsessionid1 =0;
    }		
	}	
if(isset($_POST['sessionid2']))
	{
	$crawltsessionid2= (int)$_POST['sessionid2'];
	}
else
	{
	if(isset($_GET['sessionid2']))
		{
		$crawltsessionid2 = (int)$_GET['sessionid2'];
		}
  else
    {
    $crawltsessionid2 =0;
    }		
	}	
if(isset($_POST['sessionid3']))
	{
	$crawltsessionid3= (int)$_POST['sessionid3'];
	}
else
	{
	if(isset($_GET['sessionid3']))
		{
		$crawltsessionid3 = (int)$_GET['sessionid3'];
		}
  else
    {
    $crawltsessionid3 =0;
    }		
	}	
if(isset($_POST['sessionid4']))
	{
	$crawltsessionid4= (int)$_POST['sessionid4'];
	}
else
	{
	if(isset($_GET['sessionid4']))
		{
		$crawltsessionid4 = (int)$_GET['sessionid4'];
		}
  else
    {
    $crawltsessionid4 =0;
    }		
	}	
if(isset($_POST['sessionid5']))
	{
	$crawltsessionid5= (int)$_POST['sessionid5'];
	}
else
	{
	if(isset($_GET['sessionid5']))
		{
		$crawltsessionid5 = (int)$_GET['sessionid5'];
		}
  else
    {
    $crawltsessionid5 =0;
    }		
	}	
if(isset($_POST['sessionid6']))
	{
	$crawltsessionid6= (int)$_POST['sessionid6'];
	}
else
	{
	if(isset($_GET['sessionid6']))
		{
		$crawltsessionid6 = (int)$_GET['sessionid6'];
		}
  else
    {
    $crawltsessionid6 =0;
    }		
	}	
if(isset($_POST['sessionid7']))
	{
	$crawltsessionid7= (int)$_POST['sessionid7'];
	}
else
	{
	if(isset($_GET['sessionid7']))
		{
		$crawltsessionid7 = (int)$_GET['sessionid7'];
		}
  else
    {
    $crawltsessionid7 =0;
    }		
	}	
if(isset($_POST['sessionid8']))
	{
	$crawltsessionid8= (int)$_POST['sessionid8'];
	}
else
	{
	if(isset($_GET['sessionid8']))
		{
		$crawltsessionid8 = (int)$_GET['sessionid8'];
		}
  else
    {
    $crawltsessionid8 =0;
    }
	}	
if(isset($_POST['login']))
	{	
	$login = $_POST['login'];
	}
else
	{
	$login = '';
	}
if(isset($_POST['password1']))
	{	
	$password1 = $_POST['password1'];
	}
else
	{
	$password1 = '';
	}

if(isset($_POST['password2']))
	{	
	$password2 = $_POST['password2'];
	}
else
	{
	$password2 = '';
	}

if(isset($_POST['password3']))
	{	
	$password3 = $_POST['password3'];
	}
else
	{
	$password3 = '';
	}


if(isset($_POST['logintype']))
	{
	$logintype = (int)$_POST['logintype'];
	}
else
	{
	$logintype = 0;
	}

if(isset($_POST['crawlername2']))
	{	
	$crawlername2 = $_POST['crawlername2'];
	}
else
	{
	$crawlername2 = '';
	}

if(isset($_POST['crawlerua2']))
	{	
	$crawlerua2 = $_POST['crawlerua2'];
	}
else
	{
	$crawlerua2 = '';
	}

if(isset($_POST['crawleruser2']))
	{	
	$crawleruser2 = $_POST['crawleruser2'];
	}
else
	{
	$crawleruser2 = '';
	}

if(isset($_POST['crawlerurl2']))
	{	
	$crawlerurl2 = $_POST['crawlerurl2'];
	}
else
	{
	$crawlerurl2 = '';
	}

if(isset($_POST['crawlerip2']))
	{	
	$crawlerip2 = $_POST['crawlerip2'];
	}
else
	{
	$crawlerip2 = '';
	}

if(isset($_POST['logochoice']))
	{	
	$logochoice = (int)$_POST['logochoice'];
	}
else
	{
	$logochoice = 0;
	}

//case  can use also hypertext link
if(isset($_POST['validsite']))
	{
	$validsite = (int)$_POST['validsite'];
	}
else
	{
	if(isset($_GET['validsite']))
		{
		$validsite = (int)$_GET['validsite'];
		}
	else
		{	
		$validsite= 0;
		}
	}
	
if(isset($_POST['sitename']))
	{	
	$sitename = $_POST['sitename'];
	}
else
	{
	if(isset($_GET['sitename']))
		{
		$sitename = $_GET['sitename'];
		}
	else
		{	
		$sitename= '';
		}
	}

if(isset($_POST['siteurl']))
	{	
	$siteurl = $_POST['siteurl'];
	}
else
	{
	if(isset($_GET['siteurl']))
		{
		$siteurl = $_GET['siteurl'];
		}
	else
		{	
		$siteurl= '';
		}
	}	
	

if(isset($_POST['validform']))
	{
	$validform = (int)$_POST['validform'];
	}
else
	{
	if(isset($_GET['validform']))
		{
		$validform = (int)$_GET['validform'];
		}
	else
		{	
		$validform= 0;
		}
	}


if(isset($_POST['validlogin']))
	{
	$validlogin = (int)$_POST['validlogin'];
	}
else
	{
	if(isset($_GET['validlogin']))
		{
		$validlogin = (int)$_GET['validlogin'];
		}
	else
		{	
		$validlogin= 0;
		}
	}

if(isset($_POST['period']))
	{
	$period = (int)$_POST['period'];
	}
else
	{
	if(isset($_GET['period']))
		{
		$period = (int)$_GET['period'];
		}
	else
		{	
		$period = 0;
		}
	}

	
if(isset($_POST['navig']))
	{
	$navig = (int)$_POST['navig'];
	}
else
	{
	if(isset($_GET['navig']))
		{
		$navig = (int)$_GET['navig'];
		}
	else
		{	
		$navig = 1;
		}
	}


//modified according Stef proposal
if(isset($_POST['site']))
	{
	$site= (int)$_POST['site'];
	}
elseif(isset($_GET['site']))
	{
	$site = (int)$_GET['site'];
	}
elseif(file_exists('include/configconnect.php') )
  {
	//version 1.0.1 correction of the bug existing when site 1 didn't exist	
	//mysql requete	
  $sqlpostsite = "SELECT * FROM crawlt_site ORDER BY id_site ASC";	
  $requetepostsite = mysql_query($sqlpostsite, $connexion) or die("MySQL query error");				
  $lignepostsite = mysql_fetch_object($requetepostsite);
  $site=$lignepostsite->id_site;		
  }
else
  {			
  $site = 1;
  }


if(isset($_POST['crawler']))
	{
	$crawler= $_POST['crawler'];
	}
else
	{
	if(isset($_GET['crawler']))
		{
		$crawler = $_GET['crawler'];
		}
	else
		{	
		$crawler = 0;
		}
	}

if(isset($_POST['search']))
	{
	$search= $_POST['search'];
	}
else
	{
	if(isset($_GET['search']))
		{
		$search = $_GET['search'];
		}
	else
		{	
		$search = 0;
		}
	}

if(isset($_GET['displayall']) && ($_GET['displayall']=='no' OR $_GET['displayall']=='yes'))
    {
    $displayall = $_GET['displayall'];
    }
else
    {	
    $displayall = 'no';
    }

if(!isset($firstdayweek))
    {
    if(isset($_GET['firstdayweek']) && ($_GET['firstdayweek']=='Monday' OR $_GET['firstdayweek']=='Sunday'))
        {
        $firstdayweek = $_GET['firstdayweek'];
        }
    else
        {	
        $firstdayweek= 'Monday';
        }
    }
    
if(isset($_POST['graphpos']))
	{
	$graphpos= $_POST['graphpos'];
	}
else
	{
	if(isset($_GET['graphpos']))
		{
		$graphpos = $_GET['graphpos'];
		}
	else
		{	
		$graphpos = 0;
		}
	}

?>