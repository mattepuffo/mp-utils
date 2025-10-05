<?php

/**
 * @author Matteo Ferrone
 * @since 2017-03-20
 * @version 1.1
 */
class Ftp {

    private $server;
    private $username;
    private $password;
    private $connessione;

    /**
     * 
     * @param string $server Indirizzo server
     * @param string $username Username ftp
     * @param string $password Pasword ftp
     */
    public function __construct($server, $username, $password) {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->connessione = ftp_connect($this->server);
        $login = ftp_login($this->connessione, $this->username, $this->password);
        if (!$this->connessione || !$login) {
            echo '<p class="error">IMPOSSIBILE EFFETTUARE IL LOGIN FTP</p>';
        } else {
            if (!ftp_pasv($this->connessione, TRUE)) {
                echo '<p class="error">IMPOSSIBILE ATTIVARE PASS MOD</p>';
            }
        }
    }

    /**
     * Cambia directory all'interno del server
     * 
     * @param string $dir Directory di destinazione
     * @return string Ritorna l'errore in caso di impossibilità accesso directory
     */
    public function changeDir($dir) {
        if (!ftp_chdir($this->connessione, $dir)) {
            echo '<p class="error">IMPOSSIBILE ACCEDERE ALLA DIRECTORY</p>';
        }
    }

    /**
     * Lista i files della directory corrente
     * 
     * @return array Lista files
     */
    public function listFiles() {
        $list = ftp_nlist($this->connessione, '.');
        return $list;
    }

    /**
     * Controlla estensione del file
     * 
     * @param string $f File da controllare
     * @return string Estensione del file
     */
    public function checkExt($f) {
        $ext = pathinfo($f, PATHINFO_EXTENSION);
        return $ext;
    }

    /**
     * Esegue il download di un file
     * 
     * @param string $local Path locale in cui salvare il file
     * @param string $remote Path del file remoto da prendere
     * @param string $mode Modalità di trasferimento (FTP_ASCII o FTP_BINARY)
     * @return string Ritorna l'errore in caso di impossibilità scaricare il file
     */
    public function download($local, $remote, $mode = FTP_ASCII) {
        if (!ftp_get($this->connessione, $local, $remote, $mode)) {
            echo '<p class="error">IMPOSSIBILE SCARICARE IL FILE: ' . $remote . '</p>';
        }
    }

    /**
     * Esegue l'upload di un file
     * 
     * @param string $remote Path remoto in cui salvare il file
     * @param string $local Path locale in cui prendere il file
     * @param string $mode Modalità di trasferimento (FTP_ASCII o FTP_BINARY)
     * @return string Ritorna l'errore in caso di impossibilità caricare il file
     */
    public function upload($remote, $local, $mode = FTP_ASCII) {
        if (!ftp_put($this->connessione, $remote, $local, $mode)) {
            echo '<p class="error">IMPOSSIBILE CARICARE IL FILE: ' . $local . '</p>';
        }
    }

    /**
     * Cancella il file remoto
     * 
     * @param string $remoteFile Pathe del file remoto da cancellare
     */
    public function delete($remoteFile) {
        if (!ftp_delete($this->connessione, $remoteFile)) {
            echo '<p class="error">IMPOSSIBILE CANCELLARE IL FILE REMOTO: ' . $remoteFile . '</p>';
        }
    }

    /**
     * Distruttore, esegue la disconnessione
     */
    public function __destruct() {
        ftp_close($this->connessione);
    }

}
