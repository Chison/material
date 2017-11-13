<?php
/**
 * @author: Chison 17-11-13 上午9:47
 * @link: http://old.shexiangsheng.cn
 * @link: https://github.com/chison
 * @email: 809896630@qq.com
 */

namespace task;

use SplQueue;
use Generator;

/**
 * Class Scheduler 任务调度器
 * @package task
 */
class Scheduler
{
    public $taskQueue; //任务队列
    public $taskMap = []; // taskId => Generator Object
    public $maxTaskId = 0; //当前任务Id

    public function __construct()
    {
        $this->taskQueue = new SplQueue();
    }

    /**
     * 添加任务
     * @param Generator $coroutine
     */
    public function addTask(Generator $coroutine){
        $tid = ++$this->maxTaskId;
        $task = new Task($tid , $coroutine);

        //设置
        $task->sendValue = uniqid();

        $this->taskMap[$tid] = $task;
        $this->schedule($task);
    }

    public function schedule($task){
        $this->taskQueue->enqueue($task);
    }

    public function run(){
        while (!$this->taskQueue->isEmpty()){
            $task = $this->taskQueue->dequeue();
            $task->run();

            if($task->isFinished()){
                unset($this->taskMap[$task->getTaskId()]);
            }else{
                $this->schedule($task);
            }
        }
    }
}