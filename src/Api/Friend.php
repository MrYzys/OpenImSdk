<?php

namespace OpenImSdk\Api;

use OpenImSdk\Core\Url;
use OpenImSdk\Core\Utils;

class Friend
{
    /**
     * 添加黑名单
     * @param string $token 管理员token
     * @param string $ownerUserID 用户ID
     * @param string $blackUserID 被拉黑用户ID
     * @return array
     */
    public function addBlack(string $token, string $ownerUserID, string $blackUserID): array
    {
        $data = ['ownerUserID' => $ownerUserID, 'blackUserID' => $blackUserID];
        return Utils::send(Url::$addBlack, $data, '添加黑名单错误', $token);
    }

    /**
     * 添加好友
     * @param string $token 管理员token
     * @param string $fromUserID 发送者ID
     * @param string $toUserID 接收者ID
     * @param string $reqMsg 请求消息
     * @return array
     */
    public function addFriend(string $token, string $fromUserID, string $toUserID, string $reqMsg): array
    {
        $data = ['fromUserID' => $fromUserID, 'toUserID' => $toUserID, 'reqMsg' => $reqMsg];
        return Utils::send(Url::$addFriend, $data, '添加好友错误', $token);
    }

    /**
     * 同意/拒绝好友请求
     * @param string $token 管理员token
     * @param string $ownerUserID 处理者ID
     * @param string $friendUserID 好友ID
     * @param string $handleMsg 处理消息
     * @param int $handleResult 处理结果，1同意，2拒绝
     * @return array
     */
    public function addFriendResponse(string $token, string $ownerUserID, string $friendUserID, string $handleMsg, int $handleResult): array
    {
        $data = [
            'ownerUserID' => $ownerUserID,
            'friendUserID' => $friendUserID,
            'handleMsg' => $handleMsg,
            'handleResult' => $handleResult
        ];
        return Utils::send(Url::$addFriendResponse, $data, '同意/拒绝好友请求错误', $token);
    }

    /**
     * 删除好友
     * @param string $token 管理员token
     * @param string $ownerUserID 用户ID
     * @param string $friendUserID 好友ID
     * @return array
     */
    public function deleteFriend(string $token, string $ownerUserID, string $friendUserID): array
    {
        $data = ['ownerUserID' => $ownerUserID, 'friendUserID' => $friendUserID];
        return Utils::send(Url::$deleteFriend, $data, '删除好友错误', $token);
    }

    /**
     * 获取黑名单列表
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getBlackList(string $token, string $userID): array
    {
        return Utils::send(Url::$getBlackList, ['userID' => $userID], '获取黑名单列表错误', $token);
    }

    /**
     * 获取好友申请列表（收到的申请）
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getFriendApplyList(string $token, string $userID): array
    {
        return Utils::send(Url::$getFriendApplyList, ['userID' => $userID], '获取好友申请列表错误', $token);
    }

    /**
     * 获取用户的好友列表
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getFriendList(string $token, string $userID): array
    {
        return Utils::send(Url::$getFriendList, ['userID' => $userID], '获取用户的好友列表错误', $token);
    }

    /**
     * 获取自己的好友申请列表（发出的申请）
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getSelfFriendApplyList(string $token, string $userID): array
    {
        return Utils::send(Url::$getSelfFriendApplyList, ['userID' => $userID], '获取自己的好友申请列表错误', $token);
    }

    /**
     * 批量导入好友
     * @param string $token 管理员token
     * @param string $ownerUserID 用户ID
     * @param array $friendUserIDs 好友ID列表
     * @return array
     */
    public function importFriend(string $token, string $ownerUserID, array $friendUserIDs = []): array
    {
        $data = ['ownerUserID' => $ownerUserID, 'friendUserIDs' => $friendUserIDs];
        return Utils::send(Url::$importFriend, $data, '批量导入好友错误', $token);
    }

    /**
     * 检查用户之间是否为好友
     * @param string $token 管理员token
     * @param string $userID1 用户ID1
     * @param string $userID2 用户ID2
     * @return array
     */
    public function isFriend(string $token, string $userID1, string $userID2): array
    {
        $data = ['userID1' => $userID1, 'userID2' => $userID2];
        return Utils::send(Url::$isFriend, $data, '检查用户之间是否为好友错误', $token);
    }

    /**
     * 把用户移除黑名单
     * @param string $token 管理员token
     * @param string $ownerUserID 用户ID
     * @param string $blackUserID 被移除黑名单的用户ID
     * @return array
     */
    public function removeBlack(string $token, string $ownerUserID, string $blackUserID): array
    {
        $data = ['ownerUserID' => $ownerUserID, 'blackUserID' => $blackUserID];
        return Utils::send(Url::$removeBlack, $data, '把用户移除黑名单错误', $token);
    }

    /**
     * 设置好友备注
     * @param string $token 管理员token
     * @param string $fromUserID 用户ID
     * @param string $toUserID 好友ID
     * @param string $remark 备注
     * @return array
     */
    public function setFriendRemark(string $token, string $fromUserID, string $toUserID, string $remark): array
    {
        $data = ['fromUserID' => $fromUserID, 'toUserID' => $toUserID, 'remark' => $remark];
        return Utils::send(Url::$setFriendRemark, $data, '设置好友备注错误', $token);
    }

    /**
     * 更新好友信息
     * @param string $token 管理员token
     * @param string $ownerUserID 用户ID
     * @param string $friendUserID 好友ID
     * @param string $remark 备注
     * @param bool $isPinned 是否置顶
     * @param string $ex 扩展字段
     * @return array
     */
    public function updateFriends(string $token, string $ownerUserID, string $friendUserID, string $remark = '', bool $isPinned = false, string $ex = ''): array
    {
        $data = [
            'ownerUserID' => $ownerUserID,
            'friendUserID' => $friendUserID
        ];

        // 只添加非空参数
        if ($remark !== '') {
            $data['remark'] = $remark;
        }

        if ($isPinned) {
            $data['isPinned'] = $isPinned;
        }

        if ($ex !== '') {
            $data['ex'] = $ex;
        }

        return Utils::send(Url::$updateFriends, $data, '更新好友信息失败', $token);
    }
}
