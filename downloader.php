<?php

function autoload($className)
{
    $prefixSrc = "base/";
    require $prefixSrc . $className . ".php";
}
spl_autoload_register('autoload');

$img = new imgDownloader("files.list");
