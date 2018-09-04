<?php

use arhone\templating\Templater;

include '../vendor/autoload.php';

$Templater = new Templater();

try {

    echo $Templater->render([
        __DIR__ . '/template/new.tpl',
        __DIR__ . '/template/default.tpl'
    ], [
        'title' => 'Мой сайт'
    ]);

} catch (Exception $E) {

    echo $E->getMessage();

}

