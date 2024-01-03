<?php

namespace Pkg6\WeWorkFinance\tests;

use PHPUnit\Framework\TestCase;
use Pkg6\WeWorkFinance\SDK;

class SeeTest extends TestCase
{
    /**
     * @var array 微信密钥等配置
     */
    public $weWorkConfig;

    /**
     * @var SDK
     */
    private $sdk;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->sdk = new SDK([
            'corpid' =>"corpid",
            'secret' => "secret",
            'private_keys' => [
                "v1" => "private_key",
            ],
        ]);
        parent::__construct($name, $data, $dataName);
    }

    /**
     * 获取微信数据
     */
    public function testGetChatData(): void
    {
        $chatDataArr = $this->sdk->getDecryptChatData(0, 1000);
        self::assertNotEmpty($chatDataArr);

        $videoTest = false;
        $imageTest = false;
        foreach ($chatDataArr as $msg) {
            // 视频
            if (isset($msg['video']) && false === $videoTest) {
                $this->_testMediaData($msg['video']['sdkfileid'], 'mp4', $msg['video']['md5sum']);
                $videoTest = true;
            }
            // 图片
            if (isset($msg['image']) && false === $imageTest) {
                $this->_testMediaData($msg['image']['sdkfileid'], 'jpg', $msg['image']['md5sum']);
                $imageTest = true;
            }
        }
    }

    /**
     * 下载数据
     */
    public function _testMediaData(string $sdkFileId, string $ext, string $md5sum): void
    {
        $file = $this->sdk->getMediaData($sdkFileId, $ext);
        self::assertEquals(md5_file($file), $md5sum, '下载文件失败');
        file_exists($file->getPathname()) && unlink($file->getPathname());
    }
}