# Template
Нативный шаблонизатор (PHP 7)

Шаблонизатор предназначен для удобного разделения бизнес логики и логики представления.

Позволяет подключать файлы шаблонов и передавать в них готовые данные.

# Установка

```composer require arhone/template```

```php
<?php
use arhone\template\Template;
include 'vendor/autoload.php';

$Template = new Template();
```

# Примеры

##### Рендер шаблона

```php
<?php
use arhone\template\Template;
include 'vendor/autoload.php';

$Template = new Template();

echo $Template->render(__DIR__ . '/template/default.tpl', [
    'title' => 'Мой сайт'
]);
```
template/default.tpl:
```php
<?php
/**
 * @var \arhone\template\TemplateInterface $this
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
use arhone\template\Template;
include 'vendor/autoload.php';

$Template = new Template();

echo $Template->render([
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
$Template->body = 'Содержимое';
$Template->set('body', 'Содержимое'); // Тоже самое

echo $Template->body;
```

###### Дописывание содержимого в блок

```php
<?php

$Template->body .= ' продолжение';
$Template->add('body', ' продолжение'); // Тоже самое

echo $Template->body;
```

###### Получение содержимого

```php
<?php

echo $Template->body;
echo $Template->get('body'); // Тоже самое
```

###### Удаление содержимого

```php
<?php

$Template->body = null;
$Template->delete('body'); // Тоже самое
unset($Template->body); // Тоже самое
```

###### Установка значения по умолчанию

```php
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
```

Метод default добавляет значение по умолчанию, это значение будет использовано в случае если свойство не было задано. 

```php
<?php

$Template->body = 'Значение';
$Template->default('body', 'По умолчанию');
echo $Template->body; // Выведет "Значение"
```

```php
<?php

$Template->body = 'Значение';
$Template->default('body', 'По умолчанию');
unset($Template->body);
echo $Template->body; // Выведет "По умолчанию"
```

Таким образом можно переопределять блоки стандартных шаблонов

```php
<?php
use arhone\template\Template;
include 'vendor/autoload.php';

$Template = new Template();

echo $Template->render(__DIR__ . '/slave.tpl');
```

```php
<?php
/**
 * Шаблон slave.tpl
 * @var \arhone\template\TemplateInterface $this
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
 * @var \arhone\template\TemplateInterface $this
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
 * @var \arhone\template\TemplateInterface $this
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