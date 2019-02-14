<?php
/**
 * @var \arhone\templater\TemplaterInterface $this
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