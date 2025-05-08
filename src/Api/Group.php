<?php

namespace OpenImSdk\Api;

use OpenImSdk\Core\Url;
use OpenImSdk\Core\Utils;

class Group
{
    /**
     * 创建群组
     * @param string $token 管理员token
     * @param string $ownerUserID 群主ID
     * @param array $memberUserIDs 普通成员ID列表
     * @param array $adminUserIDs 管理员ID列表
     * @param string $groupName 群名称
     * @param string $groupID 群ID，可选
     * @param string $faceURL 群头像
     * @param string $introduction 群简介
     * @param string $notification 群公告
     * @param string $ex 扩展字段
     * @param int $groupType 群类型，固定为2
     * @param int $needVerification 加群验证方式
     * @param int $lookMemberInfo 查看群成员信息权限
     * @param int $applyMemberFriend 群内加好友权限
     * @return array
     */
    public function createGroup(string $token, string $ownerUserID, array $memberUserIDs = [], array $adminUserIDs = [], string $groupName = '', 
                                string $groupID = '', string $faceURL = '', string $introduction = '', string $notification = '', 
                                string $ex = '', int $groupType = 2, int $needVerification = 0, int $lookMemberInfo = 0, int $applyMemberFriend = 0): array
    {
        $data = [
            'ownerUserID' => $ownerUserID,
            'memberUserIDs' => $memberUserIDs,
            'adminUserIDs' => $adminUserIDs,
            'groupInfo' => [
                'groupID' => $groupID,
                'groupName' => $groupName,
                'notification' => $notification,
                'introduction' => $introduction,
                'faceURL' => $faceURL,
                'ex' => $ex,
                'groupType' => $groupType,
                'needVerification' => $needVerification,
                'lookMemberInfo' => $lookMemberInfo,
                'applyMemberFriend' => $applyMemberFriend
            ]
        ];
        return Utils::send(Url::$createGroup, $data, '创建群组失败', $token);
    }

    /**
     * 申请加入群组
     * @param string $token 用户token
     * @param string $groupID 群组ID
     * @param string $reqMsg 申请消息
     * @param int $joinSource 加入来源
     * @return array
     */
    public function joinGroup(string $token, string $groupID, string $reqMsg = '', int $joinSource = 0): array
    {
        $data = [
            'groupID' => $groupID,
            'reqMsg' => $reqMsg,
            'joinSource' => $joinSource
        ];
        return Utils::send(Url::$joinGroup, $data, '申请加入群组失败', $token);
    }

    /**
     * 退出群组
     * @param string $token 用户token
     * @param string $groupID 群组ID
     * @return array
     */
    public function quitGroup(string $token, string $groupID): array
    {
        return Utils::send(Url::$quitGroup, ['groupID' => $groupID], '退出群组失败', $token);
    }

    /**
     * 获取群组信息
     * @param string $token 管理员token
     * @param array $groupIDs 群组ID列表
     * @return array
     */
    public function getGroupsInfo(string $token, array $groupIDs): array
    {
        return Utils::send(Url::$getGroupsInfo, ['groupIDs' => $groupIDs], '获取群组信息失败', $token);
    }

    /**
     * 获取群成员列表
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param int $filter 过滤类型，0所有，1群主，2管理员，3普通成员，4禁言，5进入黑名单
     * @param int $offset 偏移量
     * @param int $count 数量
     * @return array
     */
    public function getGroupMemberList(string $token, string $groupID, int $filter = 0, int $offset = 0, int $count = 100): array
    {
        $data = [
            'groupID' => $groupID,
            'filter' => $filter,
            'offset' => $offset,
            'count' => $count
        ];
        return Utils::send(Url::$getGroupMemberList, $data, '获取群成员列表失败', $token);
    }

    /**
     * 获取指定群成员信息
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param array $userIDs 用户ID列表
     * @return array
     */
    public function getGroupMembersInfo(string $token, string $groupID, array $userIDs): array
    {
        $data = [
            'groupID' => $groupID,
            'userIDs' => $userIDs
        ];
        return Utils::send(Url::$getGroupMembersInfo, $data, '获取指定群成员信息失败', $token);
    }

    /**
     * 将用户拉入群组
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $inviterUserID 邀请者ID
     * @param array $invitedUserIDList 被邀请的用户ID列表
     * @param string $reason 邀请原因
     * @return array
     */
    public function inviteUserToGroup(string $token, string $groupID, string $inviterUserID, array $invitedUserIDList, string $reason = ''): array
    {
        $data = [
            'groupID' => $groupID,
            'inviterUserID' => $inviterUserID,
            'invitedUserIDList' => $invitedUserIDList,
            'reason' => $reason,
        ];
        return Utils::send(Url::$inviteUserToGroup, $data, '将用户拉入群组失败', $token);
    }

    /**
     * 踢出群成员
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $kickUserID 踢出者ID
     * @param array $kickedUserIDs 被踢出的用户ID列表
     * @param string $reason 踢出原因
     * @return array
     */
    public function kickGroupMember(string $token, string $groupID, string $kickUserID, array $kickedUserIDs, string $reason = ''): array
    {
        $data = [
            'groupID' => $groupID,
            'kickUserID' => $kickUserID,
            'kickedUserIDs' => $kickedUserIDs,
            'reason' => $reason
        ];
        return Utils::send(Url::$kickGroupMember, $data, '踢出群成员失败', $token);
    }

    /**
     * 转让群主
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $oldOwnerUserID 原群主ID
     * @param string $newOwnerUserID 新群主ID
     * @return array
     */
    public function transferGroupOwner(string $token, string $groupID, string $oldOwnerUserID, string $newOwnerUserID): array
    {
        $data = [
            'groupID' => $groupID,
            'oldOwnerUserID' => $oldOwnerUserID,
            'newOwnerUserID' => $newOwnerUserID
        ];
        return Utils::send(Url::$transferGroupOwner, $data, '转让群主失败', $token);
    }

    /**
     * 获取用户加入的群组列表
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getJoinedGroupList(string $token, string $userID): array
    {
        return Utils::send(Url::$getJoinedGroupList, ['userID' => $userID], '获取用户加入的群组列表失败', $token);
    }

    /**
     * 解散群组
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @return array
     */
    public function dismissGroup(string $token, string $groupID): array
    {
        return Utils::send(Url::$dismissGroup, ['groupID' => $groupID], '解散群组失败', $token);
    }

    /**
     * 设置群成员昵称
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $userID 用户ID
     * @param string $nickname 群内昵称
     * @return array
     */
    public function setGroupMemberNickname(string $token, string $groupID, string $userID, string $nickname): array
    {
        $data = [
            'groupID' => $groupID,
            'userID' => $userID,
            'nickname' => $nickname
        ];
        return Utils::send(Url::$setGroupMemberNickname, $data, '设置群成员昵称失败', $token);
    }

    /**
     * 设置群成员信息
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $userID 用户ID
     * @param array $data 群成员信息
     * @return array
     */
    public function setGroupMemberInfo(string $token, string $groupID, string $userID, array $data): array
    {
        $data = array_merge([
            'groupID' => $groupID,
            'userID' => $userID
        ], $data);
        return Utils::send(Url::$setGroupMemberInfo, $data, '设置群成员信息失败', $token);
    }

    /**
     * 获取群成员用户ID列表
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @return array
     */
    public function getGroupMemberUserIDs(string $token, string $groupID): array
    {
        return Utils::send(Url::$getGroupMemberUserIDs, ['groupID' => $groupID], '获取群成员用户ID列表失败', $token);
    }

    /**
     * 获取群成员列表
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param int $offset 偏移量
     * @param int $count 数量
     * @return array
     */
    public function getGroupAllMemberList(string $token, string $groupID, int $offset = 0, int $count = 100): array
    {
        $data = [
            'groupID' => $groupID,
            'pagination' => [
                'pageNumber' => intval($offset / $count) + 1,
                'showNumber' => $count
            ]
        ];
        return Utils::send(Url::$getGroupAllMemberList, $data, '获取群成员列表失败', $token);
    }

    /**
     * 获取用户加群申请列表
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getUserReqGroupApplicationList(string $token, string $userID): array
    {
        return Utils::send(Url::$getUserReqGroupApplicationList, ['userID' => $userID], '获取用户加群申请列表失败', $token);
    }
    
    /**
     * 获取指定用户对指定群组的加群请求
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param array $userIDs 用户ID列表
     * @return array
     */
    public function getGroupApplicationListByUserID(string $token, string $groupID, array $userIDs): array
    {
        $data = [
            'groupID' => $groupID,
            'userIDs' => $userIDs
        ];
        return Utils::send(Url::$getGroupUsersReqApplicationList, $data, '获取指定用户对指定群组的加群请求失败', $token);
    }

    /**
     * 处理群组申请
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $fromUserID 申请者ID
     * @param string $handledUserID 处理者ID
     * @param int $handleResult 处理结果，1同意，2拒绝
     * @param string $handleMsg 处理消息
     * @return array
     */
    public function groupApplicationResponse(string $token, string $groupID, string $fromUserID, string $handledUserID, int $handleResult, string $handleMsg = ''): array
    {
        $data = [
            'groupID' => $groupID,
            'fromUserID' => $fromUserID,
            'handledUserID' => $handledUserID,
            'handleResult' => $handleResult,
            'handleMsg' => $handleMsg
        ];
        return Utils::send(Url::$groupApplicationResponse, $data, '处理群组申请失败', $token);
    }

    /**
     * 禁言群组，只有群主和管理员可以发送消息
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @return array
     */
    public function muteGroup(string $token, string $groupID): array
    {
        return Utils::send(Url::$muteGroup, ['groupID' => $groupID], '禁言群组失败', $token);
    }

    /**
     * 取消禁言群组，所有成员都可以发送消息
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @return array
     */
    public function cancelMuteGroup(string $token, string $groupID): array
    {
        return Utils::send(Url::$cancelMuteGroup, ['groupID' => $groupID], '取消禁言群组失败', $token);
    }

    /**
     * 禁言群成员
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $userID 群成员ID
     * @param int $mutedSeconds 禁言时间（秒）
     * @return array
     */
    public function muteGroupMember(string $token, string $groupID, string $userID, int $mutedSeconds = 0): array
    {
        $data = ['groupID' => $groupID, 'userID' => $userID, 'mutedSeconds' => $mutedSeconds];
        return Utils::send(Url::$muteGroupMember, $data, '禁言群成员失败', $token);
    }

    /**
     * 取消禁言群成员
     * @param string $token 管理员token
     * @param string $groupID 群组ID
     * @param string $userID 群成员ID
     * @return array
     */
    public function cancelMuteGroupMember(string $token, string $groupID, string $userID): array
    {
        $data = ['groupID' => $groupID, 'userID' => $userID];
        return Utils::send(Url::$cancelMuteGroupMember, $data, '取消禁言群成员失败', $token);
    }
}
