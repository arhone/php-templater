<?php
/**
 * @var \arhone\template\TemplateInterface $this
 */
?>
<html>
    <title><?=$this->title ?? 'Заголовок'?></title>
    <body>
    <?php $this->default('body')?>
    <div>Содержимое</div>
    <?=$this->end('body')?>
    </body>
</html>