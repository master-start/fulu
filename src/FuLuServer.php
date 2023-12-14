<?php

namespace Kangdev\Fulu;

use Hanson\Foundation\Foundation;

/**
 * Class FuLu
 * @package KangDev\FuLu
 *
 * @method array postData($params,$method)
 */
class FuLuServer extends Foundation
{
    private $apiService;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->apiService = new Api($config);
    }

    public function __call($name, $arguments)
    {
        return $this->apiService->{$name}(...$arguments);
    }
}