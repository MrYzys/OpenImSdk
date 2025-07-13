<?php

namespace OpenImSdk\Api;

use OpenImSdk\Core\Url;
use OpenImSdk\Core\Utils;
use OpenImSdk\Core\Config;

class Auth
{
    /**
     * 获取管理员token
     * 直接从服务器获取，不使用缓存
     * @param string $userID 管理员ID，默认为imAdmin
     * @return array
     */
    public function getAdminToken(string $userID = 'imAdmin'): array
    {
        $data = [
            'userID' => $userID,
            'secret' => Config::getSecret()
        ];
        return Utils::send(Url::$getAdminToken, $data, '获取管理员token错误');
    }

    /**
     * 获取用户token
     * 直接从服务器获取，不使用缓存
     * @param string $userID 用户ID
     * @param int $platformID 平台ID，默认为1
     * @return array
     */
    public function getUserToken(string $userID, int $platformID = 1): array
    {
        // 获取管理员token
        $adminToken = Utils::getAdminToken();
        if (!$adminToken) {
            return ['errCode' => 500, 'errMsg' => '获取管理员token失败'];
        }
        
        return Utils::send(Url::$getUserToken, ['userID' => $userID, 'platformID' => $platformID], '获取用户token错误', $adminToken);
    }

    /**
     * 强制登出
     * @param string $userID 要登出的用户ID
     * @param int $platformID 平台ID，默认为1
     * @return array
     */
    public function forceLogout(string $userID, int $platformID = 1): array
    {
        // 获取管理员token
        $adminToken = Utils::getAdminToken();
        if (!$adminToken) {
            return ['errCode' => 500, 'errMsg' => '获取管理员token失败'];
        }
        
        // 清除本地缓存的用户token
        Utils::clearToken($userID);
        
        return Utils::send(Url::$forceLogout, ['userID' => $userID, 'platformID' => $platformID], '强制登出错误', $adminToken);
    }

    /**
     * 解析当前用户token
     * @param string $token 用户token
     * @return array
     */
    public function parseToken(string $token): array
    {
        return Utils::send(Url::$parseToken, [], '解析当前用户token错误', $token);
    }


    /**
     * 用户登录 (旧版，建议使用getUserToken)
     * @param string $userID 用户ID
     * @return array
     */
    public function userToken(string $userID): array
    {
        // 获取管理员token
        $adminToken = Utils::getAdminToken();
        if (!$adminToken) {
            return ['errCode' => 500, 'errMsg' => '获取管理员token失败'];
        }
        
        return Utils::send(Url::$userToken, ['userID' => $userID], '用户登录错误', $adminToken);
    }
}
