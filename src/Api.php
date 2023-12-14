<?php

namespace Kangdev\Fulu;

use Hanson\Foundation\Exception\HttpException;

class Api extends BaseConfig
{
    /**
     * 通用请求
     * @param array $params 请求参数
     * @param string $method 请求方式
     * @return mixed
     * @throws \Exception
     */
    public function postData(array $params, string $method = 'json'){
        try {
            ApiException::checkData($params);
            return $this->request($params, $method);
        }
        catch (HttpException $e) {
            throw new \Exception($e);
        }
    }
}