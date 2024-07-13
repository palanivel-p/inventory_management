<?php

if($_COOKIE["field_api_key"]=='')
{

    header("Location:login?logout=1");
}
else
{
    header("Location: home/");
}

?>