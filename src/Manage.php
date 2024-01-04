<?php

namespace Pkg6\WeWorkFinance;

use Pkg6\WeWorkFinance\Exception\InvalidArgumentException;

/**
 *
 * @method string getTempDir();
 * @method string getChatData(int $seq, int $limit)
 * @method string decryptData(string $randomKey, string $encryptStr)
 * @method \SplFileInfo getMediaData(string $sdkFileId, string $ext)
 * @method array getDecryptChatData(int $seq, int $limit, int $retry = 0)
 */
class Manage
{
    /**
     * @var array
     */
    protected $config = [
//        "default" => "wx1",
//        "config" => [
//            "wx1" => [
//                'corpid' => "foo",
//                'secret' => "foo",
//                'private_keys' => [
//                    "v1" => "foo",
//                ],
//            ],
//        ],
        'provider' => [
            'default'   => 'ext',
            'providers' => [
                'ext' => \Pkg6\WeWorkFinance\Provider\PHPExtProvider::class,
                'ffi' => \Pkg6\WeWorkFinance\Provider\FFIProvider::class,
            ],
        ],
    ];

    /**
     * @param $config
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @param $default
     * @return SDK
     * @throws InvalidArgumentException
     */
    public function sdk($default)
    {
        if (!$this->config['config'] || !$this->config['config'][$default] || !$this->config['providers']) {
            throw new InvalidArgumentException("Missing parameter");
        }
        return new SDK($this->config['config'][$default], $this->config['provider']);
    }

    /**
     * @param $name
     * @param $arguments
     * @return false|mixed
     * @throws InvalidArgumentException
     */
    public function __call($name, $arguments)
    {
        $sdk = $this->sdk($this->config['default']);
        if (method_exists($sdk, $name)) {
            return call_user_func_array([$sdk, $name], $arguments);
        }
        throw new InvalidArgumentException('WxFinanceSDK::Method not defined. method:' . $name);
    }
}