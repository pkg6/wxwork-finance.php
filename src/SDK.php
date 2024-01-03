<?php

namespace Pkg6\WeWorkFinance;

use Pkg6\WeWorkFinance\Exception\InvalidArgumentException;


/**
 * @method string getChatData(int $seq, int $limit)
 * @method string decryptData(string $randomKey, string $encryptStr)
 * @method \SplFileInfo getMediaData(string $sdkFileId, string $ext)
 * @method array getDecryptChatData(int $seq, int $limit, int $retry = 0)
 */
class SDK
{
    protected $config = [
        'default'   => 'ext',
        'providers' => [
            'ext' => \Pkg6\WeWorkFinance\Provider\PHPExtProvider::class,
            'ffi' => \Pkg6\WeWorkFinance\Provider\FFIProvider::class,
        ],
    ];

    protected $wxConfig = [
        'corpid'       => "foo",
        'secret'       => "foo",
        'private_keys' => [
            "v1" => "foo",
        ],
    ];


    public function __construct($wxConfig, $config = [])
    {
        $this->config   = array_merge($this->config, $config);
        $this->wxConfig = array_merge($this->wxConfig, $wxConfig);
    }

    public function __call($name, $arguments)
    {
        $provider = $this->provider($this->config['default']);
        if (method_exists($provider, $name)) {
            return call_user_func_array([$provider, $name], $arguments);
        }
        throw new InvalidArgumentException('WxFinanceSDK::Method not defined. method:' . $name);

    }

    public function provider($providerName): ProviderInterface
    {
        if (!$this->config['providers'] || !$this->config['providers'][$providerName]) {
            throw new InvalidArgumentException("configurations are missing {$providerName} options");
        }
        return (new $this->config['providers'][$providerName]())->setConfig($this->wxConfig);
    }
}