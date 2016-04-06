<?php
require('../lib/simplehtmldom_1_5/simple_html_dom.php');
$pagename = htmlspecialchars($_POST['pname']);//escape
?>
<h1 id="firstHeading" class="firstHeading" lang="ko">
  <span><?php echo $pagename; ?></span>
</h1>
<div class="control">
  <div class="contributer">
    마커 등록자: <?php echo htmlspecialchars($_POST['cont']);?>
  </div>
  <br>
  <div class="delcor">
    <span class="corr_marker"><a href="javascript:">마커 수정</a></span>&nbsp;|&nbsp;
    <span class="del_marker"><a href="javascript:">마커 삭제</a></span>
  </div>
  <b><a href="https://librewiki.net/wiki/<?php echo $pagename; ?>"> 리브레 위키에서 보기 <img src="/images/Libre_Wiki-Logo.png" width="5%"></a></b>
</div>
<?php
$page = urlencode(str_replace(" ","_",$pagename));
$htmldata = file_get_contents('https://librewiki.net/api.php?action=parse&page='.$page.'&format=json&formatversion=2');
if ($htmldata !== false) {
   $pagedata = json_decode($htmldata);
   if (property_exists($pagedata, 'parse')){
     $htmltext = str_get_html($pagedata->parse->text);
     if($links = $htmltext->find('a[!class],a.new,a.image,a.mw-redirect')){
         $leng = count($links);
         for($i=0; $i<$leng; ++$i) {
             $links[$i]->href = 'https://librewiki.net'.$links[$i]->href;
             $links[$i]->target = '_blank';
         }
     }
     if($links = $htmltext->find('img')){
         $leng = count($links);
         for($i=0; $i<$leng; ++$i) {
             $links[$i]->src = 'https://librewiki.net'.$links[$i]->src;
             if($links[$i]->srcset){
                 $links[$i]->srcset = '';
             }
         }
     }
     echo $htmltext->outertext;
   } else {
     ?>
     <b>
     <h3>서버와의 연결이 원활하지 않거나 문서가 없습니다.</h3>
     <div class="del_marker">
     <span class="corr_marker">
     </div>
     <a href="https://librewiki.net/index.php?title={$pagename}&action=edit">문서 작성하러 리브레 위키로 가기</a>
     </b>
     <?php
   }
}
//    echo "asdfsadf"
//  }
//     // if($toc = $htmldata->find('#toc',0)){
//     //     $toc->outertext='';
//     // }
//     // if($tble = $htmldata->find('.wikitable,.infobox')){
//     //     $leng = count($tble);
//     //     for($i=0; $i<$leng; ++$i){
//     //         $tble[$i]->outertext='';
//     //     }
//     // }
//     // if($links = $htmldata->find('a[!class],a.new,a.image,a.mw-redirect')){
//     //     $leng3 = count($links);
//     //     for($i=0; $i<$leng3; ++$i) {
//     //         $links[$i]->href = 'https://librewiki.net'.$links[$i]->href;
//     //         $links[$i]->target = '_blank';
//     //     }
//     // }
//     // foreach ($htmldata->find('.reference a,.mw-cite-backlink a') as $res) {
//     //     $res->href = strstr($res->href, '#');
//     //     $res->target = '';
//     // }
//     // if($links2 = $htmldata->find('img')){
//     //     $leng4 = count($links2);
//     //     for($i=0; $i<$leng4; ++$i) {
//     //         $links2[$i]->src = 'https://librewiki.net'.$links2[$i]->src;
//     //         if($links2[$i]->srcset){
//     //             $links2[$i]->srcset = '';
//     //         }
//     //     }
//     // }
//     // $htmldata->find('#jump-to-nav',0)->outertext = '';
//     // echo '<div class="infowiki"><div class="control"><div class="contributer">마커 등록자: ',htmlspecialchars($_POST['cont']),'</div><br><div class="delcor"><span class="corr_marker"><a>마커 수정</a></span>&nbsp;|&nbsp;<span class="del_marker"><a>마커 삭제</a></span></div></div>',$htmldata->find('#firstHeading',0)->outertext,'<b><a href="https://librewiki.net/wiki/',$_POST['pname'],'">리브레 위키에서 보기 <img src="/images/Libre_Wiki-Logo.png" width="5%"></a></b>',$htmldata->find('.libre_main_content',0)->outertext,'</div>';
// }else{
// echo <<<heredoc
// <h1 id="firstHeading" class="firstHeading" lang="ko">
// <span dir="auto">문서를 찾을 수 없습니다.</span>
// </h1>
// <b>
// <h3>서버와의 연결이 원활하지 않거나 문서가 없습니다.</h3>
// <div class="del_marker">
// <span class="corr_marker">
// <a>마커 수정</a>
// </span>&nbsp;|&nbsp;
// <a>마커 삭제</a>
// </div>
// <a href="https://librewiki.net/index.php?title={$_POST['pname']}&action=edit">문서 작성하러 리브레 위키로 가기</a>
// </b>
// heredoc;
// }
// ?>
