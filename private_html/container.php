<?php

    /*
    * This file should always be included in the index.php file.
    * It will also act as the only access point to the *.class.php files.
    */

require_once "classes/db.class.php";    
require_once "classes/functions.class.php";    
require_once "classes/credentials.class.php";

class container
{
    private $db;
    private $functions;
    private $credentials;

    public function __construct()
    {
        // Container setup //
        date_default_timezone_set("Europe/Stockholm");
        //error_reporting(2147483647);
        //ini_set('error_log', "");
    }

    public function db()
    {
        if ($this->db instanceof db)
        {
            return $this->db;
        }
        else
        {
            return $this->db = new db($this);
        }
    }

    public function functions()
    {
        if ($this->functions instanceof functions)
        {
            return $this->functions;
        }
        else
        {
            return $this->functions = new functions($this);
        }
    }

    public function credentials()
    {
        if ($this->credentials instanceof credentials)
        {
            return $this->credentials;
        }
        else
        {
            return $this->credentials = new credentials($this);
        }
    }
}

?>