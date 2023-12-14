<?php

namespace Kangdev\Fulu;


use Hanson\Foundation\AbstractAPI;

class BaseConfig extends AbstractAPI
{
    /**
     * 开放平台分配给商户的AppKey
     * @var string
     */
    protected $app_key;

    /**
     * 开放平台分配给商户的AppSecret
     * @var string
     */
    protected $app_secret;

    /**
     * 商户号
     * @var string
     */
    protected $member_code;

    /**
     * 接口方法名称
     * @var string
     */
    protected string $method = 'fulu.goods.info.get';

    /**
     * 时间戳，格式为：yyyy-MM-dd HH:mm:ss
     * @var string
     */
    protected $timestamp;

    /**
     * 调用的接口版本
     * @var string
     */
    private string $version = '2.0';

    /**
     * 接口请求或响应格式
     * @var string
     */
    private string $format = 'json';

    /**
     * 请求使用的编码格式，如utf-8等
     * @var string
     */
    private string $charset = 'utf-8';

    /**
     * 签名加密类型，目前仅支持md5
     * @var string
     */
    private string $sign_type = 'md5';

    /**
     * 签名串
     * @var string
     */
    protected string $sign;

    /**
     * 授权码，固定值
     * @var string
     */
    protected $app_auth_token;

    /**
     * 环境 1=正式，0=沙箱测试
     * @var mixed
     */
    protected $online;

    /**
     * 沙箱环境
     * @var string
     */
    const DEV_URL = 'http://pre.openapi.fulu.com/api/getway';

    /**
     * 正式环境
     */
    const URL = 'http://pre.openapi.fulu.com/api/getway';

    public function __construct(array $config)
    {
        $this->online = $config['online'] ?? 0;
        if ($this->online == 1){
            ApiException::checkConfig($config);
        }
        $this->app_key = $config['AppKey'] ?? 'i4esv1l+76l/7NQCL3QudG90Fq+YgVfFGJAWgT+7qO1Bm9o/adG/1iwO2qXsAXNB';
        $this->app_secret = $config['AppSecret'] ?? '0a091b3aa4324435aab703142518a8f7';
        $this->member_code = $config['MemberCode'] ?? '9000358';
        $this->app_auth_token = $config['app_auth_token'] ?? '';
        $this->timestamp = date("Y-m-d H:i:s",time());
    }

    /**
     * php签名方法
     */
    public function getSign($Parameters)
    {
        //签名步骤一：把字典json序列化
        $json = json_encode($Parameters,320);
        //签名步骤二：转化为数组
        $jsonArr = $this->mb_str_split($json);
        //签名步骤三：排序
        sort($jsonArr);
        //签名步骤四：转化为字符串
        $string = implode('', $jsonArr);
        //签名步骤五：在string后加入secret
        $string = $string . $this->app_secret;
        //签名步骤六：MD5加密
        return strtolower(md5($string));
    }

    /**
     * 卡密解密
     * @param $enpass
     * @return string
     */
    public function decode($enpass) {
        $encryptString = base64_decode($enpass);
        $decryptedpass = rtrim(openssl_decrypt($encryptString,'aes-256-ecb',$this->app_secret,OPENSSL_RAW_DATA));
        return trim($decryptedpass);
    }

    /**
     * 可将字符串中中文拆分成字符数组
     */
    public function mb_str_split($str){
        return preg_split('/(?<!^)(?!$)/u', $str );
    }

    /**
     * @param array $params
     * @param string $type
     * @return mixed
     * @throws \Hanson\Foundation\Exception\HttpException
     */
    public function request(array $params,string $type = "post")
    {
        $this->method = $params['method']; //接口方法名称
        //通用参数
        $param = [
            'app_key' => $this->app_key,
            'method' => $params['method'],
            'timestamp' => $this->timestamp,
            'version' => $this->version,
            'format' => $this->format,
            'charset' => $this->charset,
            'sign_type' => $this->sign_type,
            'app_auth_token' => $this->app_auth_token,
        ];
        unset($params['method']);

        //请求参数集合（注意：该参数是以json字符串的形式传输）
        $param['biz_content'] = json_encode($params);

        //与请求参数的顺序 ??
        $this->sign = $this->getSign($param);

        $param['sign'] = $this->sign;

        //环境 1=正式，0=测试
        $online = $this->online;
        if ($online == 1){
            $url = self::URL;
        }else{
            $url = self::DEV_URL;
        }

        //请求到第三方
        $http = $this->getHttp();
        switch ($type){
            case "json":
                $response = $http->json($url, $param);
                break;
            case "post":
                $response = $http->post($url, $param);
                break;
            case "get":
                $response = $http->get($url, $param);
                break;
            default:
                $response = $http->post($url, $param);
        }

        //响应
        return json_decode(strval($response->getBody()), true);
    }

}