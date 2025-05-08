# OpenIM PHP SDK

> 基于 [OpenIM](https://github.com/orgs/OpenIMSDK) 的 PHP SDK
>
> API文档: [https://docs.openim.io/restapi/apis/introduction](https://docs.openim.io/restapi/apis/introduction)

## 安装

```bash
composer require MrYzYs/OpenImSdk
```

## 配置

```php
$config = [
    'host' => 'http://127.0.0.1:10002', // OpenIM API地址
    'secret' => 'openIM123', // OpenIM密钥
];
```

## 基本使用

### 初始化客户端

```php
// 使用文件缓存（默认）
$IM = new OpenImSdk\Client($config);

// 使用Redis缓存 (phpredis)
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$IM = new OpenImSdk\Client($config, $redis);

// 使用Redis缓存 (predis)
$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);
$IM = new OpenImSdk\Client($config, $redis);

// 指定文件缓存目录
$IM = new OpenImSdk\Client($config, null, '/path/to/cache');
```

### 认证管理

```php
// 用户注册
$result = $IM->auth->userRegister('user123', '测试用户', 'https://example.com/avatar.jpg');

// 强制登出
$logout = $IM->auth->forceLogout('user123');

// 解析token
$tokenInfo = $IM->auth->parseToken($userToken);
```

### 用户管理

```php
// 获取用户列表
$users = $IM->user->getUsers($adminToken);

// 获取用户在线状态
$onlineStatus = $IM->user->getUsersOnlineStatus($adminToken, ['user123', 'user456']);

// 更新用户信息
$updateUser = $IM->user->updateUserInfo($adminToken, 'user123', [
    'nickname' => '新昵称',
    'faceURL' => 'https://example.com/new-avatar.jpg'
]);
```

### 消息管理

```php
// 发送消息
$sendMsg = $IM->message->sendMsg(
    $adminToken,
    'admin', // 发送者ID
    'user123', // 接收者ID
    '', // 群组ID（单聊时为空）
    '管理员', // 发送者昵称
    'https://example.com/admin-avatar.jpg', // 发送者头像
    1, // 发送者平台ID
    ['text' => '你好，这是一条测试消息'], // 消息内容
    101, // 消息类型（101为文本消息）
    1 // 会话类型（1为单聊）
);

// 撤回消息
$revokeMsg = $IM->message->revokeMessage(
    $adminToken,
    'single_user123', // 会话ID
    '123456', // 消息seq
    'user123' // 用户ID
);
```

### 会话管理

```php
// 获取用户分页会话列表
$conversations = $IM->conversation->getOwnerConversation(
    $adminToken,
    'user123', // 用户ID
    1, // 页码
    20 // 每页数量
);

// 获取排序的会话列表
$sortedConversations = $IM->conversation->getSortedConversationList(
    $adminToken,
    'user123' // 用户ID
);
```

### 好友管理

```php
// 批量导入好友
$importFriend = $IM->friend->importFriend(
    $adminToken,
    'user123', // 用户ID
    ['user456', 'user789'] // 好友ID列表
);

// 获取好友列表
$friendList = $IM->friend->getFriendList(
    $adminToken,
    'user123' // 用户ID
);

// 检查是否为好友
$isFriend = $IM->friend->isFriend(
    $adminToken,
    'user123', // 用户ID1
    'user456' // 用户ID2
);
```

### 群组管理

```php
// 创建群组
$createGroup = $IM->group->createGroup(
    $adminToken,
    'user123', // 群主ID
    [], // 普通成员ID列表
    [], // 管理员ID列表
    '测试群组', // 群名称
    '', // 群ID（可选）
    'https://example.com/group-avatar.jpg', // 群头像
    '群简介', // 群简介
    '群公告' // 群公告
);

// 邀请用户加入群组
$inviteToGroup = $IM->group->inviteUserToGroup(
    $adminToken,
    'group123', // 群组ID
    'user123', // 邀请者ID
    ['user456', 'user789'] // 被邀请的用户ID列表
);

// 获取群成员列表
$groupMembers = $IM->group->getGroupAllMemberList(
    $adminToken,
    'group123', // 群组ID
    0, // 偏移量
    100 // 数量
);

// 申请加入群组
$joinGroup = $IM->group->joinGroup(
    $userToken, // 用户token
    'group123', // 群组ID
    '我想加入这个群组' // 申请消息
);

// 处理群组申请
$handleApplication = $IM->group->groupApplicationResponse(
    $adminToken,
    'group123', // 群组ID
    'user456', // 申请者ID
    'user123', // 处理者ID
    1, // 处理结果，1同意，2拒绝
    '欢迎加入' // 处理消息
);

// 踢出群成员
$kickMember = $IM->group->kickGroupMember(
    $adminToken,
    'group123', // 群组ID
    'user123', // 踢出者ID
    ['user456'], // 被踢出的用户ID列表
    '违反群规' // 踢出原因
);

// 转让群主
$transferOwner = $IM->group->transferGroupOwner(
    $adminToken,
    'group123', // 群组ID
    'user123', // 原群主ID
    'user456' // 新群主ID
);
```

## 目录结构

```
src/
├── Api/                  # API接口类
│   ├── Auth.php          # 认证相关API
│   ├── Conversation.php  # 会话相关API
│   ├── Friend.php        # 好友相关API
│   ├── Group.php         # 群组相关API
│   ├── Message.php       # 消息相关API
│   └── User.php          # 用户相关API
├── Core/                 # 核心类
│   ├── Config.php        # 配置类
│   ├── TokenManager.php  # Token管理类
│   ├── Url.php           # URL管理
│   ├── Utils.php         # 工具类
│   └── Validator.php     # 验证器
├── Exception/            # 异常处理
│   └── ValidatorException.php  # 验证异常
└── Client.php            # 客户端入口
```
