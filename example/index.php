<?php
use arhone\template\Template;
include '../vendor/autoload.php';

$Template = new Template();

try {

    echo $Template->render([
        __DIR__ . '/template/new.tpl',
        __DIR__ . '/template/default.tpl'
    ], [
        'title' => 'Мой сайт'
    ]);

} catch (Exception $E) {

    echo $E->getMessage();

}

