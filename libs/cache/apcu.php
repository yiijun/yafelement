<?php
/**
 * User: tangyijun
 * Date: 2019-09-29
 * Time: 14:53
 */
namespace libs\cache;
use libs\instance;

class Apcu extends Instance
{
    public  function get( $key ) {
        if (!function_exists('apcu_fetch')) return;
        $data = apcu_fetch( $key );
        return $data;
    }

    /**
     * @param $key
     * @param $data
     * @param int $ttl 有效期，默认永久
     * @return array|bool|void
     */
    public  function set( $key, $data, $ttl = 0 ) {
        if (!function_exists('apcu_store')) return;
        return apcu_store( $key, $data, $ttl );
    }

    /**
     * @return bool|void
     */
    public  function reset(){
        if (!function_exists('apcu_clear_cache')) return;
        return apcu_clear_cache();
    }

    /**
     * @param $key
     * @return bool|string[]|void
     */
    public  function delete($key) {
        if (!function_exists('apcu_delete')) return;
        return apcu_delete($key);
    }
}