<?php

namespace OpenImSdk\Core;

use OpenImSdk\Exception\ValidatorException;

class Validator
{
    /**
     * 验证规则
     * @var string[]
     */
    private static $rules = [
        'userID' => 'max:64',
        'userID1' => 'max:64',
        'userID2' => 'max:64',
        'ownerUserID' => 'max:64',
        'friendUserID' => 'max:64',
        'blackUserID' => 'max:64',
        'fromUserID' => 'max:64',
        'toUserID' => 'max:64',
        'sendID' => 'max:64',
        'recvID' => 'max:64',
        'inviterUserID' => 'max:64',
        'nickname' => 'max:255',
        'faceURL' => 'max:255',
        'gender' => 'in:1,2',
        'groupID' => 'max:64',
        'groupName' => 'max:255',
        'introduction' => 'max:255',
        'notification' => 'max:255',
        'groupType' => 'in:0,1,2',
        'oldOwnerUserID' => 'max:64',
        'newOwnerUserID' => 'max:64',
        'conversationID' => 'max:128',
        'handleResult' => 'in:1,2',
    ];

    /**
     * 验证数组
     * @param array $data 要验证的数据
     * @return array 验证后的数据
     * @throws ValidatorException
     */
    public static function validateArray(array $data): array
    {
        foreach ($data as $field => $value) {
            foreach (self::$rules as $key => $rules) {
                if ($field == $key) {
                    $ruleList = explode('|', $rules);
                    foreach ($ruleList as $rule) {
                        $ruleParts = explode(':', $rule);
                        $method = $ruleParts[0];
                        $param = $ruleParts[1] ?? null;
                        self::$method($field, $value, $param);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 长度最大验证
     * @param string $field 字段名
     * @param mixed $value 字段值
     * @param int $maxLength 最大长度
     * @throws ValidatorException
     */
    private static function max(string $field, $value, $maxLength)
    {
        if (strlen($value) > (int)$maxLength) {
            throw new ValidatorException("参数 {$field} 长度不能超过 {$maxLength} 位");
        }
    }

    /**
     * 枚举值验证
     * @param string $field 字段名
     * @param mixed $value 字段值
     * @param string $allowedValues 允许的值（逗号分隔）
     * @throws ValidatorException
     */
    private static function in(string $field, $value, $allowedValues)
    {
        $allowed = explode(',', $allowedValues);
        if (!in_array($value, $allowed)) {
            throw new ValidatorException("参数 {$field} 的值必须是以下之一: {$allowedValues}，当前值: {$value}");
        }
    }
}
