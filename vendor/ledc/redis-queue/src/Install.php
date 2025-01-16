<?php

namespace Ledc\RedisQueue;

/**
 * 安装脚本
 */
class Install
{
    const WEBMAN_PLUGIN = true;

    /**
     * @var array
     */
    protected static array $pathRelation = array(
        'config/plugin/ledc/redis-queue' => 'config/plugin/ledc/redis-queue',
        'config/JobsConsumer.stub' => 'app/queue/redis/JobsConsumer.php',
        'config/redis_queue.php' => 'config/redis_queue.php',
    );

    /**
     * install
     * @return void
     */
    public static function install(): void
    {
        static::installByRelation();
    }

    /**
     * Uninstall
     * @return void
     */
    public static function uninstall(): void
    {
        self::uninstallByRelation();
    }

    /**
     * installByRelation
     * @return void
     */
    public static function installByRelation(): void
    {
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path() . '/' . substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }
            //symlink(__DIR__ . "/$source", base_path()."/$dest");
            copy_dir(__DIR__ . "/$source", base_path() . "/$dest");
            echo "Create {$dest}" . PHP_EOL;
        }
    }

    /**
     * uninstallByRelation
     * @return void
     */
    public static function uninstallByRelation(): void
    {
        foreach (static::$pathRelation as $source => $dest) {
            $path = base_path() . "/$dest";
            if (!is_dir($path) && !is_file($path)) {
                continue;
            }
            echo "Remove {$dest}" . PHP_EOL;
            if (is_file($path) || is_link($path)) {
                unlink($path);
                continue;
            }
            remove_dir($path);
        }
    }
}