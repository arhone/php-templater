# Templater
Нативный шаблонизатор (PHP 7)

Шаблонизатор предназначен для удобного разделения бизнес логики и логики представления.

Позволяет подключать файлы шаблонов и передавать в них готовые данные.

# Установка

```composer require arhone/templating```

```php
<?php

use arhone\templating\Templater;
include 'vendor/autoload.php';

$Template = new Templater();
```

# Примеры

##### Рендер шаблона

```php
<?php

echo $Templater->render(__DIR__ . '/template/default.tpl', [
    'title' => 'Мой сайт'
]);
```
template/default.tpl:
```php
<?php
/**
 * @var \arhone\templating\TemplaterInterface $this
 * @var string $title Название сайта
 */
?>
<html>
    <title><?=$title?></title>
</html>
```

##### Указание нескольких шаблонов

Допустим у вас есть модуль, у него есть шаблон по умолчанию, но пользователь хочет заменить его своим шаблоном.

Что бы сохранить исходный шаблон, вы можете указать путь к дополнительному шаблону, если он существует, то шаблонизатор подключит его, если нет, то подключиться стандартный.

```php
<?php

echo $Templater->render([
    __DIR__ . '/template/extend/myModule/default.tpl', // Новый
    __DIR__ . '/myModule/template/default.tpl' // Стандартный
]); // Подключиться template/extend/myModule/default.tpl если он существует
```

##### Использование общих блоков
Такие переменные доступны во всех шаблонах.

Это позволяет наследовать шаблоны и переопределять их блоки.

###### Установка значения блока

```php
<?php

$Templater->body = 'Содержимое';
$Templater->set('body', 'Содержимое'); // Тоже самое

echo $Templater->body;
```

###### Дописывание содержимого в блок

```php
<?php

$Templater->body .= ' продолжение';
$Templater->add('body', ' продолжение'); // Тоже самое

echo $Templater->body;
```

###### Получение содержимого

```php
<?php

echo $Templater->body;
echo $Templater->get('body'); // Тоже самое
```

###### Удаление содержимого

```php
<?php

$Templater->body = null;
$Templater->delete('body'); // Тоже самое
unset($Templater->body); // Тоже самое
```

###### Установка значения по умолчанию

```php
<?php
/**
 * @var \arhone\templating\TemplatingInterface $this
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
```

Метод default добавляет значение по умолчанию, это значение будет использовано в случае если свойство не было задано. 

```php
<?php

$Templater->body = 'Значение';
$Templater->default('body', 'По умолчанию');
echo $Templater->body; // Выведет "Значение"
```

```php
<?php

$Templater->body = 'Значение';
$Templater->default('body', 'По умолчанию');
unset($Templater->body);
echo $Templater->body; // Выведет "По умолчанию"
```

Таким образом можно переопределять блоки стандартных шаблонов

```php
<?php

echo $Templater->render(__DIR__ . '/slave.tpl');
```

```php
<?php
/**
 * Шаблон slave.tpl
 * @var \arhone\templating\TemplatingInterface $this
 */
?>

<?php $this->set('body')?>
    <div>Содержимое</div>
<?php $this->end('body')?>

<?=$this->render(__DIR__ . '/default.tpl')?>
```

```php
<?php
/**
 * Шаблон default.tpl
 * @var \arhone\templating\TemplatingInterface $this
 */
?>

<?php $this->default('body')?>
    Значение по умолчанию
<?=$this->end('body')?>
```

##### htmlspecialchars() с исключениями

Когда нужно разрешить отрисовывать только некоторые html теги, например p или code

```php
<?php
/**
 * @var \arhone\templating\TemplatingInterface $this
 */
?>

<?php $this->add('body')?>

    <strong>strong</strong>
    <b>b</b>
    <p>p</p>
    <div class="test">div</div>
    <img src="" alt="img">
    <code class="html"><div>div</div></code>

<?=$this->specialChars($this->end('body'), [
    'strong',
    'p',
    'img' => ['src', 'alt'],
    'code' => ['class']
])?>
```

Для всего остального есть PHP :)