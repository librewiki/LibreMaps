//require jquery,mapsapi,markerwithlabel
String.prototype.trim = function(){
    return this.replace(/\s/g, "");
};
var markerclick = false;
var map = null;
var markerleng = [];
var zoomLevel = 0;
var regmarker = null;
var markers;
var registerForm = (
    '<h1 id="firstHeading" class="firstHeading" lang="ko"><span dir="auto">마커 등록</span></h1>'+
    '<form>'+
    '등록자명:<input type="text" name="by">'+
    '라벨 문서명:<input type="text" name="LocName"><br />'+
    '연결될 문서명:<input type="text" name="DocName"><br />'+
    '분류:<input type="text" name="GrpName" placeholder="여러 개 구분은 세미콜론(;)으로 합니다."><br />'+
    '줌 수준:&nbsp;&nbsp;<label><input type="radio" name="Zoom" value="1">최대&nbsp;&nbsp;</label>'+
    '<label><input type="radio" name="Zoom" value="2">대&nbsp;&nbsp;</label>'+
    '<label><input type="radio" name="Zoom" value="3">중&nbsp;&nbsp;</label>'+
    '<label><input type="radio" name="Zoom" value="4">소&nbsp;&nbsp;</label>'+
    '<a href="/help.html" target="_blank">도움말</a></br>'+
    '마커는 퍼블릭 도메인으로 배포됩니다.'+
    '<button type="button" id="submit_button">등록!</button>'+
    '</form>'
);
var correctForm = (
    '<h1 id="firstHeading" class="firstHeading" lang="ko"><span dir="auto">마커 수정</span></h1>'+
    '<form>'+
    '라벨 문서명:<input type="text" name="C_LocName"><br />'+
    '연결될 문서명:<input type="text" name="C_DocName"><br />'+
    '분류:<input type="text" name="C_GrpName" placeholder="여러 개 구분은 세미콜론(;)으로 합니다."><br />'+
    '줌 수준:&nbsp;&nbsp;<label><input type="radio" name="C_Zoom" value="1">최대&nbsp;&nbsp;</label>'+
    '<label><input type="radio" name="C_Zoom" value="2">대&nbsp;&nbsp;</label>'+
    '<label><input type="radio" name="C_Zoom" value="3">중&nbsp;&nbsp;</label>'+
    '<label><input type="radio" name="C_Zoom" value="4">소&nbsp;&nbsp;</label>'+
    '<a href="/help.html" target="_blank">도움말</a></br>'+
    '마커는 퍼블릭 도메인으로 배포됩니다.'+
    '<button type="button" id="C_submit_button">편집!</button>'+
    '</form>'
);
var noticeText = (
    '<br>'+
    '<h1 id="firstHeading" class="firstHeading" lang="ko"><span dir="auto">리브레 맵스 공지</span></h1>'+
    '<p>리브레 맵스는 <a href="https://librewiki.net/">리브레 위키</a>의 자료를 이용하여 지도를 만들어 나가는 프로젝트입니다.'+
    '<p>새 마커를 등록하려면 원하는 위치에서 오른쪽 클릭을 해 주세요.</p>'+
    '<p>피드백은 <a href="https://issue.librewiki.net/">이슈 트래커</a> 또는 메일 nessunkim@gmail.com, <a href="https://librewiki.net/wiki/%EC%82%AC%EC%9A%A9%EC%9E%90%ED%86%A0%EB%A1%A0:Nessun">리브레 위키 사용자토론</a>에서 받습니다.</p>'+
    '<p><a href="/m/">모바일(등록은 불가)</a></p>'+
    '<p><a href="/help.html"> 도움말 보기 </a></p>'+
    '</div>'
);
var mobile = false;
function initialize() {
    'use strict';
    var mapOptions = {
        zoom: 3,
        disableDefaultUI: false,
        center: new google.maps.LatLng(37.555107, 126.970691)
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    google.maps.event.addListener(map, 'rightclick',registerMarkers);
    markers = getMarkers();
    google.maps.event.addListener(map, 'idle', zoomWithMarkers);
    geocoder = new google.maps.Geocoder();
    getgroups();
}
google.maps.event.addDomListener(window, 'load', initialize);

function showeffect(){
    'use strict';
    if (mobile) {
        $('#map-canvas').outerWidth('10%');
        $('#right-side').outerWidth('89.8%');
    } else {
        $('#map-canvas').outerWidth('60%');
        $('#right-side').outerWidth('39.8%');
    }
    var options = {direction :"right"};
    $('#right-side').show( "slide", options, 500 );
}

function getback() {
    'use strict';
    var options = {direction :"right"};
    $('#right-side').hide();
    $('#map-canvas').width('100%');
}

function registerMarkers(e){
    'use strict';
    showeffect();
    if(regmarker)regmarker.setMap(null);
    regmarker = new MarkerWithLabel({
        position: e.latLng,
        map: map,
        labelContent: "마커 등록중...",
        labelAnchor: new google.maps.Point(30, 70),
        labelClass: "labels",
        labelInBackground: false,
        zIndex: 5000
    });
    $('#data').html(registerForm);
    $('#submit_button').unbind("click");
    $('#submit_button').click(function(){
        $('#submit_button').unbind("click");
        var By = $('input[name=by]').val();
        var Loc = $('input[name=LocName]').val();
        var Doc =$('input[name=DocName]').val();
        var Zoomlv = $(':checked[name=Zoom]').val();
        var Groups = $('input[name=GrpName]').val();
        if (By.trim() === '' || Loc.trim() === '' || Doc.trim() === '' || $(':checked[name=Zoom]').length===0) {
            alert("분류 이외의 모든 항목은 필수입니다.");
        } else {
            $.ajax({
                url: '/data/register_marker.php',
                type: 'POST',
                data: {
                    by: By,
                    LocName: Loc,
                    DocName: Doc,
                    Zoom: Zoomlv,
                    GroupText: Groups,
                    Lat: e.latLng.lat(),
                    Lng: e.latLng.lng()
                }
            }).done(function(res) {
                location.reload();
            });
        }
        regmarker.setMap(null);
    });
}

function getgroups(){
    'use strict';
    $.ajax({
        url: '/data/get_groups.php',
        type: 'POST',
        dataType: 'json'
    }).done(function(res) {
        for(var i in res){
            $('#groupselect').append("<option value='"+res[i].name+"'>"+res[i].name+"</option>");
        }
    });
    $('#groupselect').change(function() {
        if($('#groupselect').val() === 9999){
            zoomWithMarkers(true);
        } else {
            for (var j=1; j<5; j++) {
                for (var i = 0; i < markerleng[j]; i++) {
                    var arr_group = markers[j][i].grpnums.split(';');
                    if (arr_group.indexOf($('#groupselect').val()) !== -1) {
                        markers[j][i].setVisible(true);
                    } else {
                        markers[j][i].setVisible(false);
                    }
                }
            }
        }
    });
}

function markerOnClick() {
    'use strict';
    markerclick = true;
    $('#mobile-view_on').css("z-index","999999");
    var pid = this.num;
    var pos = this.position;
    var Dname = this.doc;
    var Lname = this.labelContent;
    var zoom = this.zoomLv;
    var Grp = this.grpnums;
    if (regmarker) regmarker.setMap(null);
    $.ajax({
        url: '/data/get_libre_document.php',
        type: 'POST',
        dataType: 'html',
        data: {pname: this.doc, cont: this.by}
    }).done(function(res) {
        $('#right-side').scrollTop(0);
        showeffect();
        $('#data').html(res);
        $('.corr_marker').unbind();
        $('.corr_marker').click(function() {
            $('#data').html(correctForm);
            $('input[name=C_LocName]').val(Lname);
            $('input[name=C_DocName]').val(Dname);
            $('input[name=C_GrpName]').val(Grp);
            if(Grp==';') $('input[name=C_GrpName]').val('');
            $('[name=C_Zoom][value='+zoom+']').attr('checked', true);
            $('#C_submit_button').unbind("click");
            $('#C_submit_button').click(function() {
                $('#C_submit_button').unbind("click");
                var Loc = $('input[name=C_LocName]').val();
                var Doc = $('input[name=C_DocName]').val();
                var Zoomlv = $(':checked[name=C_Zoom]').val();
                var GroupTx = $('input[name=C_GrpName]').val();
                if (Loc.trim() === ''|| Doc.trim() === '' || $(':checked[name=C_Zoom]').length === 0) {
                    alert("다 입력해 주세요.");
                } else {
                    $.ajax({
                        url: '/data/correct_marker.php',
                        type: 'POST',
                        data: {
                            pID : pid,
                            LocName: Loc,
                            DocName: Doc,
                            Zoom: Zoomlv,
                            GroupText: GroupTx
                        }
                    }).done(function() {
                        location.reload();
                    });
                }
            });
        });
        $('.del_marker').unbind();
        $('.del_marker').click(function() {
            var reason = prompt("삭제 사유를 입력해 주십시오.");
            if (reason.replace(/^\s+|\s+$/g,"") !== '') {
                $.ajax({
                    url: '/data/delete_marker.php',
                    type: 'POST',
                    data: {pID: pid, com: reason}
                })
                .done(function(res) {
                    alert("삭제되었습니다.");
                    location.reload();
                });
            } else {
                alert("사유를 입력해야 합니다.");
            }
        });
    });
}
function getMarkers() {
    'use strict';
    var batch = [[],[],[],[],[]];
    $.ajax({
        url: '/data/marker_data.php',
        type: 'POST',
        dataType: 'json',
        async: false
    }).done(function(json) {
        for(var j=1; j<5; ++j){
            var markerList = json[j];
            var leng = markerList.length;
            var marker;
            for (var i = 0; i < leng; i++) {
                marker = new MarkerWithLabel({
                    position: new google.maps.LatLng(markerList[i].Lat, markerList[i].Lng),
                    map: map,
                    zoomLv: j,
                    num: markerList[i].ID,
                    title: markerList[i].Dn,
                    doc: markerList[i].Dn,
                    by: markerList[i].By,
                    grpnums: markerList[i].Grp,
                    //icon: iconimg,
                    labelContent: markerList[i].Ln,
                    labelAnchor: new google.maps.Point(30, 70),
                    labelClass: "labels",
                    labelInBackground: false,
                    zIndex: (10-j)*100
                });
                marker.setVisible(false);
                batch[j].push(marker);
                google.maps.event.addListener(marker, "click", markerOnClick);//
            }
            markerleng[j] = batch[j].length;
        }
    });
    return batch;
}

//0~5~11~16
function zoomWithMarkers(pass){
    if (pass===null) pass = false;
    var currzoom = map.getZoom();
    if ($('#groupselect').val()!==9999 && pass===false) return;
    if (!pass) {
        if (currzoom>=0&&currzoom<=4) {
            if (zoomLevel==1) {
                return;
            } else zoomLevel = 1;
        } else if(currzoom>=5&&currzoom<=10){
            if (zoomLevel==2) {
                return;
            } else zoomLevel = 2;
        } else if(currzoom>=11&&currzoom<=15){
            if (zoomLevel==3) {
                return;
            } else zoomLevel = 3;
        } else {
            if (zoomLevel==4) {
                return;
            } else zoomLevel = 4;
        }
    }
    var i, j;
    for (i = 0; i < markerleng[zoomLevel]; i++) {
        markers[zoomLevel][i].setVisible(true);
    }
    for (j = 1; j < 5; j++) {
        if (j <= zoomLevel) continue;
        for (i = 0; i < markerleng[j]; i++) {
            markers[j][i].setVisible(false);
        }
    }
}
function codeAddress(){
    var address = document.getElementById('address').value;
    geocoder.geocode({'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var n = results.length;
            var searchwindow = [];
            alert(+n+'개 결과가 있습니다.');
            for (var i=0; i<n; i++) {
                searchwindow[i] = new google.maps.InfoWindow();
                searchwindow[i].setContent('<b>'+address+'</b>');
                searchwindow[i].setPosition(results[i].geometry.location);
                searchwindow[i].open(map);
            }
        } else {
            if (status === 'ZERO_RESULTS') {
                status = "결과가 없습니다.";
            }
            alert('실패: ' + status);
        }
    });
}
function notice(){
    showeffect();
    $('#data').html(noticeText);
}
function recent(){
    showeffect();
    var recentText='<table class="wikitable"><thead><tr><th>구분</th><th>시간</th><th>이름</th><th>원작성자</th></tr></thead><tbody>';
    $.ajax({
        url: '/data/log_data.php',
        dataType: 'json',
    })
    .done(function(res) {
        for (var i in res) {
            var what;
            if(res[i].what=='correct') {
                what='수정';
            } else if (res[i].what=='delete') {
                what='삭제';
            } else if (res[i].what=='register') {
                what='등록';
            }
            recentText+='<tr><td>'+what+'</td><td>'+res[i].date+'</td><td><a role="button" onclick="tomarker('+res[i].Lat+','+res[i].Lng+')">'+res[i].Ln+'</a></td><td>'+res[i].Name+'</td></tr>';
        }
        recentText+='</tbody></table>';
        $('#data').html(recentText);
    });
}
function tomarker(Lat, Lng){
    map.setZoom(15);
    map.setCenter(new google.maps.LatLng(Lat, Lng));
}
