<?php

namespace OpenImSdk\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use OpenImSdk\Exception\ValidatorException;

class Utils
{
    /**
     * TokenManager实例
     * @var TokenManager|null
     */
    private static $tokenManager = null;
    
    /**
     * 设置TokenManager
     * @param TokenManager $tokenManager
     */
    public static function setTokenManager(TokenManager $tokenManager)
    {
        self::$tokenManager = $tokenManager;
    }
    
    /**
     * 获取TokenManager
     * @return TokenManager
     */
    public static function getTokenManager(): TokenManager
    {
        if (self::$tokenManager === null) {
            // 默认使用文件缓存
            self::$tokenManager = new TokenManager(
                TokenManager::CACHE_TYPE_FILE, 
                null, 
                sys_get_temp_dir() . '/openimsdk_cache'
            );
        }
        
        return self::$tokenManager;
    }
    
    /**
     * 生成操作ID
     * 用于请求追踪
     * @return string
     */
    public static function generateOperationID(): string
    {
        // 生成一个更具唯一性的操作ID
        return uniqid('openim_', true) . '_' . str_replace('.', '', microtime(true));
    }
    
    /**
     * 发起HTTP请求
     * @param string $uri 请求URI
     * @param array $data 请求数据
     * @param string $token 认证令牌
     * @return string 响应内容
     * @throws GuzzleException
     * @throws ValidatorException
     */
    private static function request(string $uri, array $data, string $token): string
    {
        $client = new Client();
        $options[RequestOptions::JSON] = Validator::validateArray($data);

        // 添加必要的请求头
        $options[RequestOptions::HEADERS]['operationID'] = self::generateOperationID();

        if ($token) {
            $options[RequestOptions::HEADERS]['token'] = $token;
        }

        return $client->post($uri, $options)->getBody()->getContents();
    }

    /**
     * 发送API请求
     * @param string $path API路径
     * @param array $data 请求数据
     * @param string $errMsg 错误信息
     * @param string $token 认证令牌
     * @return array 响应数据
     */
    public static function send(string $path, array $data, string $errMsg, string $token = ''): array
    {
        try {
            $url = Url::buildUrl($path);
            return json_decode(self::request($url, $data, $token), true);
        } catch (GuzzleException $e) {
            return ['errCode' => $e->getCode(), 'errMsg' => $errMsg, 'errDlt' => $e->getMessage()];
        } catch (ValidatorException $e) {
            return ['errCode' => 400, 'errMsg' => $errMsg, 'errDlt' => $e->getMessage()];
        }
    }
    
    /**
     * 获取管理员Token
     * 如果缓存中没有，则自动获取并缓存
     * @param string $userID 管理员ID
     * @return string|null
     */
    public static function getAdminToken(string $userID = 'imAdmin'): ?string
    {
        $tokenManager = self::getTokenManager();
        $token = $tokenManager->getAdminToken($userID);
        
        if (!$token) {
            // 从服务器获取新的Token
            $result = self::send(Url::$getAdminToken, [
                'userID' => $userID,
                'secret' => Config::getSecret()
            ], '获取管理员Token失败');
            
            if (isset($result['errCode']) && $result['errCode'] === 0 && isset($result['data']['token'])) {
                $token = $result['data']['token'];
                
                // 使用API返回的过期时间
                $expireTimeSeconds = $result['data']['expireTimeSeconds'] ?? null;
                
                // 保存token，使用API返回的过期时间
                $tokenManager->saveAdminToken($userID, $token, $expireTimeSeconds);
            }
        }
        
        return $token;
    }
    
    /**
     * 获取用户Token
     * 如果缓存中没有，则自动获取并缓存
     * @param string $userID 用户ID
     * @param int $platformID 平台ID
     * @return string|null
     */
    public static function getUserToken(string $userID, int $platformID = 1): ?string
    {
        $tokenManager = self::getTokenManager();
        $token = $tokenManager->getUserToken($userID);
        
        if (!$token) {
            // 从服务器获取新的Token
            $adminToken = self::getAdminToken();
            if (!$adminToken) {
                return null;
            }
            
            $result = self::send(Url::$getUserToken, [
                'userID' => $userID,
                'platformID' => $platformID
            ], '获取用户Token失败', $adminToken);
            
            if (isset($result['errCode']) && $result['errCode'] === 0 && isset($result['data']['token'])) {
                $token = $result['data']['token'];
                
                // 使用API返回的过期时间
                $expireTimeSeconds = $result['data']['expireTimeSeconds'] ?? null;
                
                // 保存token，使用API返回的过期时间
                $tokenManager->saveUserToken($userID, $token, $expireTimeSeconds);
            }
        }
        
        return $token;
    }
    
    /**
     * 清除Token缓存
     * @param string $userID 用户ID
     * @param bool $isAdmin 是否为管理员Token
     * @return bool
     */
    public static function clearToken(string $userID, bool $isAdmin = false): bool
    {
        return self::getTokenManager()->clearToken($userID, $isAdmin);
    }
}
