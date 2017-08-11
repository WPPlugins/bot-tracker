<?php
/*
Plugin Name: Bot Tracker
Plugin URI: http://wordpress.designpraxis.at
Description: Tracking Robots, Spiders and Crawlers activity on your WordPress website using <a href="http://www.crawltrack.fr/">cr@wltr@ck</a><br />You can acces cr@wltr@ck from your <a href="index.php?page=bot-tracker/bot-tracker.php">Dashboard</a>
Version: 2.3.0
Author: Roland Rust
Author URI: http://wordpress.designpraxis.at

Following modifications were made to cr@wltr@ck:
crawltrack\include\configconnect.php
changed:
$gn = $_GET['graphname'];
$pr = $_GET['period'];
$nav = $_GET['navig'];
$tgr = $_GET['typegraph'];
include(dirname(__FILE__)."/../../../../../wp-config.php");
$crawltuser = DB_USER;
$crawltpassword = DB_PASSWORD;
$crawltdb = DB_NAME;
$crawlthost = DB_HOST;
$lang = "english";
$graphname = $gn;
$period = $pr;
$navig = $nav;
$typegraph = $tgr;


crawltrack\php\login.php
line 104
$userpass2 = $_GET['dprx_pass'];
$userlogin = $_GET['dprx_user'];
line 33:
uncommented cache part

crawltrack\include\functions.php
line 95:
$this->caching = false;

crawltrack\graphs\artichow\Artichow.cfg.php
define('ARTICHOW_CACHE', FALSE);

crawltrack\styles\style.css
@ end of file

crawltrack\crawltrack.php
generated usually at crawltrack install
customized: absolute paths all dirname(__FILE__)
and $url_crawlt = get_bloginfo('url');
line 260

crawltrack_create.php is an excerpt of crawltrack/include/createtable.php
all $tables = mysql_query($sql, $connexion); replaced by $tables = mysql_query($sql);
include"./include/createtableip.php"; replaced by include dirname(__FILE__)."/crawltrack/include/createtableip.php";
sql_quote($crawltlang) replaced by $crawltlang

crawltrack/include/createtableip.php:
all include"./include replaced by include dirname(__FILE__)."
$tables = mysql_query($sql, $connexion) or die("MySQL query error"); replaced by $tables = mysql_query($sql) or die("MySQL query error");
*/

add_action('init', 'dprx_bot_check_install');
add_action("wp_footer","dprx_tracker_include");

function dprx_tracker_include() {
	$crawltsite=1;
	include(dirname(__FILE__)."/crawltrack/crawltrack.php");
} 

if (eregi("bot-tracker",$_REQUEST['page'])) {
add_action('admin_head', 'dprx_bot_tracker_add_style');
}

function dprx_bot_tracker_add_style() {
	?>
	<link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/bot-tracker/bot-tracker.css" type="text/css"/>
	<script type="text/javascript">
	
	/***********************************************
	* IFrame SSI script II- © Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
	* Visit DynamicDrive.com for hundreds of original DHTML scripts
	* This notice must stay intact for legal use
	***********************************************/
	
	//Input the IDs of the IFRAMES you wish to dynamically resize to match its content height:
	//Separate each ID with a comma. Examples: ["myframe1", "myframe2"] or ["myframe"] or [] for none:
	var iframeids=["dprx_bot_tracker_iframe"]
	
	//Should script hide iframe from browsers that don't support this script (non IE5+/NS6+ browsers. Recommended):
	var iframehide="yes"
	
	var getFFVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Firefox")).split("/")[1]
	var FFextraHeight=parseFloat(getFFVersion)>=0.1? 16 : 0 //extra height in px to add to iframe in FireFox 1.0+ browsers
	
	function resizeCaller() {
	var dyniframe=new Array()
	for (i=0; i<iframeids.length; i++){
	if (document.getElementById)
	resizeIframe(iframeids[i])
	//reveal iframe for lower end browsers? (see var above):
	if ((document.all || document.getElementById) && iframehide=="no"){
	var tempobj=document.all? document.all[iframeids[i]] : document.getElementById(iframeids[i])
	tempobj.style.display="block"
	}
	}
	}
	
	function resizeIframe(frameid){
	var currentfr=document.getElementById(frameid)
	if (currentfr && !window.opera){
	currentfr.style.display="block"
	if (currentfr.contentDocument && currentfr.contentDocument.body.offsetHeight) //ns6 syntax
	currentfr.height = currentfr.contentDocument.body.offsetHeight+FFextraHeight; 
	else if (currentfr.Document && currentfr.Document.body.scrollHeight) //ie5+ syntax
	currentfr.height = currentfr.Document.body.scrollHeight;
	if (currentfr.addEventListener)
	currentfr.addEventListener("load", readjustIframe, false)
	else if (currentfr.attachEvent){
	currentfr.detachEvent("onload", readjustIframe) // Bug fix line
	currentfr.attachEvent("onload", readjustIframe)
	}
	}
	}
	
	function readjustIframe(loadevt) {
	var crossevt=(window.event)? event : loadevt
	var iframeroot=(crossevt.currentTarget)? crossevt.currentTarget : crossevt.srcElement
	if (iframeroot)
	resizeIframe(iframeroot.id);
	}
	
	function loadintoIframe(iframeid, url){
	if (document.getElementById)
	document.getElementById(iframeid).src=url
	}
	
	if (window.addEventListener)
	window.addEventListener("load", resizeCaller, false)
	else if (window.attachEvent)
	window.attachEvent("onload", resizeCaller)
	else
	window.onload=resizeCaller
	
	</script>
	<?php
}

add_action('admin_menu', 'dprx_bot_tracker_add_option_page');

function dprx_bot_tracker_add_option_page() {
	 add_submenu_page('index.php','Bot Tracker', 'Bot-Tracker', 8, __FILE__, 'dprx_bot_tracker_option_page');
}

function dprx_bot_tracker_set_admin($userdata) {
	global $wpdb;
	$sql = "SELECT * FROM crawlt_login 
		WHERE crawlt_user = '".$userdata->user_login."'";
	$res = $wpdb->get_results($sql, ARRAY_A);
	if (count($res) == 1) {
		$sql = "UPDATE crawlt_login 
			SET crawlt_password = '".$userdata->user_pass."'
			WHERE crawlt_user = '".$userdata->user_login."'";
			$res = $wpdb->query($sql);
	} else {
		$sql = "INSERT INTO crawlt_login 
			(crawlt_user,crawlt_password, admin, site) VALUES ('".$userdata->user_login."','".$userdata->user_pass."',1,1)";
			$res = $wpdb->query($sql);
	}
}

function dprx_bot_tracker_crawltrack_installed() {
	global $wpdb;
	$sql = "SELECT * FROM crawlt_config";
	$res = $wpdb->get_results($sql, ARRAY_A);
	if (count($res) < 1) {
		return false;
	}
	return true;
}

function dprx_bot_tracker_set_site() {
	global $wpdb;
	$sql = "SELECT * FROM crawlt_site 
		WHERE name = '".trailingslashit(get_bloginfo('url'))."'";
	$res = $wpdb->get_results($sql, ARRAY_A);
	if (count($res) < 1) {
		$sql = "INSERT INTO crawlt_site 
			(name,url) VALUES ('".trailingslashit(get_bloginfo('url'))."','".trailingslashit(get_bloginfo('url'))."');";
		$res = $wpdb->query($sql);
	}
}

function dprx_bot_check_install() {
	global $userdata, $wpdb;
	if (eregi("bot-tracker",$_REQUEST['page'])) {
		get_currentuserinfo();
		$sql = "SHOW TABLES";
		$res = $wpdb->get_results($sql, ARRAY_N);
		foreach ($res as $r) {
			if ($r[0] == "crawlt_site") {
				$is_installed = true;
			}
		}
		if (!$is_installed) {
			$crawltlang = "english";
			 include(dirname(__FILE__)."/crawltrack_create.php");
			 dprx_bot_tracker_set_admin($userdata);
			 wp_redirect(get_bloginfo("wpurl")."/wp-admin/index.php?page=bot-tracker/bot-tracker.php");
		} 
	}
}

function dprx_bot_tracker_option_page() {
	global $userdata;
	get_currentuserinfo();
	dprx_bot_tracker_set_admin($userdata);
	dprx_bot_tracker_set_site();
	?>
	<iframe width="100%" id="dprx_bot_tracker_iframe" src="<?php bloginfo("wpurl"); ?>/wp-content/plugins/bot-tracker/crawltrack/php/login.php?dprx_user=<?php echo $userdata->user_login; ?>&dprx_pass=<?php echo $userdata->user_pass; ?>"></iframe>
	<div class="wrap">
		<p>
		<?php _e("Running into Troubles? Features to suggest?","bkpwp"); ?>
		<a href="http://wordpress.designpraxis.at/">
		<?php _e("Drop me a line","bkpwp"); ?> &raquo;
		</a>
		</p>
		<div style="display: block; height:30px;">
			<div style="float:left; font-size: 16px; padding:5px 5px 5px 0;">
			<?php _e("Do you like this Plugin?","bkpwp"); ?>
			<?php _e("Consider to","bkpwp"); ?>
			</div>
			<div style="float:left;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="rol@rm-r.at">
			<input type="hidden" name="no_shipping" value="0">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="currency_code" value="EUR">
			<input type="hidden" name="tax" value="0">
			<input type="hidden" name="lc" value="AT">
			<input type="hidden" name="bn" value="PP-DonationsBF">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Please donate via PayPal!">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			</div>
		</div>
	</div>
	<?php
}

?>
