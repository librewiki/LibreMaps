<?php
require('./db_info.php');
require('user_block.php');
require('./marker_grouper.php');
if($_SERVER["HTTP_CF_CONNECTING_IP"]==null){
    $ip = $_SERVER['REMOTE_ADDR'];
}else $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
$db = new mysqli($libredb['host'], $libredb['user'], $libredb['password'],$libredb['db']);
$db->query("SET names utf8mb4");
$sql = 'DELETE FROM tb_marker WHERE ID = '.$db->real_escape_string($_POST['pID']);
$db->query($sql);
$sql2 = 'INSERT INTO tb_logs (IP, markerid, commentmsg, what) VALUES("'.$ip.'", '.intval($_POST['pID']).',"'.$db->real_escape_string($_POST['com']).'", "delete")';
$db->query($sql2);
marker_grouper::count_and_delete($db);
$db->close();
?>
