<?php
include 'TemplateInterface.php';
include 'Template.php';

$Template = new \arhone\template\Template();
$Template->set('body', 'Содержимое');


?>

<html>
    <head>
        <title><?=$Template->title ?? 'Заголовок'?></title>
    </head>
    <body>


    <?php $Template->start('body')?>
        qwe
    <?=$Template->end('body')?>
    
    </body>
</html>
