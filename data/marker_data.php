<?php
require('./db_info.php');
require('./marker_grouper.php');
$db = new mysqli($libredb['host'], $libredb['user'], $libredb['password'], $libredb['db']);
$db->query("SET names utf8mb4");
$sql = 'SELECT a.ID, a.Location_Name, a.Document_Name, a.Latitude, a.Longitude, a.Zoom_Level, a.Groups, tb_contribute.`Name` FROM tb_marker a INNER JOIN tb_contribute WHERE a.Madeby = tb_contribute.idtb_contribute';
$res = $db->query($sql);
$resarray = [array(),array(),array(),array(),array()];
while($row = $res->fetch_assoc()){
    $subarray = array(
        "ID" => (INT)$row['ID'],
        "Ln" => $row['Location_Name'],
        "Dn" => $row['Document_Name'],
        "Lat" => (double)$row['Latitude'],
        "Lng" => (double)$row['Longitude'],
        "Grp" => marker_grouper::output($row['Groups'],$db),
        //"Zoom" => $row['Zoom_Level'],
        "By" => $row['Name']
    );
    array_push($resarray[$row['Zoom_Level']],$subarray);
}
$db->close();
print_r(json_encode($resarray, JSON_UNESCAPED_UNICODE));
?>
