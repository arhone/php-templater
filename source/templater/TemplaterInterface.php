<?php declare(strict_types = 1);

namespace arhone\templating\templater;

/**
 * Шаблонизатор
 *
 * Interface TemplaterInterface
 * @package arhone\templating\templater
 * @author Алексей Арх <info@arh.one>
 */
interface TemplaterInterface {

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
     * Включение буферизации вывода
     *
     * @param string $name
     * @param mixed|null $value
     * @return void
     */
    public function set (string $name, $value = null) : void;

    /**
     * Устанавливает значение для переменной по умолчанию
     * Включение буферизации вывода
     *
     * @param string $name
     * @param mixed|null $value
     * @return void
     */
    public function default (string $name, $value = null) : void;

    /**
     * Дописывает значение в переменную
     * Включение буферизации вывода
     *
     * @param string $name
     * @param mixed|null $value
     * @return void
     */
    public function add (string $name, $value = null) : void;

    /**
     * Получить содержимое текущего буфера и удалить его
     *
     * @param string $name
     * @return mixed
     */
    public function end (string $name);

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
     * Удаляет переменную
     *
     * @param string $name
     * @return void
     */
    public function delete (string $name) : void;

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
