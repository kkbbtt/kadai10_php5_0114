<!DOCTYPE html>
<html lang="ja">

<head>
<title>クボシュラン・ガイド</title>
    <style>
        #gmap {
            height: 400px;
            width: 100%;
            margin-top: 10px;
            text-align: center;
            }

        .inner{
            display: flex;
            justify-content: center;
            align-items: center;
            }
    </style>
</head>

<body id="main">

<div id="gmap"></div>
    <script>
    function initMap() {
      var target = document.getElementById('gmap');  
      var sapporo = {lat: 43.07211879470651, lng: 141.3604925825979};  //EZOHUBの緯度経度
      var map = new google.maps.Map(document.getElementById('gmap'), {
        center: sapporo,
        zoom: 16
      });
      
      //情報ウィンドウのインスタンスの生成（後でマーカーに紐付け）
      var infowindow = new google.maps.InfoWindow();
      
      //PlacesService のインスタンスの生成（引数に map を指定）
      var service = new google.maps.places.PlacesService(map);
      
      if(!navigator.geolocation){ 
        //情報ウィンドウの位置をマップの中心位置に指定
        infowindow.setPosition(map.getCenter());
        //情報ウィンドウのコンテンツを設定
        infowindow.setContent('Geolocation に対応していません。');
        //情報ウィンドウを表示
        infowindow.open(map);
      }
      
      //ブラウザが対応している場合、position にユーザーの位置情報が入る
      navigator.geolocation.getCurrentPosition(function(position) { 
        //position から緯度経度（ユーザーの位置）のオブジェクトを作成し変数に代入
        var pos = {  
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        //情報ウィンドウに現在位置を指定
        infowindow.setPosition(pos);
        //情報ウィンドウのコンテンツを設定
        infowindow.setContent('現在位置を取得しました。');
        //情報ウィンドウを表示
        infowindow.open(map);
        //マップの中心位置を指定
        map.setCenter(pos);
        
        //種類（タイプ）やキーワードをもとに施設を検索（プレイス検索）するメソッド nearbySearch()
        service.nearbySearch({
          location: pos,  //検索するロケーション
          radius: 500,  //検索する半径（メートル）
          type: ['restaurant']  //タイプで検索。文字列またはその配列で指定
          //キーワードで検索する場合は name:'レストラン' や ['レストラン','中華'] のように指定
     
        }, callback);  //コールバック関数（callback）は別途定義
     
        //コールバック関数には results, status が渡されるので、status により条件分岐
        function callback(results, status) {
          // status は以下のような定数で判定（OK の場合は results が配列で返ってきます）
          if (status === google.maps.places.PlacesServiceStatus.OK) {
            //results の数だけ for 文で繰り返し処理
            for (var i = 0; i < results.length; i++) {
              //createMarker() はマーカーを生成する関数（別途定義）
              createMarker(results[i]);
            }
          }
        }
      }, function() {  //位置情報の取得をユーザーがブロックした場合のコールバック
        //情報ウィンドウの位置をマップの中心位置に指定
        infowindow.setPosition(map.getCenter());
        //情報ウィンドウのコンテンツを設定
        infowindow.setContent('Error: Geolocation が無効です。');
        //情報ウィンドウを表示
        infowindow.open(map);
      });   
      
      //マーカーを生成する関数（引数には検索結果の配列 results[i] が入ってきます）
      function createMarker(place) {
        //var placeLoc = place.geometry.location; 
        var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location  //results[i].geometry.location
        });
     
        //マーカーにイベントリスナを設定
        marker.addListener('click', function() {
          infowindow.setContent(place.name);  //results[i].name
          infowindow.open(map, this);
        });
      }
    }
        
    </script> 
    <script src="https://maps.googleapis.com/maps/api/js?key=■■■&callback=initMap&libraries=places" async defer></script>
    <!-- ■■■の部分は取得した APIキーで置き換えます。 -->  

    <div class="inner">
        <img src="img/bib-michelin-man-footer.svg" style="float: left; width:100px; padding:10px 25px 0px 0px; ">
        <p><a href="admin/login.php">ログインする</a></p>
        </div>

</body>
</html>