<?php

class functions
{
    private $container;

    public function __construct(container $container)
    {
        $this->container = $container;    
    }
    
    #region User Functions
    private function checkLogin(string $username, string $password)
    {
        $quotedUsername = $this->container->db()->quote($username);
        $result = $this->container->db()->constructResultQuerry("SELECT `password`, `enabled` FROM `users` WHERE `username` = $quotedUsername AND `enabled` = 1;");

        if ($result !== false && count($result) != 0)
        {
            $hash = $result[0]['password'];
            if ($result[0]['enabled'] == 1)
            {
                return password_verify($password, $hash);
            }
            else
            {
                error_log("User ".$quotedUsername." tried to login but is disabled");
            }
        }
        else
        {
            error_log("Unknown user tried to login: ".$quotedUsername);
        }
        return false;
    }

    public function changePassword(string $username, string $passwordOld, string $passwordNew)
    {
        if ($this->checkLogin($username, $passwordOld))
        {
            $quotedUsername = $this->container->db()->quote($username);
            $quotedPasswordNewHASH = $this->container->db()->quote(password_hash($passwordNew, PASSWORD_DEFAULT));
            $success = $this->container->db()->constructQuerry("UPDATE `users` SET `password`=$quotedPasswordNewHASH WHERE `username` = $quotedUsername AND `enabled` = 1;");
        }
        else
        {
            $success = false;
        }
        
        return $success;
    }

    public function createAccount(string $username, string $password)
    {
        if ($this->checkUserExists($username))
        {
            $quotedUsername = $this->container->db()->quote($username);
            $quotedPasswordHASH = $this->container->db()->quote(password_hash($password, PASSWORD_DEFAULT));
            $success = $this->container->db()->constructQuerry("INSERT INTO `users` (`username`, `password`) VALUES ($quotedUsername, $quotedPasswordHASH)");
        }
        else
        {
            $success = false;
        }
        
        return $success;
    }

    public function deleteAccount(string $username)
    {
        if ($this->checkUserExists($username))
        {
            $quotedUsername = $this->container->db()->quote($username);
            $success = $this->container->db()->constructQuerry("DELETE FROM `users` WHERE `username` = $quotedUsername");
        }
        else
        {
            $success = false;
        }
        
        return $success;
    }

    private function getUserData(string $username)
    {
        $quotedUsername = $this->container->db()->quote($username);
        return $this->container->db()->constructResultQuerry("SELECT `id`, `teamAccess`, `superAdmin`, `enabled` FROM `users` WHERE username = $quotedUsername;");
    }

    private function checkUserExists(string $username)
    {
        $quotedUsername = $this->container->db()->quote($username);
        if (count($this->container->db()->constructResultQuerry("SELECT `id`, `enabled` FROM `users` WHERE username = $quotedUsername;")) != 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function toggleUserEnabled(string $username)
    {
        $quotedUsername = $this->container->db()->quote($username);
        $result = $this->getUserData($username);

        if ($result[0]['enabled'] == 0)
        {
            $success = $this->container->db()->constructQuerry("UPDATE `users` SET `enabled` = 1 WHERE `username` = $quotedUsername;");
        }
        else
        {
            $success = $this->container->db()->constructQuerry("UPDATE `users` SET `enabled` = 0 WHERE `username` = $quotedUsername;");
        }
        return $success;
    }

    public function login(string $username, string $password)
    {
        if ($this->checkLogin($username, $password))
        {
            $result = $this->getUserData($username);

            session_start();
            $_SESSION['id'] = $result[0]['id'];
            $_SESSION['username'] = $username;
            $_SESSION['teamAccess'] = $result[0]['teamAccess'];
            $_SESSION['superAdmin'] = $result[0]['superAdmin'];
            return true;
        }
        else
        {
            return false;
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
    #endregion
}
?>