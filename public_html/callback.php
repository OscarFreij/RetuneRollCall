<?php
session_start();
require_once "../private_html/container.php";
if (!isset($container))
{
    $container = new container();
}

if (isset($_POST['cba'])) //cba stands for CallBack Action
{
    switch ($_POST['cba']) {
        case 'login':
            if (isset($_POST['username']) && isset($_POST['password']))
            {
                $success = $container->functions()->login($_POST['username'], $_POST['password']);
                if ($success)
                {
                    http_response_code(202);
                }
                else
                {
                    http_response_code(401);
                }
            }
            break;
        case 'create_user':
            if (isset($_POST['username']) && isset($_POST['password']))
            {
                if (isset($_SESSION['superAdmin']) && $_SESSION['superAdmin'] == 1)
                {
                    $success = $container->functions()->createAccount($_POST['username'], $_POST['password']);
                    if ($success)
                    {
                        http_response_code(201);
                    }
                    else
                    {
                        http_response_code(409);
                    }
                }
                else
                {
                    http_response_code(401);
                }
                
            }
            else
            {
                http_response_code(400);
            }
            break;
        default:
            http_response_code(400);
            break;
    }
}
else
{
    http_response_code(400);
}