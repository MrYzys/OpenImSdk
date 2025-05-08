<?php

namespace OpenImSdk;

use OpenImSdk\Api\Auth;
use OpenImSdk\Api\Conversation;
use OpenImSdk\Api\Friend;
use OpenImSdk\Api\Group;
use OpenImSdk\Api\Message;
use OpenImSdk\Api\User;
use OpenImSdk\Core\Config;
use OpenImSdk\Core\TokenManager;
use OpenImSdk\Core\Utils;
use Redis;
use Predis\Client as PredisClient;

class Client
{
    /**
     * 认证相关
     * @var Auth
     */
    public $auth;

    /**
     * 好友相关
     * @var Friend
     */
    public $friend;

    /**
     * 群组相关
     * @var Group
     */
    public $group;

    /**
     * 消息相关
     * @var Message
     */
    public $message;

    /**
     * 用户相关
     * @var User
     */
    public $user;

    /**
     * 会话相关
     * @var Conversation
     */
    public $conversation;

    /**
     * 初始化客户端
     * @param array $config 配置信息
     * @param Redis|PredisClient|null $redis Redis连接（可选）
     * @param string $cacheDir 缓存目录（当不使用Redis时）
     */
    public function __construct(array $config, $redis = null, string $cacheDir = '')
    {
        // 设置基本配置
        Config::setConfig($config);
        
        // 初始化TokenManager
        if ($redis !== null) {
            // 使用Redis缓存
            $tokenManager = new TokenManager(TokenManager::CACHE_TYPE_REDIS, $redis);
        } else {
            // 使用文件缓存
            $tokenManager = new TokenManager(TokenManager::CACHE_TYPE_FILE, null, $cacheDir ?: sys_get_temp_dir() . '/openimsdk_cache');
        }
        
        // 设置TokenManager
        Utils::setTokenManager($tokenManager);

        // 初始化API类
        $this->auth = new Auth();
        $this->friend = new Friend();
        $this->group = new Group();
        $this->message = new Message();
        $this->user = new User();
        $this->conversation = new Conversation();
    }
}
