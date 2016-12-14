<?php

class Importer {
    public $importerErrors = false;
    public $errors = array();
    private $conn = null;

    public function __construct($host, $user, $pass, $port = false) {
        if ($port==false){
            $port = ini_get("mysqli.default_port");
        }
        $this->importerErrors = false;
        $this->errors = array();
        $this->conn = new mysqli($host, $user, $pass, "", $port);
        if ($this->conn->connect_error) {
            $this->addErr("Connect Error (".$this->conn->connect_errno.") ".$this->conn->connect_error);
        }
    }

    private function addErr($errStr){
        $this->importerErrors = true;
        $this->errors[] = $errStr;
    }

    public function doImport($databaseFile, $database = "", $createDb = false) {
        if ($this->importerErrors == false) {

            if ($createDb && $database!=""){
                if (!$this->conn->query("CREATE DATABASE IF NOT EXISTS ".$database)){
                    $this->addErr("Query error (".$this->conn->errno.") ".$this->conn->error);
                }
            }
            if ($database!=""){
                if (!$this->conn->select_db($database)){
                    $this->addErr("Query error (".$this->conn->errno.") ".$this->conn->error);
                }
            }
            if (is_file($databaseFile) && is_readable($databaseFile)){
                try {
                    $fRead = fopen($databaseFile,"r");
                    $databaseFile = fread($fRead, filesize($databaseFile));
                    // processing and parsing the content
                    $databaseFile = str_replace("\r","\n",$databaseFile);
                    $totalLines = preg_split("/\n/", $databaseFile);
                    $queryStr = "";
                    foreach($totalLines as $line){
                        $LeftSideLine = ltrim($line);
                        $t_queryStr = trim($queryStr);
                        if (1==preg_match("/^#|^\-\-/",$LeftSideLine) && $t_queryStr == ""){
                            continue; // skip one-line comments
                        }
                        $queryStr .= $line."\n"; // append the line to the current query
                        $rightSideLine = rtrim($LeftSideLine);
                        if (1!==preg_match("/;$/",$rightSideLine)){
                            continue; // skip incomplete statement
                        }
                        if (substr_count($queryStr,"/*")!=substr_count($queryStr,"*/")){
                            continue; // skip incomplete statement (hack for multiline comments)
                        }
                        $queryStr = trim($queryStr);
                        if (!$this->conn->query($queryStr)){
                            $this->addErr("Query error (".$this->conn->errno.") ".$this->conn->error."\r\n\r\nOriginal Query:\r\n\r\n".$queryStr);
                        }
                        $queryStr="";
                    }
                } catch(Exception $error) {
                    $this->addErr("File error: (".$error->getCode().") ".$error->getMessage());
                }
            } else {
                $this->addErr("File error: '".$databaseFile."' is not a readable file.");
            }
        }
    }
}

?>
