<?php

namespace Ledc\RedisQueue\Command;

use Webman\RedisQueue\Command\MakeConsumerCommand;

/**
 * Make队列消费者
 */
class MakeConsumer extends MakeConsumerCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'make:queue-consumer';

    /**
     * @param string $namespace
     * @param string $class
     * @param string $queue
     * @param string $file
     * @return void
     */
    protected function createConsumer($namespace, $class, $queue, $file): void
    {
        $path = pathinfo($file, PATHINFO_DIRNAME);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $controller_content = <<<EOF
<?php

namespace $namespace;

use Ledc\\RedisQueue\\HasHelper;
use Webman\\RedisQueue\\Consumer;

/**
 * 队列消费者 $class
 */
class $class implements Consumer
{
    use HasHelper;
    
    /**
     * 要消费的队列名
     * @var string
     */
    public string \$queue = '$queue';
    /**
     * 连接名
     * - 对应 config/redis-queue.php 里的连接
     * - 对应 plugin/webman/redis-queue/redis.php 里的连接
     * @return string
     */
    public string \$connection = 'default';

    /**
     * 消费方法
     * - 消费过程中没有抛出异常和Error视为消费成功；否则消费失败,进入重试队列
     * @param mixed \$data 数据
     */
    public function consume(\$data)
    {
        // 无需反序列化
        var_export(\$data);
    }
}

EOF;
        file_put_contents($file, $controller_content);
    }

}
