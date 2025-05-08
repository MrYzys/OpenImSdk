<?php

namespace OpenImSdk\Core;

use Redis;
use Predis\Client as PredisClient;
use Exception;

class TokenManager
{
    /**
     * 缓存类型：本地文件
     */
    const CACHE_TYPE_FILE = 'file';
    
    /**
     * 缓存类型：Redis
     */
    const CACHE_TYPE_REDIS = 'redis';
    
    /**
     * 缓存类型
     * @var string
     */
    private $cacheType;
    
    /**
     * Redis连接
     * @var Redis|PredisClient|null
     */
    private $redis;
    
    /**
     * 缓存目录
     * @var string
     */
    private $cacheDir;
    
    /**
     * 默认Token过期时间（秒）
     * 仅在API未返回过期时间时使用
     * @var int
     */
    private $defaultTokenExpire = 86400; // 默认24小时
    
    /**
     * 构造函数
     * @param string $cacheType 缓存类型：file或redis
     * @param Redis|PredisClient|null $redis Redis连接（当cacheType为redis时必须）
     * @param string $cacheDir 缓存目录（当cacheType为file时必须）
     */
    public function __construct(string $cacheType = self::CACHE_TYPE_FILE, $redis = null, string $cacheDir = '')
    {
        $this->cacheType = $cacheType;
        
        if ($cacheType === self::CACHE_TYPE_REDIS) {
            if (!$redis) {
                throw new Exception('Redis connection is required when cache type is redis');
            }
            
            if (!($redis instanceof Redis) && !($redis instanceof PredisClient)) {
                throw new Exception('Redis connection must be an instance of Redis or Predis\Client');
            }
            
            $this->redis = $redis;
        } elseif ($cacheType === self::CACHE_TYPE_FILE) {
            if (empty($cacheDir)) {
                $cacheDir = sys_get_temp_dir() . '/openimsdk_cache';
            }
            
            if (!is_dir($cacheDir) && !mkdir($cacheDir, 0755, true)) {
                throw new Exception("Failed to create cache directory: {$cacheDir}");
            }
            
            $this->cacheDir = rtrim($cacheDir, '/');
        } else {
            throw new Exception("Invalid cache type: {$cacheType}");
        }
    }
    
    /**
     * 设置默认Token过期时间
     * @param int $seconds 过期时间（秒）
     * @return $this
     */
    public function setDefaultTokenExpire(int $seconds)
    {
        $this->defaultTokenExpire = $seconds;
        return $this;
    }
    
    /**
     * 获取管理员Token
     * @param string $userID 管理员ID
     * @return string|null
     */
    public function getAdminToken(string $userID = 'imAdmin'): ?string
    {
        $key = "admin_token_{$userID}";
        $tokenData = $this->getCache($key);
        
        if (!$tokenData) {
            // Token不存在或已过期，需要重新获取
            return null;
        }
        
        $data = json_decode($tokenData, true);
        return $data['token'] ?? null;
    }
    
    /**
     * 保存管理员Token
     * @param string $userID 管理员ID
     * @param string $token Token
     * @param int|null $expireTimeSeconds Token过期时间（秒）
     * @return bool
     */
    public function saveAdminToken(string $userID, string $token, ?int $expireTimeSeconds = null): bool
    {
        $key = "admin_token_{$userID}";
        $expireTime = $expireTimeSeconds ?? $this->defaultTokenExpire;
        
        // 存储token和过期时间
        $data = [
            'token' => $token,
            'expireTimeSeconds' => $expireTime
        ];
        
        return $this->setCache($key, json_encode($data), $expireTime);
    }
    
    /**
     * 获取用户Token
     * @param string $userID 用户ID
     * @return string|null
     */
    public function getUserToken(string $userID): ?string
    {
        $key = "user_token_{$userID}";
        $tokenData = $this->getCache($key);
        
        if (!$tokenData) {
            // Token不存在或已过期，需要重新获取
            return null;
        }
        
        $data = json_decode($tokenData, true);
        return $data['token'] ?? null;
    }
    
    /**
     * 保存用户Token
     * @param string $userID 用户ID
     * @param string $token Token
     * @param int|null $expireTimeSeconds Token过期时间（秒）
     * @return bool
     */
    public function saveUserToken(string $userID, string $token, ?int $expireTimeSeconds = null): bool
    {
        $key = "user_token_{$userID}";
        $expireTime = $expireTimeSeconds ?? $this->defaultTokenExpire;
        
        // 存储token和过期时间
        $data = [
            'token' => $token,
            'expireTimeSeconds' => $expireTime
        ];
        
        return $this->setCache($key, json_encode($data), $expireTime);
    }
    
    /**
     * 清除Token
     * @param string $userID 用户ID
     * @param bool $isAdmin 是否为管理员Token
     * @return bool
     */
    public function clearToken(string $userID, bool $isAdmin = false): bool
    {
        $key = $isAdmin ? "admin_token_{$userID}" : "user_token_{$userID}";
        return $this->deleteCache($key);
    }
    
    /**
     * 获取缓存
     * @param string $key 缓存键
     * @return string|null
     */
    private function getCache(string $key): ?string
    {
        if ($this->cacheType === self::CACHE_TYPE_REDIS) {
            return $this->getRedisCache($key);
        } else {
            return $this->getFileCache($key);
        }
    }
    
    /**
     * 设置缓存
     * @param string $key 缓存键
     * @param string $value 缓存值
     * @param int $expire 过期时间（秒）
     * @return bool
     */
    private function setCache(string $key, string $value, int $expire): bool
    {
        if ($this->cacheType === self::CACHE_TYPE_REDIS) {
            return $this->setRedisCache($key, $value, $expire);
        } else {
            return $this->setFileCache($key, $value, $expire);
        }
    }
    
    /**
     * 删除缓存
     * @param string $key 缓存键
     * @return bool
     */
    private function deleteCache(string $key): bool
    {
        if ($this->cacheType === self::CACHE_TYPE_REDIS) {
            return $this->deleteRedisCache($key);
        } else {
            return $this->deleteFileCache($key);
        }
    }
    
    /**
     * 获取Redis缓存
     * @param string $key 缓存键
     * @return string|null
     */
    private function getRedisCache(string $key): ?string
    {
        $value = $this->redis->get($key);
        return $value !== false ? $value : null;
    }
    
    /**
     * 设置Redis缓存
     * @param string $key 缓存键
     * @param string $value 缓存值
     * @param int $expire 过期时间（秒）
     * @return bool
     */
    private function setRedisCache(string $key, string $value, int $expire): bool
    {
        if ($this->redis instanceof Redis) {
            return $this->redis->setex($key, $expire, $value);
        } else {
            // Predis
            return (bool)$this->redis->setex($key, $expire, $value);
        }
    }
    
    /**
     * 删除Redis缓存
     * @param string $key 缓存键
     * @return bool
     */
    private function deleteRedisCache(string $key): bool
    {
        return (bool)$this->redis->del($key);
    }
    
    /**
     * 获取文件缓存
     * @param string $key 缓存键
     * @return string|null
     */
    private function getFileCache(string $key): ?string
    {
        $file = $this->getCacheFile($key);
        
        if (!file_exists($file)) {
            return null;
        }
        
        $content = file_get_contents($file);
        if ($content === false) {
            return null;
        }
        
        $data = json_decode($content, true);
        if (!$data || !isset($data['value']) || !isset($data['expire'])) {
            return null;
        }
        
        // 检查是否过期
        if ($data['expire'] < time()) {
            $this->deleteFileCache($key);
            return null;
        }
        
        return $data['value'];
    }
    
    /**
     * 设置文件缓存
     * @param string $key 缓存键
     * @param string $value 缓存值
     * @param int $expire 过期时间（秒）
     * @return bool
     */
    private function setFileCache(string $key, string $value, int $expire): bool
    {
        $file = $this->getCacheFile($key);
        $data = [
            'value' => $value,
            'expire' => time() + $expire
        ];
        
        return file_put_contents($file, json_encode($data)) !== false;
    }
    
    /**
     * 删除文件缓存
     * @param string $key 缓存键
     * @return bool
     */
    private function deleteFileCache(string $key): bool
    {
        $file = $this->getCacheFile($key);
        
        if (file_exists($file)) {
            return unlink($file);
        }
        
        return true;
    }
    
    /**
     * 获取缓存文件路径
     * @param string $key 缓存键
     * @return string
     */
    private function getCacheFile(string $key): string
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
}
