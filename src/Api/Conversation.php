<?php

namespace OpenImSdk\Api;

use OpenImSdk\Core\Url;
use OpenImSdk\Core\Utils;

class Conversation
{
    /**
     * 获取当前用户分页会话列表
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @param int $pageNumber 页码，从1开始
     * @param int $showNumber 每页数量
     * @return array
     */
    public function getOwnerConversation(string $token, string $userID, int $pageNumber = 1, int $showNumber = 20): array
    {
        $data = [
            'userID' => $userID,
            'pagination' => [
                'pageNumber' => $pageNumber,
                'showNumber' => $showNumber
            ]
        ];
        return Utils::send(Url::$getOwnerConversation, $data, '获取当前用户分页会话列表失败', $token);
    }

    /**
     * 获取排序的会话列表
     * @param string $token 管理员token
     * @param string $userID 用户ID
     * @return array
     */
    public function getSortedConversationList(string $token, string $userID): array
    {
        return Utils::send(Url::$getSortedConversationList, ['userID' => $userID], '获取排序的会话列表失败', $token);
    }

    /**
     * 为多个用户设置相同会话ID的字段
     * @param string $token 管理员token
     * @param string $conversationID 会话ID
     * @param array $userIDs 用户ID列表
     * @param int $recvMsgOpt 接收消息选项
     * @param bool $isPinned 是否置顶
     * @param bool $isPrivateChat 是否私聊
     * @param int $groupAtType 群@类型
     * @param string $ex 扩展字段
     * @param bool $isMsgDestruct 是否开启消息销毁
     * @param int $msgDestructTime 消息销毁时间
     * @param int $burnDuration 阅后即焚时长
     * @return array
     */
    public function setConversations(string $token, string $conversationID, array $userIDs, int $recvMsgOpt = 0, bool $isPinned = false, bool $isPrivateChat = false, int $groupAtType = 0, string $ex = '', bool $isMsgDestruct = false, int $msgDestructTime = 0, int $burnDuration = 0): array
    {
        $data = [
            'conversationID' => $conversationID,
            'userIDs' => $userIDs,
            'conversation' => [
                'recvMsgOpt' => $recvMsgOpt,
                'isPinned' => $isPinned,
                'isPrivateChat' => $isPrivateChat,
                'groupAtType' => $groupAtType,
                'ex' => $ex,
                'isMsgDestruct' => $isMsgDestruct,
                'msgDestructTime' => $msgDestructTime,
                'burnDuration' => $burnDuration
            ]
        ];
        return Utils::send(Url::$setConversations, $data, '为多个用户设置相同会话ID的字段失败', $token);
    }
}
