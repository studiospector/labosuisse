<?php

class Ftps {

    private $host;
    private $port;
    private $user;
    private $password;
    private $ftp_conn;

    /**
     * Inizializza la classe
     */
    public function __construct($host, $port, $user, $password) {
        $this->host = $host;
        if($port == '')
            $this->port = 21;
        else
            $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->ftp_conn = ftp_ssl_connect($this->host, $this->port);
    }

    /**
     * Controlla che la connessione FTPS sia corretta
     */
    public function checkConnection(){
        $login_result = ftp_login($this->ftp_conn, $this->user, $this->password);
        ftp_pasv($this->ftp_conn, true);
        if($login_result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Lista i file presenti nella $directory
     */
    public function lsDirectory($directory){
      $contents = array();
      $docs = ftp_nlist($this->ftp_conn, $directory);
      if(!is_array($docs))
        $docs = array();
      foreach($docs as $doc){
        $contents[] = basename($doc);
      }
      if(count($contents) > 0){
        return $contents;
      }else{
        return false;
      }
    }

    /**
     * Scarica il file
     */
    public function download_file($file_path){
      $size = ftp_size($this->ftp_conn, $file_path);
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=" . basename($file_path));
      header("Content-Length: $size");
      ftp_get($this->ftp_conn, "php://output", $file_path, FTP_BINARY);
    }

    /**
     * Crea il file temporaneo
     */
    public function getTempFile($file, $file_path){
      if(ftp_get($this->ftp_conn, $file, $file_path, FTP_BINARY)){
        return true;
      }else
        return false;
    }

    /**
     * Sposta il file (usato per spostare i file nelle cartelle di backup)
     */
    public function moveFile($old, $new){
      echo $old."<br/>";
      echo $new;
      ftp_rename($this->ftp_conn, $old, $new);
    }

    /**
     * Carica il file
     */
    public function putFile($remote, $file){
      ftp_fput($this->ftp_conn, $remote, $file, FTP_ASCII);
    }
}
