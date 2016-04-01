<?php
require('./db_info.php');
require('./marker_grouper.php');
require('user_block.php');
if($_SERVER["HTTP_CF_CONNECTING_IP"]==null){
    $ip = $_SERVER['REMOTE_ADDR'];
}else $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
if($_POST['LocName']==null||$_POST['DocName']==null||$_POST['Lat']==null||$_POST['Lng']==null) exit();
$db = new mysqli($libredb['host'], $libredb['user'], $libredb['password'],$libredb['db']);
$db->query("SET names utf8mb4");
$sql = 'SELECT * FROM tb_contribute WHERE name = BINARY "'.$db->real_escape_string($_POST['by']).'"';
$contres = $db->query($sql);
if($contres->num_rows == 0){
    if(!empty($_POST['isIP'])){
        $isip = 1;
    }else{
        $isip = 0;
    }
    $sql = 'INSERT INTO tb_contribute (isIP, `name`) VALUES('.$isip.', "'.$db->real_escape_string($_POST['by']).'")';
    $db->query($sql);
    $uid = $db->insert_id;
}
else{
    $row = $contres->fetch_assoc();
    $uid = $row['idtb_contribute'];
}
$grpnum = marker_grouper::input($_POST['GroupText'], $db);
$reg_sql = 'INSERT INTO tb_marker (Location_Name, Document_Name, Latitude, Longitude, Zoom_Level, Madeby, Groups) VALUES("'.$db->real_escape_string($_POST['LocName']).'", "'.$db->real_escape_string($_POST['DocName']).'", '.doubleval($_POST['Lat']).', '.doubleval($_POST['Lng']).', "'.$db->real_escape_string($_POST['Zoom']).'", '.$uid.', "'.$grpnum.'")';
$db->query($reg_sql);
$log_sql = 'INSERT INTO tb_logs (IP, markerid, what) VALUES("'.$ip.'", '.$db->insert_id.',"register")';
$db->query($log_sql);
$db->close();
?>
