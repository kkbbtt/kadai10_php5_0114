<?php
// 0. SESSION開始！！
session_start();
require_once('funcs.php');
loginCheck();

// 1. ログインチェック処理！
// 以下、セッションID持ってたら、ok
// 持ってなければ、閲覧できない処理にする。
// ログイン処理の時に代入した$_SESSION['chk_ssid']を持っているか？
// もしくはサーバーのSESSION IDと一緒か？
// if (!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()) {
//     exit('LOGIN ERROR');
// }else{
// session_regenerate_id(true);
//     $_SESSION['chk_ssid'] = session_id();
// }
//以下ログインユーザーのみ処理が行われる。

//１．関数群の読み込み
require_once('funcs.php');

//２．データ登録SQL作成
$pdo = db_conn();
$stmt = $pdo->prepare('SELECT * FROM gs_bm_table04');
$status = $stmt->execute();

//３．データ表示
$view = '';
if ($status == false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //GETデータ送信リンク作成
        // <a>で囲う。
        $view .= '<tr><td class="a">';
        $view .= '<a href="https://maps.google.co.jp/maps?ll=' . $result['location'] . '&z=18' . '" target="_blank">';
        $view .='[ 地図を表示 ] ';
        $view .= '</a>';

        $view .= '<a href="detail.php?id=' . $result['id'] . '">';
        $view .= $result['name'];
        $view .= '</a>';

        $view .= '<a href="delete.php?id=' . $result['id'] . '">';
        $view .= '<span style="color:blue; margin: 5px; ">';
        $view .='[ 削除 ]';
        $view .= '</span>';
        $view .= '</a>';

        $view .= '</td><tr>';
    }
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>データベース表示</title>
    <link rel="stylesheet" href="css/range.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        div {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body id="main">
    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">データ登録</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <div>
    <table class="container jumbotron">
    <tr>
    <th class="a">データ一覧</th>
    </tr>
    <?= $view ?>
    </table>
    </div>

</body>

</html>
