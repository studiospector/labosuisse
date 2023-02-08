<?php

$hosts = 'storage.oms.calicant.us';
$port = 21;
$user = 'labo_wp_shop';
$pwd = 'WFjJSACrnRmMRZgdiUvD';


$ftp_conn = ftp_ssl_connect($hosts, $port) or die("Could not connect to $hosts");
echo '<p>ftp_ssl_connect:</p>';
var_dump($ftp_conn);
echo '<p>ftp_login:</p>';
$login = ftp_login($ftp_conn, $user, $pwd);
var_dump($login);
echo '<p>ftp_pasv:</p>';
ftp_set_option($ftp_conn, FTP_USEPASVADDRESS, false); // set ftp option
var_dump(ftp_pasv($ftp_conn, true));
echo '<p>ftp_nlist:</p>';
var_dump(ftp_nlist($ftp_conn, '.'));

