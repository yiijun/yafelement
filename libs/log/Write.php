<?php
/**
 * User: tangyijun
 * Date: 2019-11-28
 * Time: 15:06
 */
namespace Libs\Log;
use Libs\Instance;

class Write extends Instance{

    /**
     * @param $data
     * @param string $type
     * @param string $file_name
     * @param bool $cut_by_dt
     * @param bool $show_timestamp
     * @param string $sep
     * @return bool|int
     */
    public function info($data, $type = '' , $file_name='', $cut_by_dt = false, $show_timestamp = true, $sep = "\t" ) {

        $now        = time();
        $date       = date('Y-m-d H:i:s', $now);
        $result     = [$date];
        if ($show_timestamp) $result[] = $now;
        if (!empty($type)) $result[] = $type;
        foreach ($data as $item) {
            $result[] = str_replace("\t", '', $item);
        }
        $txt         = implode($sep, $result ) . PHP_EOL;
        $file_output = !empty( $cut_by_dt ) ?  ( $file_name . '_' . date('Y-m-d') . '.log' ) : ( $file_name . '.log' );
        $path = \Yaf\Application::app()->getConfig()->log['path'].SITE_NAME;
        if (!is_dir( $path)) mkdir( $path,  0755, true);
        $ret = file_put_contents( $path. $file_output, $txt , FILE_APPEND );
        return $ret;
    }

    /**
     * @param $msg
     * @param string $file_name
     * @param bool $cut_by_dt
     */
    public function error($msg,$file_name = '',$cut_by_dt = false)
    {
        $debugInfo = debug_backtrace();
        $stack = "[";
        foreach($debugInfo as $key => $val){
            if(array_key_exists("file", $val)){
                $stack .= ",file:" . $val["file"];
            }
            if(array_key_exists("line", $val)){
                $stack .= ",line:" . $val["line"];
            }
            if(array_key_exists("function", $val)){
                $stack .= ",function:" . $val["function"];
            }
        }
        $stack .= "]";
        $this->write([$stack.$msg],8,$file_name,$cut_by_dt);
    }
}