<?php
require('./db_info.php');
require('./marker_grouper.php');
require('user_block.php');
if($_SERVER["HTTP_CF_CONNECTING_IP"]==null){
    $ip = $_SERVER['REMOTE_ADDR'];
}else $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
$db = new mysqli($libredb['host'], $libredb['user'], $libredb['password'],$libredb['db']);
$db->query("SET names utf8mb4");
$grpnum = marker_grouper::input($_POST['GroupText'],$db);
$update_sql = 'UPDATE tb_marker SET Location_Name = "'.$db->real_escape_string($_POST['LocName']).'", Document_Name = "'.$db->real_escape_string($_POST['DocName']).'", Zoom_Level = "'.$db->real_escape_string($_POST['Zoom']).'", Groups = "'.$grpnum.'" WHERE `ID` = '.intval($_POST['pID']);
$db->query($update_sql);
$log_sql = 'INSERT INTO tb_logs (IP, markerid, what) VALUES("'.$ip.'", '.intval($_POST['pID']).',"correct")';
$db->query($log_sql);
marker_grouper::count_and_delete($db);
$db->close();
?>
