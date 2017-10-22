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

        throw new \Exception('Template: Запрашиваемый шаблон ' . implode(',', $pathList) . ' не существует');

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
    public function __isset (string $name) {
        
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
