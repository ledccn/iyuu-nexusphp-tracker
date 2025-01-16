<?php

namespace Ledc\Push;

/**
 * 安装脚本
 */
class Install
{
    const WEBMAN_PLUGIN = true;

    /**
     * @var array
     */
    protected static array $pathRelation = [
        'config/plugin/ledc/push' => 'config/plugin/ledc/push'
    ];

    /**
     * Install
     * @return void
     */
    public static function install(): void
    {
        $config_app_path = __DIR__ . '/config/plugin/ledc/push/app.php';
        $config_app_content = file_get_contents($config_app_path);
        $app_key = md5(microtime(true) . rand(0, 2100000000));
        $app_secret = md5($app_key . rand(0, 2100000000));
        $server_listen_port = parse_url(config('server.listen', 'http://0.0.0.0:8787'), PHP_URL_PORT);
        $config_app_content = str_replace([
            'APP_KEY_TO_REPLACE',
            'APP_SECRET_TO_REPLACE',
            ':8787'
        ], [$app_key, $app_secret, ':' . $server_listen_port], $config_app_content);
        file_put_contents($config_app_path, $config_app_content);
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
            /*if (is_link($path) {
                unlink($path);
            }*/
            remove_dir($path);
        }
    }
}
