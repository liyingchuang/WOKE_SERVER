<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxe2d928572092e5fd", "db348d4f9a7e96ba6ff3d18820dd19f3");
$url=$_GET['url'];
$url=urldecode($url);
$signPackage = $jssdk->GetSignPackage($url);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
echo json_encode($signPackage);
?>

