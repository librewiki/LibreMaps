# Libre Maps
  지금 보니 코드가 정말 형편없습니다. 천천히 새로 짜기로 하고, 일단 기존 코드좀 수정해서 올려 봅니다.

## DB 설정
  db 만들고, dbinit.sql 돌려 주세요.

  /data 디렉토리 안에, db_info.php 파일을 만들어 다음과 같이 입력해 주세요.

    <?php
    $libredb = array('host' => "host_name" , 'user' => "user_name" , 'password' => "password" , 'db' =>"db_name" );
    ?>
