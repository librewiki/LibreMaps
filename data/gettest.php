<?php
if($_SERVER["HTTP_CF_CONNECTING_IP"]==null){
    $ip = $_SERVER['REMOTE_ADDR'];
}else $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
echo $ip;
echo <<<heredoc
dfdfad
dffdfdf
dfddfff
vv
heredoc;
?>
