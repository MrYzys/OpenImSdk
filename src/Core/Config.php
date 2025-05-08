<?php

namespace OpenImSdk\Core;

class Config
{
    private static $config = [
        'host' => 'http://127.0.0.1:10002',
        'secret' => 'openIM123',
    ];

    /**
     * 设置配置项
     * @param array $config
     * @return void
     */
    public static function setConfig(array $config)
    {
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * 获取密钥
     * @return string
     */
    public static function getSecret(): string
    {
        return self::$config['secret'];
    }

    /**
     * 获取API主机地址
     * @return string
     */
    public static function getHost(): string
    {
        return self::$config['host'];
    }
}
