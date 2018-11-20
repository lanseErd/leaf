<?php
/**
 * Debug
 * Date: 2018/11/18
 * Time: 15:33
 */

namespace Leaf;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
class Debug extends AbstractProcessingHandler{


    /**
     * @param resource|string $stream
     */
    public function __construct()
    {
        parent::__construct(Logger::DEBUG, true);

    }

    /**
     * 重写write方法
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        echo $record['formatted'];

    }

}