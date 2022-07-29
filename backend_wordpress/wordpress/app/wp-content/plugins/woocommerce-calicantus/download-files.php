<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-load.php");

if (isset($_GET['f']) && $_GET['f'] != '' && isset($_GET['o']) && $_GET['o'] != '' && isset($_GET['t']) && $_GET['t'] != '') {
  $cc = new WC_Calicantus();
  $cc->download_file($_GET['o'], base64_decode($_GET['f']), $_GET['t']);
}
?>