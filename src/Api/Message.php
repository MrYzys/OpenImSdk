<?php

namespace OpenImSdk\Api;

use OpenImSdk\Core\Url;
use OpenImSdk\Core\Utils;

class Message
{
    /**
     * 发送消息
     * @param string $token 管理员token
     * @param string $sendID 发送者ID
     * @param string $recvID 接收者ID，单聊时必填
     * @param string $groupID 群组ID，群聊时必填
     * @param string $senderNickname 发送者昵称
     * @param string $senderFaceURL 发送者头像
     * @param int $senderPlatformID 发送者平台ID
     * @param array $content 消息内容
     * @param int $contentType 消息类型
     * @param int $sessionType 会话类型，1单聊，2群聊
     * @param bool $isOnlineOnly 是否仅在线用户接收
     * @param bool $notOfflinePush 是否不离线推送
     * @param int $sendTime 发送时间，毫秒
     * @param array $offlinePushInfo 离线推送信息
     * @param string $ex 扩展字段
     * @return array
     */
    public function sendMsg(string $token, string $sendID, string $recvID = '', string $groupID = '', string $senderNickname = '', string $senderFaceURL = '', int $senderPlatformID = 1, array $content = [], int $contentType = 101, int $sessionType = 1, bool $isOnlineOnly = false, bool $notOfflinePush = false, int $sendTime = 0, array $offlinePushInfo = [], string $ex = ''): array
    {
        $data = [
            'sendID' => $sendID,
            'senderNickname' => $senderNickname,
            'senderFaceURL' => $senderFaceURL,
            'senderPlatformID' => $senderPlatformID,
            'contentType' => $contentType,
            'sessionType' => $sessionType,
            'isOnlineOnly' => $isOnlineOnly,
            'notOfflinePush' => $notOfflinePush,
            'ex' => $ex
        ];

        // 根据会话类型设置recvID或groupID
        if ($sessionType == 1 && !empty($recvID)) {
            $data['recvID'] = $recvID;
        } elseif ($sessionType == 2 && !empty($groupID)) {
            $data['groupID'] = $groupID;
        }

        // 设置消息内容
        if (empty($content)) {
            $data['content'] = ['text' => ''];
        } else {
            $data['content'] = $content;
        }

        // 设置发送时间，如果有的话
        if ($sendTime > 0) {
            $data['sendTime'] = $sendTime;
        }

        // 设置离线推送信息，如果有的话
        if (!empty($offlinePushInfo)) {
            $data['offlinePushInfo'] = $offlinePushInfo;
        }

        return Utils::send(Url::$sendMsg, $data, '发送消息失败', $token);
    }

    /**
     * 批量发送消息
     * @param string $token 管理员token
     * @param string $sendID 发送者ID
     * @param string $senderNickname 发送者昵称
     * @param string $senderFaceURL 发送者头像
     * @param int $sessionType 会话类型
     * @param int $contentType 消息类型
     * @param string $content 消息内容
     * @return array
     */
    public function batchSendMsg(string $token, string $sendID, string $senderNickname, string $senderFaceURL, int $sessionType, int $contentType, string $content): array
    {
        $data = [
            'senderPlatformID' => 0,
            'sendID' => $sendID,
            'senderNickname' => $senderNickname,
            'senderFaceURL' => $senderFaceURL,
            'sessionType' => $sessionType,
            'contentType' => $contentType,
            'content' => ['text' => $content]
        ];
        return Utils::send(Url::$batchSendMsg, $data, '批量发送消息失败', $token);
    }

    /**
     * 清空用户消息
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function clearMsg(string $token, string $userID): array
    {
        return Utils::send(Url::$clearMsg, ['userID' => $userID], '清空用户消息失败', $token);
    }

    /**
     * 根据seq列表删除消息
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @param string $conversationID 会话ID
     * @param array $seqs seq列表
     * @return array
     */
    public function delMsg(string $token, string $userID, string $conversationID, array $seqs): array
    {
        $data = [
            'userID' => $userID,
            'conversationID' => $conversationID,
            'seqs' => $seqs
        ];
        return Utils::send(Url::$delMsg, $data, '删除消息失败', $token);
    }

    /**
     * 撤回消息
     * @param string $token 管理员token
     * @param string $conversationID 会话ID
     * @param string $seq 消息seq
     * @param string $userID 用户ID
     * @return array
     */
    public function revokeMessage(string $token, string $conversationID, string $seq, string $userID): array
    {
        $data = [
            'conversationID' => $conversationID,
            'seq' => $seq,
            'userID' => $userID
        ];
        return Utils::send(Url::$revokeMessage, $data, '撤回消息失败', $token);
    }

    /**
     * 发送业务通知
     * @param string $token 管理员token
     * @param string $sendID 发送者ID
     * @param string $recvID 接收者ID
     * @param string $title 通知标题
     * @param string $content 通知内容
     * @param string $notificationUrl 通知点击跳转链接
     * @param string $ex 扩展字段
     * @return array
     */
    public function sendBusinessNotification(string $token, string $sendID, string $recvID, string $title, string $content, string $notificationUrl = '', string $ex = ''): array
    {
        $data = [
            'sendID' => $sendID,
            'recvID' => $recvID,
            'title' => $title,
            'content' => $content,
            'notificationUrl' => $notificationUrl,
            'ex' => $ex
        ];
        return Utils::send(Url::$sendBusinessNotification, $data, '发送业务通知失败', $token);
    }

    /**
     * 获取用户所有会话
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getAllConversations(string $token, string $userID): array
    {
        return Utils::send(Url::$getAllConversations, ['userID' => $userID], '获取用户所有会话失败', $token);
    }

    /**
     * 根据会话ID获取会话
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @param string $conversationID 会话ID
     * @return array
     */
    public function getConversation(string $token, string $userID, string $conversationID): array
    {
        $data = [
            'userID' => $userID,
            'conversationID' => $conversationID
        ];
        return Utils::send(Url::$getConversation, $data, '获取会话失败', $token);
    }

    /**
     * 根据会话ID列表获取会话
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @param array $conversationIDs 会话ID列表
     * @return array
     */
    public function getConversations(string $token, string $userID, array $conversationIDs): array
    {
        $data = [
            'userID' => $userID,
            'conversationIDs' => $conversationIDs
        ];
        return Utils::send(Url::$getConversations, $data, '获取会话列表失败', $token);
    }
}
