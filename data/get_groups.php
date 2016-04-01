<?php
require('./db_info.php');
$db = new mysqli($libredb['host'], $libredb['user'], $libredb['password'],$libredb['db']);
$db->query("SET names utf8mb4");
$sql = 'SELECT * FROM tb_groups';
$res = $db->query($sql);
$db->close();
$resarray = array();
while($row = $res->fetch_assoc()){
    $subarray = array(
        "num" => (INT)$row['idtb_Groups'],
        "name" => $row['Group_Name']
    );
    array_push($resarray,$subarray);
}
print_r(json_encode($resarray, JSON_UNESCAPED_UNICODE));
?>
