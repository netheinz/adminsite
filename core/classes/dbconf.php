<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class dbconf extends db {
    function __construct() {
        $this->dbhost = "127.0.0.1";
        $this->dbuser = "dbuser";
        $this->dbpassword = "dbpassword";
        $this->dbname = "database";
        $db = parent::_connect();
    }
}