<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\queue\db;

use yii\helpers\Console;
use yii\queue\cli\Command as CliCommand;

/**
 * Manages application db-queue.
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class Command extends CliCommand
{
    /**
     * @var Queue
     */
    public $queue;

    /**
     * @var string
     */
    public $defaultAction = 'info';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'info' => InfoAction::class,
        ];
    }

    /**
     * Runs all jobs from db-queue.
     * It can be used as cron job.
     */
    public function actionRun()
    {
        $this->queue->run();
    }

    /**
     * Listens db-queue and runs new jobs.
     * It can be used as demon process.
     *
     * @param integer $delay Number of seconds for waiting new job.
     */
    public function actionListen($delay = 3)
    {
        $this->queue->listen($delay);
    }

    /**
     * Clears the queue.
     */
    public function actionClear()
    {
        if ($this->confirm('Are you sure?')) {
            $this->queue->clear();
            Console::output('Queue has been cleared.');
        }
    }

    /**
     * Removes a job by id.
     *
     * @param int $id
     * @return int exit code
     */
    public function actionRemove($id)
    {
        if ($this->queue->remove($id)) {
            Console::output('The job has been removed.');
            return static::EXIT_CODE_NORMAL;
        } else {
            Console::output('The job is not found.');
            return static::EXIT_CODE_ERROR;
        }
    }
}