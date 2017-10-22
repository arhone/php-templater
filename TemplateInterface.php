<?php declare(strict_types = 1);

namespace arhone\template;

/**
 * Шаблонизатор
 *
 * Class TemplateInterface
 * @package arhone\template
 */
interface TemplateInterface {

    /**
     * Template constructor.
     *
     * @param array $config
     */
    function __construct (array $config = []);

    /**
     * Возвращает загруженный шаблон
     *
     * @param mixed $path Путь к файлу шаблона или массив с путями
     * @param array $data Массив с переменными
     * @return string
     */
    public function render ($path, array $data = []) : string;

    /**
     * Устанавливает значение для переменной
     * 
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function set (string $name, $value);

    /**
     * Дописывает значение в переменную
     * 
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function add (string $name, $value);

    /**
     * Возвращает значение переменной
     * 
     * @param string $name
     * @return mixed
     */
    public function get (string $name);

    /**
     * Проверяет существование переменной
     * 
     * @param string $name
     * @return mixed
     */
    public function has (string $name);
    
    /**
     * Включение буферизации вывода
     *
     * @param string $name
     * @return mixed
     */
    public function start (string $name);

    /**
     * Получить содержимое текущего буфера и удалить его
     * 
     * @param string $name
     * @return mixed
     */
    public function end (string $name);

    /**
     * htmlspecialchars() с исключениями
     *
     * @param string $text
     * @param array $tagList
     * @return string
     */
    public function specialChars (string $text, array $tagList = []) : string;

    /**
     * Очистить от комментарий
     *
     * @param string $text
     * @return string
     */
    public function clearComment (string $text) : string;

    /**
     * Очистить от переноса строк
     *
     * @param string $text
     * @return string
     */
    public function clearRN (string $text) : string;

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) : array;

}
