<?php

use arhone\templater\Templater;

include '../vendor/autoload.php';

$templater = new Templater();

try {

    echo $templater->render([
        __DIR__ . '/template/new.tpl',
        __DIR__ . '/template/default.tpl'
    ], [
        'title' => 'Мой сайт'
    ]);

    /*$template = $templater->load(__DIR__ . '/template/default.tpl');
    if ($template->isLoaded()) {

        echo $template->render(null, [
            'title' => 'Заголовок'
        ]);

    }*/


} catch (Exception $exception) {

    echo $exception->getMessage();

}

