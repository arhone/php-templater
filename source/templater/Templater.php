<?php declare(strict_types = 1);

namespace arhone\templating\templater;

/**
 * Шаблонизатор
 *
 * Class Templater
 * @package arhone\templating\templater
 * @author Алексей Арх <info@arh.one>
 */
class Templater implements TemplaterInterface {

    /**
     * Настройки класса
     * 
     * @var array
     */
    protected $config = [];
    
    /**
     * Хранилище блоков
     *
     * @var array
     */
    protected static $storage = [];

    /**
     * Значения по умолчанию
     *
     * @var array
     */
    protected static $default = [];

    /**
     * Путь к шаблону
     *
     * @var array
     */
    protected $path = null;

    /**
     * Статус позиции
     *
     * @var null
     */
    protected static $obPosition = null;

    /**
     * Template constructor.
     *
     * Template constructor.
     * @param array $config
     */
    function __construct (array $config = []) {

        $this->config($config);

    }

    /**
     * Возвращает загруженный шаблон
     *
     * @param null|string|array $path
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function render ($path, array $data = []) : string {

        if ($this->path) {
            $this->load($path);
        }

        ob_start();
        extract($data);
        include $this->$path;
        return ob_get_clean();

    }

    /**
     * Получение пути к файлу шаблона
     *
     * @param string|array $path
     * @return $this
     * @throws \Exception
     */
    public function load ($path) {

        $pathList = [];
        if (is_string($path)) {
            $pathList = [$path];
        } elseif (is_array($path)) {
            $pathList = $path;
        }
        
        foreach ($pathList as $path) {

            if (is_file($path)) {

                $this->path = $path;
                return $this;

            }
            
        }

        throw new \Exception('Template: Запрашиваемый шаблон ' . implode(',', $pathList) . ' не существует');

    }

    /**
     * Устанавливает значение для переменной
     * Включение буферизации вывода
     *
     * @param string $name
     * @param mixed|null $value
     * @return void
     */
    public function set (string $name, $value = null) : void {

        if ($value) {

            self::$storage[$name] = $value;

        } else {

            self::$obPosition = 'set';
            ob_start();

        }

    }

    /**
     * Устанавливает значение для переменной
     * Включение буферизации вывода
     *
     * @param string $name
     * @param mixed|null $value
     * @return void
     */
    public function default (string $name, $value = null) : void {

        if ($value) {

            self::$default[$name] = $value;

        } else {

            self::$obPosition = 'default';
            ob_start();

        }

    }

    /**
     * Дописывает значение в переменную
     * Включение буферизации вывода
     *
     * @param string $name
     * @param mixed|null $value
     * @return void
     */
    public function add (string $name, $value = null) : void {

        if ($value) {

            self::$storage[$name] = isset(self::$storage[$name]) ? self::$storage[$name] . $value : $value;

        } else {

            self::$obPosition = 'add';
            ob_start();

        }

    }

    /**
     * Получить содержимое текущего буфера и удалить его
     *
     * @param string $name
     * @return mixed
     */
    public function end (string $name) {

        $value = ob_get_clean();

        if (self::$obPosition == 'add') {

            self::$storage[$name] = isset(self::$storage[$name]) ? self::$storage[$name] . $value : $value;
            self::$obPosition = null;

        } elseif (self::$obPosition == 'set') {

            self::$storage[$name] = $value;
            self::$obPosition = null;

        } else {

            self::$default[$name] = $value;
            self::$obPosition = null;

        }

        return $this->get($name);

    }

    /**
     * Возвращает значение переменной
     *
     * @param string $name
     * @return mixed
     */
    public function get (string $name) {

        return self::$storage[$name] ?? self::$default[$name] ?? null;

    }

    /**
     * Проверяет существование переменной
     *
     * @param string $name
     * @return bool
     */
    public function has (string $name) : bool {
        
        return isset(self::$storage[$name]);
        
    }

    /**
     * Удаляет переменную
     *
     * @param string $name
     * @return void
     */
    public function delete (string $name) : void {

        unset(self::$storage[$name]);

    }
    
    /**
     * Возвращает значение переменной
     * 
     * @param string $name
     * @return mixed|null
     */
    public function __get (string $name) {

        return $this->get($name);
        
    }

    /**
     * Устанавливает значение переменной
     *
     * @param string $name
     * @param $value
     */
    public function __set (string $name, $value) {

        $this->set($name, $value);
        
    }

    /**
     * Проверяет на существование
     * 
     * @param string $name
     * @return mixed
     */
    public function __isset (string $name) {
        
        return $this->has($name);
        
    }

    /**
     * Удаляет переменную
     *
     * @param string $name
     * @return mixed
     */
    public function __unset (string $name) {

        unset(self::$storage[$name]);

    }

    /**
     * htmlspecialchars() с исключениями
     * 
     * @param string $text
     * @param array $tagList
     * @return string
     */
    public function specialChars (string $text, array $tagList = []) : string {
        
        if (isset($tagList['code']) || array_search('code', $tagList) !== false) {
            
            preg_match_all('!<code[ a-zA-Zа-яА-Я0-9./"=:_@%?\-&#;]*>(.*?)</code>!isU', $text, $code);
            if (!empty($code[0])) {
                
                foreach ($code[1] as $key => $v) {
                    $code[2][$key] = htmlspecialchars($v);
                    $code[3][$key] = '{{{code-' . $key . '}}}';
                }
                $text = str_replace($code[1], $code[3], $text);
                
            }
            
        }
        
        if (!empty($tagList)) {
            
            $pattern1     = [];
            $pattern2     = [];
            $replacement1 = [];
            $replacement2 = [];
            
            foreach ($tagList as $key => $value) {
                $attr = is_array($value) ? '(([ ]+[' . implode('|', $value) . ']*[ ]?=[ ]?"[ a-zA-Zа-яА-Я0-9./:_@%?\-&#;]*")*)?' : '([ ])?';
                $tag = is_array($value) ? $key : $value;
                $pattern1[] = '$<([/])?' . $tag . $attr . '(.*?)?>$iu';
                $pattern2[] = '$\[([/])?' . $tag . $attr . '\]$iu';
                $replacement1[] = '[$1' . $tag . '$2]';
                $replacement2[] = '<$1' . $tag . '$2>';
            }
            
            $text = preg_replace($pattern1, $replacement1, $text);
            $text = htmlspecialchars($text, ENT_NOQUOTES);
            $text = preg_replace($pattern2, $replacement2, $text);
            
            if (!empty($code[0])) {
                $text = str_replace($code[3], $code[2], $text);
            }
            
            return $text;
            
        }
            
        return htmlspecialchars($text);

    }

    /**
     * Очистить от комментарий
     * 
     * @param string $text
     * @return string
     */
    public function clearComment (string $text) : string {
        
        return preg_replace('/<!--[^\[].*-->/Uis', '', $text);
        
    }

    /**
     * Очистить от переноса строк
     * 
     * @param string $text
     * @return string
     */
    public function clearRN (string $text) : string {
        
        return preg_replace('/\s+/', ' ', $text);
        
    }
    
    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) : array {

        return $this->config = array_merge($this->config, $config);

    }

}
