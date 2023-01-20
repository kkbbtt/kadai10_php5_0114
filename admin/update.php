<?php
//1. POSTデータ取得
$name   = $_POST['name'];
$location  = $_POST['location'];
$comment = $_POST['comment'];
$point    = $_POST['point'];
$id     = $_POST['id'];
$img = '';

// 簡単なバリデーション処理。
if (trim($name) === '') {
    $err = 1;
}
// imgがある場合
if (isset($_FILES['img']['name'])) {
    $fileName = $_FILES['img']['name'];
    $img = date('YmdHis') . '_' . $_FILES['img']['name'];
}

// imgのバリデーション
if (!empty($fileName)) {
    $check =  substr($fileName, -3);
    if ($check != 'jpg' && $check != 'gif' && $check != 'png') {
        $err[] = 1;
    }
}

// もしerr配列に何か入っている場合はエラーなので、redirect関数でindexに戻す。その際、GETでerrを渡す。
if (isset($err) && count($err) > 0) {
    redirect('post.php?error');
}

/**
 * (1)$_FILES['img']['tmp_name']... 一時的にアップロードされたファイル
 * (2)'../picture/' . $image...写真を保存したい場所。先にフォルダを作成しておく。
 * (3)move_uploaded_fileで、（１）の写真を（２）に移動させる。
 */
if (isset($_FILES['img']['name'])) {
    move_uploaded_file($_FILES['img']['tmp_name'], '../images/' . $img);
}

//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

//３．データ登録SQL作成
// もし写真がある場合は、UPDATE文の中にimgを追加
if (isset($_FILES['img']['name']) && $_FILES['img']['name'] !== '') {
    $stmt = $pdo->prepare('UPDATE gs_bm_table04
                        SET
                            name = :name,
                            location = :location,
                            point = :point,
                            comment = :comment,
                            img = :img,');
    $stmt->bindValue(':img', $img, PDO::PARAM_STR);
} else {
    //  画像がない場合imgは省略する。
    $stmt = $pdo->prepare('UPDATE gs_bm_table04
                        SET
                            name = :name,
                            comment = :comment,
                            point = :point,
                            location = :location,');
}
$stmt->bindValue(':name',   $name,   PDO::PARAM_STR);
$stmt->bindValue(':location',  $location,  PDO::PARAM_STR);
$stmt->bindValue(':point',    $point,    PDO::PARAM_INT);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':id',     $id,     PDO::PARAM_INT);

$status = $stmt->execute(); //実行

//４．データ登録処理後
if (!$status) {
    sql_error($stmt);
} else {
    redirect('select.php');
}