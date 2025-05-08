<?php

namespace OpenImSdk\Api;

use OpenImSdk\Core\Url;
use OpenImSdk\Core\Utils;

class User
{
    /**
     * 获取用户列表
     * @param string $token 管理员token
     * @param int $pagination 页码
     * @param int $showNumber 每页数量
     * @return array
     */
    public function getUsers(string $token, int $pagination = 1, int $showNumber = 20): array
    {
        $data = [
            'pagination' => [
                'pageNumber' => $pagination,
                'showNumber' => $showNumber
            ]
        ];
        return Utils::send(Url::$getUsers, $data, '获取用户列表错误', $token);
    }

    /**
     * 获取用户在线状态
     * @param string $token 管理员token
     * @param array $userIDList 用户ID列表
     * @return array
     */
    public function getUsersOnlineStatus(string $token, array $userIDList): array
    {
        return Utils::send(Url::$getUsersOnlineStatus, ['userIDList' => $userIDList], '获取用户在线状态错误', $token);
    }

    /**
     * 获取用户在线token详情
     * @param string $token 管理员token
     * @param array $userIDList 用户ID列表
     * @return array
     */
    public function getUsersOnlineTokenDetail(string $token, array $userIDList): array
    {
        return Utils::send(Url::$getUsersOnlineTokenDetail, ['userIDList' => $userIDList], '获取用户在线token详情错误', $token);
    }

    /**
     * 获取订阅用户状态
     * @param string $token 管理员token
     * @return array
     */
    public function getSubscribeUsersStatus(string $token): array
    {
        return Utils::send(Url::$getSubscribeUsersStatus, [], '获取订阅用户状态错误', $token);
    }

    /**
     * 订阅用户状态
     * @param string $token 管理员token
     * @param array $userIDList 用户ID列表
     * @return array
     */
    public function subscribeUsersStatus(string $token, array $userIDList): array
    {
        return Utils::send(Url::$subscribeUsersStatus, ['userIDList' => $userIDList], '订阅用户状态错误', $token);
    }

    /**
     * 设置全局免打扰
     * @param string $token 管理员token
     * @param int $globalRecvMsgOpt 全局消息接收选项
     * @return array
     */
    public function setGlobalMsgRecvOpt(string $token, int $globalRecvMsgOpt): array
    {
        return Utils::send(Url::$setGlobalMsgRecvOpt, ['globalRecvMsgOpt' => $globalRecvMsgOpt], '设置全局免打扰错误', $token);
    }

    /**
     * 修改用户信息
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @param array $data 用户信息
     * @return array
     */
    public function updateUserInfo(string $token, string $userID, array $data): array
    {
        $data = array_merge(['userID' => $userID], $data);
        return Utils::send(Url::$updateUserInfo, $data, '修改用户信息错误', $token);
    }

    /**
     * 搜索通知账号
     * @param string $token 管理员token
     * @param string $keyword 搜索关键词
     * @param int $pagination 页码
     * @param int $showNumber 每页数量
     * @return array
     */
    public function searchNotificationAccount(string $token, string $keyword, int $pagination = 1, int $showNumber = 20): array
    {
        $data = [
            'keyword' => $keyword,
            'pagination' => [
                'pageNumber' => $pagination,
                'showNumber' => $showNumber
            ]
        ];
        return Utils::send(Url::$searchNotificationAccount, $data, '搜索通知账号错误', $token);
    }

    /**
     * 添加通知账号
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @param string $nickname 昵称
     * @param string $faceURL 头像
     * @param int $gender 性别
     * @param string $phoneNumber 手机号
     * @param string $birth 生日
     * @param string $email 邮箱
     * @param string $ex 扩展字段
     * @return array
     */
    public function addNotificationAccount(string $token, string $userID, string $nickname = '', string $faceURL = '', int $gender = 1, string $phoneNumber = '', string $birth = '', string $email = '', string $ex = ''): array
    {
        $data = [
            'userID' => $userID,
            'nickname' => $nickname,
            'faceURL' => $faceURL,
            'gender' => $gender,
            'phoneNumber' => $phoneNumber,
            'birth' => $birth,
            'email' => $email,
            'ex' => $ex
        ];
        return Utils::send(Url::$addNotificationAccount, $data, '添加通知账号错误', $token);
    }

    /**
     * 更新通知账号
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @param string $nickname 昵称
     * @param string $faceURL 头像
     * @param int $gender 性别
     * @param string $phoneNumber 手机号
     * @param string $birth 生日
     * @param string $email 邮箱
     * @param string $ex 扩展字段
     * @return array
     */
    public function updateNotificationAccount(string $token, string $userID, string $nickname = '', string $faceURL = '', int $gender = 1, string $phoneNumber = '', string $birth = '', string $email = '', string $ex = ''): array
    {
        $data = [
            'userID' => $userID,
            'nickname' => $nickname,
            'faceURL' => $faceURL,
            'gender' => $gender,
            'phoneNumber' => $phoneNumber,
            'birth' => $birth,
            'email' => $email,
            'ex' => $ex
        ];
        return Utils::send(Url::$updateNotificationAccount, $data, '更新通知账号错误', $token);
    }

    /**
     * 检查列表账户注册状态
     * @param string $token 管理员token
     * @param array $checkUserIDList 用户ID列表
     * @return array
     */
    public function accountCheck(string $token, array $checkUserIDList): array
    {
        return Utils::send(Url::$accountCheck, ['checkUserIDList' => $checkUserIDList], '检查列表账户注册状态错误', $token);
    }

    /**
     * 获取所有用户uid列表
     * @param string $token 管理员token
     * @return array
     */
    public function getAllUsersUid(string $token): array
    {
        return Utils::send(Url::$getAllUsersUid, [], '获取所有用户uid列表错误', $token);
    }

    /**
     * 获取自己的信息
     * @param string $token 用户token
     * @param string $userID 用户ID
     * @return array
     */
    public function getSelfUserInfo(string $token, string $userID): array
    {
        return Utils::send(Url::$getSelfUserInfo, ['userID' => $userID], '获取自己的信息错误', $token);
    }

    /**
     * 获取用户信息
     * @param string $token 管理员token
     * @param array $userIDList 用户ID列表
     * @return array
     */
    public function getUsersInfo(string $token, array $userIDList): array
    {
        return Utils::send(Url::$getUsersInfo, ['userIDList' => $userIDList], '获取用户信息错误', $token);
    }
}
