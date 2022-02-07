<?php

if (isset($_SESSION['id']))
{
    //Admin stuff
}
else
{
    http_response_code(401);
}

?>