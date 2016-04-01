<?php
if($_SERVER["HTTP_CF_CONNECTING_IP"]==null){
    $__ip = $_SERVER['REMOTE_ADDR'];
} else $__ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
$blocked_ip = ['111.111.111.111'];
if (in_array($__ip, $blocked_ip)) {
    exit();
}
?>
