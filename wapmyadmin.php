<?php
/**
* @version		$Id: wapmyadmin.php 3 2011-12-30 00:08:16Z akalongman@gmail.com $
* @package	wapMyAdmin
* @copyright	Copyright (C) 20010 - 2011 wapMyAdmin Project. All rights reserved.
* @license		GNU General Public License version 2 or later
*/


error_reporting(E_ALL);
@ini_set('display_errors', false);


$start = microtime(true);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
header("Cache-Control: post-check=0, pre-check=0", false);

session_name('SID');
session_start();
$SID = SID;
ob_start();
ob_implicit_flush(0);

header("Content-Type: text/html; charset=utf-8");
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10-flat.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
<meta http-equiv="expires" content="0"/>
<meta http-equiv="cache-control" content="no-cache"/>
<meta http-equiv="pragma" content="no-cache"/>
<link rel="shortcut icon" href="http://geg.ge/pda/favicon.ico" type="image/x-icon"/>
<meta http-equiv="Copyright" content="wapMyAdmin Project"/>
<meta name="keywords" content="wap phpMyAdmin wapMyAdmin"/>
<meta name="description" content="wap phpMyAdmin"/>
<title>wapMyAdmin</title>
<style type="text/css">

body {
font-family: sans-serif, arial, verdana; font-weight: normal; font-size: medium; color: #FFFFFF; background-color: #000000;
}
.format_n {
-wap-input-format: "*n";
}
hr {
background: #cccccc; color: #cccccc; height: 1px;
}
a:link,a:visited {
text-decoration: underline; color : #0055FF;
}
a:active {
text-decoration: underline; color: #FF0000;
}
a:hover, a:focus {
text-decoration: underline; color: #00af00;
}
div {
margin: 1px 0px 1px 0px; padding: 4px 4px 4px 4px;
}
.a {
background-color: #00008B; padding: 0px; text-align: center; border: 1px solid #c0c0c0; font-size: 17px; color: #ffffff; text-decoration: none; font-weight: bold;
}
.b {
background-color: #000000; padding: 0px; text-align: left; font-size: 15px; color: #ffffff;
}
.c {
background-color: #00005f; padding: 0px; text-align: left; font-size: 15px; color: #ffffff;
}
.t { 
background-color: #000033; padding: 0px; border: 1px solid #0000bb; text-align: left;  font-size: 14px; color: #00aa00; text-indent: 20px; font-weight: bold;
}
.z {
background-color: #003B00; padding: 0px; text-align: center; border: 1px solid #c0c0c0; font-size: 15px; color: #aaaa00;
}
.on {
color: #00ff00; font-weight: normal;
}
.off {
color: #ff0000; font-weight: normal;
}
.ank {
color: #00bb00; text-decoration: underline;
}
form {
background-color: #00005f; color: #ffffff; font-size: 14px;
}
input,select,textarea {
background-color: #00005f; color: #00af00; font-size: 14px;
}
</style>
</head>
<body>
<div>
<div class="a">
wapMyAdmin (v 1.5)
</div>

<?php

function db_connect()
{
	global $start, $mysql_host;
	$mysql_host = !empty($_SESSION["mysql_host"]) ? $_SESSION["mysql_host"] : '';
	$mysql_login = !empty($_SESSION["mysql_login"]) ? $_SESSION["mysql_login"] : '';
	$mysql_pass = !empty($_SESSION["mysql_pass"]) ? $_SESSION["mysql_pass"] : '';
	
	$link = @mysql_connect($mysql_host, $mysql_login, $mysql_pass);
	if (!$link)
	{
		echo '<span class="off">Can\'t connect to MySQL!<br/>';
		echo mysql_error().'</span><br/>';
		echo '<a href="'.$_SERVER["PHP_SELF"].'">&lt;&lt; Main</a>';
		echo '</div>';
		echo '<div class="z">';
		echo 'wapMyAdmin is Free Software released under the GNU/GPL License.';
		echo '<br/>';
		echo round(microtime(true) - $start, 4).' sec';
		echo '</div>';
		echo '</div>';
		echo '</body>';
		echo '</html>';
		ob_end_flush();
		exit();
	}	
	return $link;
}


function db_select($link)
{
	global $start;
	if (isset($_REQUEST["db"]))
	{
		$db = $_REQUEST["db"];
		$_SESSION["db"] = $db;
	}
	else if (isset($_SESSION["db"])) 
	{
		$db = $_SESSION["db"];
	}
	else 
	{
		$db = null;
	}

	if ($db)
	{
		$select_db = @mysql_select_db($db, $link);
		if (!$select_db)
		{
			echo '<span class="off">Can\'t select database '.$db.'!<br/>';
			echo mysql_error().'</span><br/>';
			echo '<a href="'.$_SERVER["PHP_SELF"].'">&lt;&lt; Main</a>';
			echo '</div>';

			echo '<div class="z">';
			echo 'wapMyAdmin is Free Software released under the GNU/GPL License.';
			echo '<br/>';
			echo round(microtime(true) - $start, 4).' sec';
			echo '</div>';
			echo '</div>';
			echo '</body>';
			echo '</html>';
			ob_end_flush();
			exit();
		}
	}
	return $db;
}

function db_pathway()
{
	global $mysql_host, $mod, $db, $tb, $SID;
	if ($mod && $mysql_host)
	{
		echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=listdb">Server: `'.$mysql_host.'` </a>';
		if ($mod != 'listdb') 
		{
			echo ' &gt;  <a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=listtb">Database: `'.$db.'` </a>';
		}
		if ($tb) 
		{
			echo ' &gt;  <a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=tbdetail&amp;tb='.$tb.'">Table: `'.$tb.'` </a>';
		}
		echo '<br/>';
		echo '<hr/>';
	}	
	
}
	
	
	
global $mysql_host;



echo '<div class="b">';


$mod = isset($_GET["mod"]) ? $_GET["mod"] : false;

$tb = isset($_GET["tb"]) ? $_GET["tb"] : false;
$i = isset($_GET["i"]) ? (int)$_GET["i"] : 0;
$goto = isset($_REQUEST["goto"]) ? (int)$_REQUEST["goto"] : 0;







switch($mod)
{
	
	default:
		echo '<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=listdb"><div>';
		echo 'MySQL Host:<br/>';
		echo '<input title="Host" name="mysql_host" value="localhost" maxlength="50"/><br/>';
		echo 'MySQL Login:<br/>';
		echo '<input title="Login" name="mysql_login" maxlength="50"/><br/>';
		echo 'MySQL Password:<br/>';
		echo '<input title="Password" name="mysql_pass" type="password" maxlength="50"/><br/>';
		echo '<input value="Enter" type="submit"/></div></form>';
		break;
	

	case 'listdb':
		if (!empty($_POST["mysql_host"]))
		{
			$_SESSION["mysql_host"] = $_POST["mysql_host"];
		}
		if (!empty($_POST["mysql_login"]))
		{
			$_SESSION["mysql_login"] = $_POST["mysql_login"];
		}
		if (!empty($_POST["mysql_pass"]))
		{
			$_SESSION["mysql_pass"] = $_POST["mysql_pass"];
		}
		
		
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
				
		$db_list = mysql_list_dbs($link);
		$count = mysql_num_rows($db_list);
		echo 'Databases: '.$count.'<br/>';
		while($db = mysql_fetch_object($db_list))
		{
			echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=listtb&amp;db='.$db->Database.'">'.$db->Database.'</a><br/>';
		}
		break;
	
	
	case 'listtb':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();

		echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=query">SQL</a> | ';
		//echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=export&amp;db='.$db.'">Export</a> | ';
		echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=import&amp;db='.$db.'">Import</a> | ';
		echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=info">Info</a>';
		
		echo '<br/>';
		echo '<hr/>';
		
		$tb_list = mysql_query('SHOW TABLES FROM `'.$db.'`');
		$count = mysql_num_rows($tb_list);
	
		echo 'Tables: '.$count.' <br/>';
		for($i=0; $i<$count; $i++)
		{
			$tb = mysql_tablename($tb_list, $i);
			echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=tbdetail&amp;tb='.$tb.'">'.$tb.'</a>';
			echo '<br/>';
		}
		break;
	
	case 'tbdetail';
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$result = mysql_query("SELECT * FROM `".$tb."`", $link);
		$fields = mysql_num_fields($result);
		$rows = mysql_num_rows($result);
		
	
		echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$SID.'&amp;mod=query&amp;tb='.$tb.'">SQL</a> | ';
		echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbempty&amp;tb='.$tb.'">Empty</a> | ';
		echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbdrop&amp;tb='.$tb.'">Drop</a>';
		
		echo '<hr/>';
		echo '<span class="ank">Rows:</span> <b>'.$rows.'</b><br/>';
		
		
		echo '<form action="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=viewrow&amp;tb='.$tb.'&amp;i='.$i.'" method="post"><div>';
		echo 'Jump to row (max='.$rows.'):<br/><input class="format_n" name="goto" size="'.strlen($rows).'" maxlength="'.strlen($rows).'"/>
		<input type="submit" name="DoGo" value="Ok"/></div></form>';
		
		echo 'Fields: '.$fields.'<br/>';
		$fields = mysql_list_fields($db, $tb, $link);
		$colums = mysql_num_fields($fields);
		
		for($i=0; $i<$colums; $i++)
		{
			$name = mysql_field_name($fields, $i);
			$type = mysql_field_type($fields, $i);
			$len = mysql_field_len($fields, $i);
					
			echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=fielddetail&amp;tb='.$tb.'&amp;i='.$i.'">'.$name.'</a>  <span style="color: #009900">'.$type.'('.$len.')</span>';
			echo '<br/>';
		}
		break;
	


	case 'fielddetail':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$result = mysql_query("SELECT * FROM `".$tb."`", $link);
		$type = mysql_field_type($result, $i);
		$name = mysql_field_name($result, $i);
		$len = mysql_field_len($result, $i);
		$flags = mysql_field_flags($result, $i);
		
		echo '<span class="ank">Field:</span> '.$name.'<br/>';
		
		echo '<span class="ank">Type:</span> '.$type.'('.$len.')<br/>';
		echo '<span class="ank">Flags:</span> '.$flags.'<br/>';
		break;
	
	
	
	case 'viewrow';
		$link = db_connect();
		$db = db_select($link);
		db_pathway();

		$result = mysql_query("SELECT * FROM `".$tb."`", $link);
		$max = mysql_num_rows($result);
		if (!$max)
		{
			echo '<span class="off">No rows in this table</span><br/>';
			echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbdetail&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
		}
		else if (!$goto)
		{
			echo '<span class="off">No row number selected</span><br/>';
			echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbdetail&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
		}
		else	if ($goto <= $max)
		{
			echo 'Row <b> '.$goto.'</b><br/>';
	
			for($j=0; $j<$goto; $j++)
			{
				$row = mysql_fetch_array($result);
			}
			
			$fields = mysql_list_fields($db, $tb, $link);
			$colums = mysql_num_fields($fields);
			for($i=0; $i<$colums; $i++)
			{
				$name = mysql_field_name($fields, $i);
				echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=changefieldresult&amp;tb='.$tb.'&amp;i='.$i.'&amp;goto='.$goto.'">'.$name.'</a>: '.htmlspecialchars($row[$i]).'<br/>';
			}
			mysql_free_result($result);
		}
		else
		{
			echo '<span class="off">This row not found (max=<b>'.$max.'</b>)</span><br/>';
			echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbdetail&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
		}
		break;
	
	
	
	
	case 'changefieldresult':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$result = mysql_query("SELECT * FROM `".$tb."`", $link);
		$type = mysql_field_type($result, $i);
		$name = mysql_field_name($result, $i);
		$len = mysql_field_len($result, $i);
		$flags = mysql_field_flags($result, $i);
		
		
		for($j=0; $j<$goto; $j++)
		{
			$row = mysql_fetch_row($result);
		}
		
		$value = $row[$i];
		
		
		echo '<span class="ank">Field:</span> <b>'.$name.'</b><br/>';
		
		echo '<span class="ank">Type:</span> '.$type.'('.$len.')<br/>';
		echo '<span class="ank">Flags:</span> '.$flags.'<br/>';
		echo '<span class="ank">Value:</span> '.$value.'<br/>';
		
		
		echo '<form action="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=addfieldresult&amp;tb='.$tb.'&amp;i='.$i.'&amp;goto='.$goto.'" method="post"><div>';
		
		if ($type == 'int')
		{
			echo '<input class="format_n" value="'.$value.'" name="change" size="15"/><br/>';
		}
		else
		{
			echo '<input type="text" value="'.htmlspecialchars($value).'" name="change" size="15"/><br/>';
		}
		echo '<input type="submit" name="DoGo" value="Ok"/></div></form>';
		
		echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=viewrow&amp;tb='.$tb.'&amp;goto='.$goto.'">&lt;&lt;Back</a><br/>';
		
		break;
	
	
	
	case 'addfieldresult':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$change = isset($_POST["change"]) ? trim(mysql_real_escape_string($_POST["change"])) : '';
	
		$result = mysql_query("SELECT * FROM `".$tb."`", $link);
		$name = mysql_field_name($result, $i);
		$nameid = mysql_field_name($result, 0);
		
		for($j=0; $j<$goto; $j++)
		{
			$row = mysql_fetch_row($result);
		}
		
		if (mysql_query("UPDATE `".$tb."` SET `".$name."`='".$change."' WHERE (`".$nameid."`='".$row[0]."') LIMIT 1"))
		{
			echo '<span class="on">Field result successfully changed.</span><br/>';
			echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=viewrow&amp;tb='.$tb.'&amp;goto='.$goto.'">&lt;&lt;Back</a><br/>';
		}
		else
		{
			echo '<span class="off">'.mysql_error().'</span><br/>';
			echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=viewrow&amp;tb='.$tb.'&amp;goto='.$goto.'">&lt;&lt;Back</a><br/>';
		}
		
	
		break;
	
	
	
	case 'query':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		if ($tb) 
		{
			$query = 'SELECT * FROM `'.$tb.'` WHERE 1'; 
		}
		else 
		{
			$query = '';
		}
		
		echo '<form action="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=addquery&amp;tb='.$tb.'" method="post"><div>';
		echo 'SQL:<br/>';
		echo '<textarea name="query" cols="15" rows="5">'.$query.'</textarea><br/>';
		echo '<input type="submit" name="DoGo" value="Ok"/></div></form>';
		
		
		if ($tb)
		{
			echo 'Fields:<br/>';
			$fields = mysql_list_fields($db, $tb, $link);
			$colums = mysql_num_fields($fields);
			
			for($i=0; $i<$colums; $i++)
			{
				$name = mysql_field_name($fields, $i);
	
				$type = mysql_field_type($fields, $i);
	
				$len = mysql_field_len($fields, $i);
	
				echo '<span style="color: #0055FF">'.$name.'</span> <span style="color: #009900">'.$type.'('.$len.')</span>';
				echo '<br/>';
			}
		}
		
		break;
	
	
	
	case 'addquery':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();

		$query = isset($_POST["query"]) ? trim($_POST["query"]) : '';
		
		
		if ($query)
		{
			$result = mysql_query($query);
			
			
			
			if ($result === false)
			{
				echo '<span class="off">'.mysql_error().'</span><br/>';
				echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=query">&gt;&gt;Back</a><br/>';
			}
			else if ($result === true)
			{
				$affected = mysql_affected_rows();
				
				echo '<span class="on">Carmatebit shesrulda</span><br/><hr/>';
				echo htmlspecialchars($query).'<br/>';
				if ($affected > 1) echo 'Rows: '.$affected.'<br/>';
			
			}
			else
			{
				$num = mysql_num_rows($result);
				if ($num > 0)
				{
					echo '<div class="t">';
					echo 'Found: '.$num.'';
					echo '</div>';
					while($obj = mysql_fetch_object($result))
					{
						foreach($obj as $key=>$value)
						{
							echo '<span class="ank">'.$key.':</span> '.$value;
							echo '<br/>';
						}
						echo '<hr/>';
					}
				}
				else
				{
					echo '<span class="on">MySQL returned an empty result set (i.e. zero rows)</span><br/>';
				}
			}
		}	
		else
		{
			echo '<span class="off">Query is empty!</span><br/>';
		}	
		echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=query&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
		
		break;

	
	
	case 'tbdrop':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$confirm = isset($_POST["confirm"]) ? true : false;
		$cancel = isset($_POST["cancel"]) ? true : false;
	
		if ($confirm)
		{		
			if (mysql_query("DROP TABLE `".$tb."`"))
			{
				echo '<span class="on">Table `'.$tb.'` successfully droped!</span><br/>';
				echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=listtb&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
			}
			else
			{
				echo '<span class="off">'.mysql_error().'</span><br/>';
				echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=listtb&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
			}
		}
		else if ($cancel)
		{
			header('Location: '.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&mod=tbdetail&tb='.$tb);
		}
		else
		{
			echo '<span class="off">Do you really want to "DROP TABLE `'.$tb.'`"?</span><br/>';
			echo '<form action="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbdrop&amp;tb='.$tb.'" method="post"><div>';
			echo '<input type="submit" name="confirm" value="Yes"/> | ';
			echo ' <input type="submit" name="cancel" value="No"/>';
			echo '</div></form>';	
		}
		break;
	
	
	case 'tbempty':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$confirm = isset($_POST["confirm"]) ? true : false;
		$cancel = isset($_POST["cancel"]) ? true : false;
	
		if ($confirm)
		{		
			if (mysql_query("TRUNCATE TABLE `".$tb."`"))
			{
				echo '<span class="on">Table `'.$tb.'` successfully truncated!</span><br/>';
				echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbdetail&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
			}
			else
			{
				echo '<span class="off">'.mysql_error().'</span><br/>';
				echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbdetail&amp;tb='.$tb.'">&lt;&lt;Back</a><br/>';
			}
		}
		else if ($cancel)
		{
			header('Location: '.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&mod=tbdetail&tb='.$tb);
		}
		else
		{
			echo '<span class="off">Do you really want to "TRUNCATE TABLE `'.$tb.'`"?</span><br/>';
			echo '<form action="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=tbempty&amp;tb='.$tb.'" method="post"><div>';
			echo '<input type="submit" name="confirm" value="Yes"/> | ';
			echo ' <input type="submit" name="cancel" value="No"/>';
			echo '</div></form>';	
		}
		break;	
	
	
	
	case 'import':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
	
		echo '<form enctype="multipart/form-data" action="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=addimport" method="post"><div>';
		echo 'Upload SQL file:<br/>';
		echo '<input type="file" name="file" size="16"/><br/>';
		echo 'or<br/>';	
		echo 'Import SQL file:<br/>';
		echo '<input type="text" name="imp" value="http://" size="16"/><br/>';	
		echo '<input type="submit" name="DoGo" value="Ok"/></div></form>';
		
		
		break;
	
	
	
	
	case 'addimport':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$err = '';
		
		$file = isset($_FILES["file"]) ? $_FILES["file"] : false;
		$imp = isset($_POST["imp"]) ? $_POST["imp"] : 'http://';	
		
		if (!$file && $imp == 'http://') 
		{
			$err = 'File not choiced!';
		}
		
		if ($file)
		{
			$data = @file_get_contents($file["tmp_name"]);	
		}
		else if ($imp)
		{
			$data = @file_get_contents($imp);	
		}	
		if (!$data) 
		{
			$err = 'File is empty!';
		}
		
		if ($err)
		{
			echo '<span class="off">'.$err.'</span>';
		}
		else
		{
			$queryes = preg_split("#(SELECT|CREATE|DROP|UPDATE|INSERT|SHOW|REVOKE|MATCH|LIKE|GRANT|DESCRIBE|OPTIMIZE|COUNT|ALTER|AGAINST|)[-a-z0-9_.:@&?=+,`!/~*'%$\"\s\n]*;#i", $data);
			$count = sizeof($queryes) - 1;
			echo '<span class="ank">Queryes:</span> '.$count.'<br/>';
			for($i=0;$i<$count;$i++)
			{
				$b = $i + 1;
				echo '<b>'.$b.') </b> ';
				if (mysql_query($queryes[$i]))
				{
					echo '<span class="on">Query sucefully executed</span><br/>';
				} 
				else 
				{ 
					echo '<span class="off">Error: '.mysql_error().'</span><br/>';
				}
			}
			
		}
		
		break;
	
	
	
	
	case 'info':
		$link = db_connect();
		$db = db_select($link);
		db_pathway();
		
		$query = mysql_query("SHOW GLOBAL VARIABLES");
		
		while($result = mysql_fetch_object($query))
		{ 
			echo '<span class="ank">'.$result->Variable_name.':</span>'.$result->Value;
			echo '<br/>';
		}
		break;
	
	
	case 'exit':
		session_destroy();
		//setcookie("password",'', time() + 3600 * 24 * 365, "/");
		echo '<span class="on">Your session destroyed! good bye :)</span><br/>';
		echo '<a href="'.$_SERVER["PHP_SELF"].'">&lt;&lt; Main</a>';
		break;
	
	case 'about':

		echo '<a href="http://wapmyadmin.googlecode.com">wapMyAdmin</a> is Free Software released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html">GNU/GPL License</a>.';
		echo '<br />';
		echo 'Author: LONGMAN (<a href="http://geg.ge">geg.ge</a>)';
		echo '<br />';
		echo '<a href="'.$_SERVER["PHP_SELF"].'">&lt;&lt; Main</a>';

		
		break;
	
	
}



if (!$mod || $mod == 'listdb')
{
	echo '<hr/>';
	echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=about">About Project</a><br />';
	if ($mod == 'listdb')
	{
		echo '<a href="'.$_SERVER["SCRIPT_NAME"].'?'.$SID.'&amp;mod=exit">Logout</a>';
	}

}

echo '<div class="z">';
echo 'wapMyAdmin is Free Software released under the GNU/GPL License.';
echo '<br/>';

echo round(microtime(true) - $start, 4).' sec';
echo '</div>';
echo '</div>';
echo '</body>';
echo '</html>';
ob_end_flush();
