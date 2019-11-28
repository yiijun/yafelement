<?php
/**
 * User: tangyijun
 * Date: 2019-02-13
 * Time: 16:34
 */
namespace Libs\Cache;
class Cache
{
    /**
     * @param string $name
     * @param string $key
     * @return static
     */
    public static function getInstance($name = 'default', $key = 'default')
    {
        $class = get_called_class();
        $instance = &self::statics($class);
        if (isset($instance[$name][$key])) {
            return $instance[$name][$key];
        }
        return $instance[$name][$key] = new $class($name);
    }

    private static function &statics($name, $defaultValue = null, $reset = false)
    {
        static $data = [], $default = [];
        if (isset($data[$name]) || array_key_exists($name, $data)) {
            if ($reset) {
                $data[$name] = $default[$name];
            }
            return $data[$name];
        }
        if (isset($name)) {
            if ($reset) {
                return $data;
            }
            $default[$name] = $data[$name] = $defaultValue;
            return $data[$name];
        }
        foreach ($default as $name => $value) {
            $data[$name] = $value;
        }
        return $data;
    }
}