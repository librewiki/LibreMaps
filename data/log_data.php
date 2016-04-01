<?php
require('./db_info.php');
$db = new mysqli($libredb['host'], $libredb['user'], $libredb['password'], $libredb['db']);
$db->query("SET names utf8mb4");
$sql = 'SELECT A.`date`, A.`what`, B.`Location_Name`, B.`Latitude`, B.`Longitude`, C.`Name`, A.`commentmsg` FROM tb_logs A INNER JOIN tb_marker B ON A.`markerid` = B.`ID` INNER JOIN tb_contribute C ON B.`Madeby` = C.`idtb_contribute` ORDER BY `idtb_logs` DESC LIMIT 20';
$res = $db->query($sql);
$resarray = array();
while($row = $res->fetch_assoc()){
    $subarray = array(
        "date" => $row['date'],
        "what" => $row['what'],
        "Ln" => $row['Location_Name'],
        "Name" => $row['Name'],
        "cmt" => $row['commentmsg'],
        "Lat" => $row['Latitude'],
        "Lng" => $row['Longitude']
    );
    array_push($resarray,$subarray);
}
$db->close();
print_r(json_encode($resarray, JSON_UNESCAPED_UNICODE));
?>
