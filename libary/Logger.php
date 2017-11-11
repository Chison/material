<?php
/**
 * @author: Chison 17-11-11 下午5:17
 * @link: http://old.shexiangsheng.cn
 * @link: https://github.com/chison
 * @email: 809896630@qq.com
 * 日志类
 */

namespace Chison;

class Logger
{
    public static $path = './';

    /**
     * 写入日志
     * @param $content array | string
     */
    public static function write($content){
        $insert = "\r\n";
        if(is_array($content)){
            foreach ($content as $k=>$v){
                if(is_array($v)){
                    $insert .= $k . " : " . json_encode($v) . "\r\n";
                }else{
                    $insert .= $k . " : " . $v . "\r\n";
                }
            }
        }else{
            $insert .= $content . "\r\n";
        }
        self::AssociateLog()->send($insert);
    }

    /**
     * 协程 ?
     */
    protected static function AssociateLog()
    {
        $file = fopen(self::getFile(),'a');
        while (true){
            fwrite($file , yield );
        }
    }

    /**
     * @param string $pre
     * @return string
     */
    protected static function getFile($pre = "cis"){
        return self::$path . date("Ymd") . '.log';
    }
}

Logger::write(sha1( uniqid()));