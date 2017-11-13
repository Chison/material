<?php
/**
 * @author: Chison 17-11-12 下午6:54
 * @link: http://old.shexiangsheng.cn
 * @link: https://github.com/chison
 * @email: 809896630@qq.com
 */

namespace task;

use Generator;

class Task
{
    public $taskId;
    public $coroutine;
    public $isFristRun = true;
    public $sendValue = null;

    /**
     * Task constructor.
     * @param Int $taskId
     * @param Generator $coroutine
     */
    public function __construct(Int $taskId , Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    /**
     * 获取任务ID
     * @return Int
     */
    public function getTaskId(){
        return $this->taskId;
    }

    /**
     * @param $value
     */
    public function setSendValue($value){
        $this->sendValue = $value;
    }

    /**
     * 运行
     * @return mixed
     */
    public function run(){
        if($this->isFristRun){
            $this->isFristRun = false;
            return $this->coroutine->current();
        }else{
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    /**
     * @return bool
     */
    public function isFinished() {
        return !$this->coroutine->valid();
    }
}