<?php

namespace OpenImSdk\Core;

class Url
{
    // 认证管理
    static $getAdminToken = '/auth/get_admin_token';
    static $getUserToken = '/auth/get_user_token';
    static $forceLogout = '/auth/force_logout';
    static $parseToken = '/auth/parse_token';
    static $userToken = '/auth/user_token';

    // 用户管理
    static $userRegister = '/user/user_register';
    static $getUsers = '/user/get_users';
    static $getUsersOnlineStatus = '/user/get_users_online_status';
    static $getUsersOnlineTokenDetail = '/user/get_users_online_token_detail';
    static $getSubscribeUsersStatus = '/user/get_subscribe_users_status';
    static $subscribeUsersStatus = '/user/subscribe_users_status';
    static $setGlobalMsgRecvOpt = '/user/set_global_msg_recv_opt';
    static $updateUserInfo = '/user/update_user_info';
    static $searchNotificationAccount = '/user/search_notification_account';
    static $addNotificationAccount = '/user/add_notification_account';
    static $updateNotificationAccount = '/user/update_notification_account';
    static $accountCheck = '/user/account_check';
    static $getAllUsersUid = '/user/get_all_users_uid';
    static $getSelfUserInfo = '/user/get_self_user_info';
    static $getUsersInfo = '/user/get_users_info';

    // 好友管理
    static $addBlack = '/friend/add_black';
    static $addFriend = '/friend/add_friend';
    static $addFriendResponse = '/friend/add_friend_response';
    static $deleteFriend = '/friend/delete_friend';
    static $getBlackList = '/friend/get_black_list';
    static $getFriendApplyList = '/friend/get_friend_apply_list';
    static $getFriendList = '/friend/get_friend_list';
    static $getSelfFriendApplyList = '/friend/get_self_friend_apply_list';
    static $importFriend = '/friend/import_friend';
    static $isFriend = '/friend/is_friend';
    static $removeBlack = '/friend/remove_black';
    static $setFriendRemark = '/friend/set_friend_remark';
    static $updateFriends = '/friend/update_friends';

    // 群组管理
    static $createGroup = '/group/create_group';
    static $joinGroup = '/group/join_group';
    static $quitGroup = '/group/quit_group';
    static $getGroupsInfo = '/group/get_groups_info';
    static $getGroupMemberList = '/group/get_group_member_list';
    static $getGroupMembersInfo = '/group/get_group_members_info';
    static $inviteUserToGroup = '/group/invite_user_to_group';
    static $kickGroupMember = '/group/kick_group_member';
    static $transferGroupOwner = '/group/transfer_group_owner';
    static $getJoinedGroupList = '/group/get_joined_group_list';
    static $dismissGroup = '/group/dismiss_group';
    static $muteGroupMember = '/group/mute_group_member';
    static $cancelMuteGroupMember = '/group/cancel_mute_group_member';
    static $muteGroup = '/group/mute_group';
    static $cancelMuteGroup = '/group/cancel_mute_group';
    static $setGroupMemberNickname = '/group/set_group_member_nickname';
    static $setGroupMemberInfo = '/group/set_group_member_info';
    static $getGroupMemberUserIDs = '/group/get_group_member_user_i_ds';
    static $getGroupAllMemberList = '/group/get_group_all_member_list';
    static $getUserReqGroupApplicationList = '/group/get_user_req_group_applicationList';
    static $getGroupUsersReqApplicationList = '/group/get_group_users_req_application_list';
    static $groupApplicationResponse = '/group/group_application_response';

    // 消息管理
    static $sendMsg = '/msg/send_msg';
    static $batchSendMsg = '/msg/batch_send_msg';
    static $clearMsg = '/msg/clear_msg';
    static $delMsg = '/msg/del_msg';
    static $manageSendMsg = '/msg/manage_send_msg';
    static $revokeMessage = '/msg/revoke_msg';
    static $sendBusinessNotification = '/msg/send_business_notification';
    static $getAllConversations = '/msg/get_all_conversations';
    static $getConversation = '/msg/get_conversation';
    static $getConversations = '/msg/get_conversations';

    // 会话管理
    static $getOwnerConversation = '/conversation/get_owner_conversation';
    static $getSortedConversationList = '/conversation/get_sorted_conversation_list';
    static $setConversations = '/conversation/set_conversations';

    /**
     * 构建完整的API URL
     * @param string $path API路径
     * @return string 完整URL
     */
    public static function buildUrl(string $path): string
    {
        return Config::getHost() . $path;
    }
}
