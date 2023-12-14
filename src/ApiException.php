<?php

namespace Kangdev\Fulu;

class ApiException
{
    /**
     * 校验请求参数数据
     * @param $result
     * @throws \Exception
     */
    public static function checkData($result){
        if (empty($result['method'])){
            throw new \Exception("接口方法名称不能为空",404);
        }
    }

    /**
     * 校验配置参数
     * @param $result
     * @throws \Exception
     */
    public static function checkConfig($result){
        if (empty($result['AppKey'])){
            throw new \Exception("AppKey不能为空",404);
        }
        if (empty($result['AppSecret'])){
            throw new \Exception("AppSecret不能为空",404);
        }
        if (empty($result['MemberCode'])){
            throw new \Exception("MemberCode不能为空",404);
        }
    }
}