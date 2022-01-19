<?php

class db
{
    private $container;
    private $pdo;

    public function __construct(container $container)
    {
        $this->container = $container;    
        
        try 
        {
            $credentials = $this->container->credentials()->getDBCredentials();
            $servername = $credentials['servername'];
            $dbname = $credentials['dbname'];
            $username = $credentials['username'];
            $password = $credentials['password'];

            $this->pdo = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log("PDO Connection Astablished", 0);
        }
        catch(PDOException $e)
        {
            throw new Exception("PDO Connection Error: ".$e->getMessage(), 1);
        }   
    }

    public function constructQuerry($querry)
    {
        try 
        {
            $this->pdo->exec($querry);
        }
        catch(PDOException $e)
        {
            throw new Exception("PDO Querry Error: ".$e->getMessage(), 1);
            return false;
        }  
        return true;
    }
    
    public function constructResultQuerry($querry)
    {
        try 
        {
            $stmt = $this->pdo->prepare($querry);
            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            throw new Exception("PDO Querry Error: ".$e->getMessage(), 1);
            return false;
        }  
        return $result;
    }

    public function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
}
?>