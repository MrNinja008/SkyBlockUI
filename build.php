<?php

$output = "SkyBlockUI.phar";

if (is_file($output)) {
    unlink($output);
}

$phar = new Phar($output);
$phar->startBuffering();
$phar->buildFromDirectory(__DIR__);
$phar->stopBuffering();

echo "SkyBlockUI phar file has been created";