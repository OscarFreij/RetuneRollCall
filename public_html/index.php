<!DOCTYPE html>
<html lang="en">
<?php
require "../private_html/modules/head.php";
?>
<body>
    <h1>RetuneRollCall ONLINE!</h1>
</body>
</html>

<?php
session_start();
require_once "../private_html/container.php";
$container = new container();

if (isset($_GET['page']))
{
    switch ($_GET['page'])
    {
        case 'login':
            require "../private_html/pages/login.php";
            break;
        case 'logout':
            $container->functions()->logout();
            break;
        case 'admin':
            require "../private_html/pages/admin.php";
            break;
        case 'display':
            require "../private_html/pages/display.php";
            break;
        case 'home':
            require "../private_html/pages/home.php";
            break;
        default:
            http_response_code(404);
            break;
    }
}
else
{
    require "../private_html/pages/home.php";
}

if (http_response_code() != 200)
{
    switch (http_response_code()) {
        case 400:
            require "../private_html/pages/errors/400.php";
            break;

        case 401:
            require "../private_html/pages/errors/401.php";
            break;

        case 404:
            require "../private_html/pages/errors/404.php";
            break;

        case 500:
            require "../private_html/pages/errors/500.php";
            break;
        
        default:
            error_log("index recived http response code: ".http_response_code());
            break;
    }
    
}

?>