<?php

$method = $_GET['method'];
$data = file_get_contents('php://stdin');

function get_method_call($method) {
  return "api_${method}";
}

$method_name = get_method_call($method);
if (function_exists($method_name)) {
  $method_name();
} else {
  echo "not found";
}

function new_pdo(){
  return new PDO('mysql:host=localhost;dbname=runway', 'root', 'admin', array());
}

function api_submituser() {
  $phone = $_POST['phone'];
  $shop = $_POST['shop'];
  $city = $_POST['city'];

  $pdo = new_pdo();
  $stm = $pdo->prepare('SELECT * FROM user_submit WHERE Fphone = :phone');
  $stm->execute(array(':phone' => $phone));
  $results = $stm->fetchAll();
  if (count($results) > 0) {
    echo json_encode(array(
      'success' => 0,
      'msg' => '已经填写资料',
    ));
  } else {
    $stm = $pdo->prepare("INSERT INTO user_submit (Fphone, Fcity, Fshop, Fcreated) VALUES (?, ?, ?, ?)");
    $ret = $stm->execute(array(
      $phone,
      $city,
      $shop,
      time()
    ));
    echo json_encode(array(
      'success' => 1,
      'msg' => '成功填写资料',
      'id' => $ret
    ));
  }
}

function api_export() {
  $pdo = new_pdo();
  $stm = $pdo->prepare("SELECT * FROM user_submit");
  $stm->execute();
  $results = $stm->fetchAll();
  if (count($results)) {
    ob_start();
    $f = fopen('php://output', 'w');
    fputcsv($f, array_keys($$results[0]));
    foreach($results as $row) {
      fputcsv($f, $row);
    }
    fclose($f);

    $csv = ob_get_clean();
    header('Content-Type: application/csv; charset=utf-8');
    header('Content-Type: application/download; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    echo $csv;
    die();
  } else {
    echo "暂无数据";
  }

}


