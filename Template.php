<?php declare(strict_types = 1);

namespace arhone\template;

/**
 * Шаблонизатор
 *
 * Class Template
 * @package arhone\template
 */
class Template implements TemplateInterface {

    /**
     * Настройки класса
     * 
     * @var array
     */
    protected $config = [];
    
    /**
     * Блоки
     *
     * @var array
     */
    protected static $block = [];

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
     * @param mixed $path Путь к файлу шаблона или массив с путями
     * @param array $data Массив с переменными
     * @return string
     */
    public function render ($path, array $data = []) : string {

        ob_start();
            extract($data);
            include $this->getPath($path);
        return ob_get_clean();

    }

    /**
     * Получение пути к файлу шаблона
     *
     * @param string|array $path
     * @return string
     * @throws \Exception
     */
    protected function getPath ($path) : string {

        $pathList = [];
        if (is_string($path)) {
            $pathList = [$path];
        } elseif (is_array($path)) {
            $pathList = $path;
        }
        
        foreach ($pathList as $path) {

            if (is_file($path)) {

                return $path;

            }
            
        }

        throw new \Exception('Tpl: Запрашиваемый шаблон ' . implode(',', $pathList) . ' не существует');

    }

    /**
     * Устанавливает значение для переменной
     *
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function set (string $name, $value) {

        self::$block[$name] = $value;

    }

    /**
     * Дописывает значение в переменную
     *
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function add (string $name, $value) {

        self::$block[$name] = self::$block[$name] ? self::$block[$name] . $value : $value;

    }

    /**
     * Возвращает значение переменной
     *
     * @param string $name
     * @return mixed
     */
    public function get (string $name) {

        return self::$block[$name] ?? null;

    }

    /**
     * Проверяет существование переменной
     *
     * @param string $name
     * @return mixed
     */
    public function has (string $name) {
        
        return self::$block[$name];
        
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
    public function __isset(string $name) {
        
        return $this->has($name);
        
    }

    /**
     * Включение буферизации вывода
     *
     * @param string $name
     * @return mixed
     */
    public function start (string $name) {
        
        ob_start();
        
    }

    /**
     * Получить содержимое текущего буфера и удалить его
     *
     * @param string $name
     * @return mixed
     */
    public function end (string $name) {
        
        $value = ob_get_clean();
        return self::$block[$name] = self::$block[$name] ?? $value;
        
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
