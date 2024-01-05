<?php

/*
 * This file is part of the pkg6/wework-finance.
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\WeWorkFinance;

use Pkg6\WeWorkFinance\Exception\InvalidArgumentException;

/**
 * @method string getTempDir();
 * @method string getChatData(int $seq, int $limit)
 * @method string decryptData(string $randomKey, string $encryptStr)
 * @method \SplFileInfo getMediaData(string $sdkFileId, string $ext)
 * @method array getDecryptChatData(int $seq, int $limit, int $retry = 0)
 */
class SDK
{
    /**
     * @var array
     */
    protected $config = [
        'default' => 'ext',
        'providers' => [
            'ext' => \Pkg6\WeWorkFinance\Provider\PHPExtProvider::class,
            'ffi' => \Pkg6\WeWorkFinance\Provider\FFIProvider::class,
        ],
    ];

    /**
     * @var array
     */
    protected $wxConfig = [
//        'corpid' => "foo",
//        'secret' => "foo",
//        'private_keys' => [
//            "v1" => "foo",
//        ],
    ];

    /**
     * @param $wxConfig
     * @param $config
     */
    public function __construct($wxConfig, $config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->wxConfig = array_merge($this->wxConfig, $wxConfig);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return false|mixed
     *
     * @throws InvalidArgumentException
     */
    public function __call($name, $arguments)
    {
        $provider = $this->provider($this->config['default']);
        if (method_exists($provider, $name)) {
            return call_user_func_array([$provider, $name], $arguments);
        }
        throw new InvalidArgumentException('WxFinanceSDK::Method not defined. method:' . $name);
    }

    /**
     * @param $providerName
     *
     * @return ProviderInterface
     *
     * @throws InvalidArgumentException
     */
    public function provider($providerName): ProviderInterface
    {
        if ( ! $this->config['providers'] || ! $this->config['providers'][$providerName]) {
            throw new InvalidArgumentException("configurations are missing {$providerName} options");
        }

        return (new $this->config['providers'][$providerName]())->setConfig($this->wxConfig);
    }
}
