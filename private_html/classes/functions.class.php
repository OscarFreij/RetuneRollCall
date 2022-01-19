<?php

class functions
{
    private $container;

    public function __construct(container $container)
    {
        $this->container = $container;    
    }

    private function checkLogin($username, $password)
    {
        $quotedUsername = $this->container->db()->quote($username);
        $result = $this->container->db()->constructResultQuerry("SELECT `password`, `enabled` FROM `users` WHERE `users.username` = $quotedUsername AND `users.enabled` = 1;");

        if ($result !== false && count($result) == 0)
        {
            $hash = $result[0]['password'];
            if ($result[0]['enabled'] == 1)
            {
                return password_verify($password, $hash);
            }
        }
        return false;
    }

    private function getUserData($username)
    {
        $quotedUsername = $this->container->db()->quote($username);
        return $result = $this->container->db()->constructResultQuerry("SELECT `id`, `teamAccess`, `superAdmin` FROM `users` WHERE `users.username` = $quotedUsername AND `users.enabled` = 1;");
    }

    public function login($username, $password)
    {
        if ($this->checkLogin($username, $password))
        {
            $result = $this->getUserData($username);

            session_start();
            $_SESSION['id'] = $result[0]['id'];
            $_SESSION['username'] = $username;
            $_SESSION['teamAccess'] = $result[0]['teamAccess'];
            $_SESSION['superAdmin'] = $result[0]['superAdmin'];
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location: http://$host$uri/");
    }
}
?>