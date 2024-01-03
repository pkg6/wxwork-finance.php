<?php

namespace Pkg6\WeWorkFinance\Provider;

use FFI;
use Pkg6\WeWorkFinance\Exception\FinanceSDKException;
use Pkg6\WeWorkFinance\Exception\InvalidArgumentException;

class FFIProvider extends AbstractProvider
{

    /**
     * @var FFI
     */
    protected $ffi;

    /**
     * @var string 指针
     */
    protected $financeSdk;

    /**
     * @var string C语言头
     */
    protected $cHeader = 'WeWorkFinanceSdk_C.h';

    /**
     * @var string C语言库
     */
    protected $cLib = 'libWeWorkFinanceSdk_C.so';


    public function __destruct()
    {
        // 释放sdk
        $this->financeSdk instanceof FFI && $this->ffi->DestroySdk($this->financeSdk);
    }


    /**
     * {@inheritdoc}
     * @throws FinanceSDKException ...
     */
    public function getChatData(int $seq, int $limit, int $timeout = 0): string
    {
        // 初始化buffer
        $chatDatas = $this->ffi->NewSlice();
        // 拉取内容
        $res = $this->ffi->GetChatData($this->financeSdk, $seq, $limit, $this->config['proxy'], $this->config['passwd'], $this->config['timeout'], $chatDatas);
        if ($res !== 0) {
            throw new FinanceSDKException(sprintf('GetChatData err res:%d', $res));
        }

        $resStr = FFI::string($chatDatas->buf);
        // 释放buffer
        $this->ffi->FreeSlice($chatDatas);
        $chatDatas->len = 0;
        return $resStr;
    }

    /**
     * {@inheritdoc}
     * @throws FinanceSDKException ...
     */
    public function decryptData(string $randomKey, string $encryptStr): string
    {
        // 初始化buffer
        $msg = $this->ffi->NewSlice();
        $res = $this->ffi->DecryptData($randomKey, $encryptStr, $msg);
        if ($res !== 0) {
            throw new FinanceSDKException(sprintf('RsaDecryptChatData err res:%d', $res));
        }
        $resStr = FFI::string($msg->buf);
        // 释放buffer
        $this->ffi->FreeSlice($msg);
        $msg->len = 0;
        return $resStr;
    }

    /**
     * {@inheritdoc}
     * @throws FinanceSDKException
     */
    public function getMediaData(string $sdkFileId, string $ext): \SplFileInfo
    {
        $path = $this->temp_dir($sdkFileId, $ext);
        try {
            $this->downloadMediaData($sdkFileId, $path);
        } catch (\WxworkFinanceSdkExcption $e) {
            throw new FinanceSDKException('获取文件失败' . $e->getMessage(), $e->getCode());
        }
        return new \SplFileInfo($path);
    }

    /**
     * 下载媒体资源.
     * @param string $sdkFileId file id
     * @param string $path 文件路径
     * @throws FinanceSDKException
     */
    protected function downloadMediaData(string $sdkFileId, string $path): void
    {
        $indexBuf = '';

        while (true) {
            // 初始化buffer MediaData_t*
            $media = $this->ffi->NewMediaData();

            // 拉取内容
            $res = $this->ffi->GetMediaData($this->financeSdk, $indexBuf, $sdkFileId, $this->config['proxy'], $this->config['passwd'], $this->config['timeout'], $media);
            if ($res !== 0) {
                $this->ffi->FreeMediaData($media);
                throw new FinanceSDKException(sprintf('GetMediaData err res:%d\n', $res));
            }
            // buffer写入文件
            $handle = fopen($path, 'ab+');
            if (!$handle) {
                throw new \RuntimeException(sprintf('打开文件失败:%s', $path));
            }
            fwrite($handle, FFI::string($media->data, $media->data_len), $media->data_len);
            fclose($handle);
            // 完成下载
            if ($media->is_finish === 1) {
                $this->ffi->FreeMediaData($media);
                break;
            }
            // 重置文件指针
            $indexBuf = FFI::string($media->outindexbuf);
            $this->ffi->FreeMediaData($media);
        }
    }

    /**
     * 获取php-ext-include.
     * @param array $config ...
     * @throws FinanceSDKException ...
     * @throws InvalidArgumentException ...
     */
    protected function setFinanceSDK(array $config = []): void
    {
        if (!extension_loaded('ffi')) {
            throw new FinanceSDKException('缺少ext-ffi扩展');
        }

        $this->config = array_merge($this->config, $config);
        if (!isset($this->config['corpid'])) {
            throw new InvalidArgumentException('缺少配置:corpid');
        }
        if (!isset($this->config['secret'])) {
            throw new InvalidArgumentException('缺少配置:secret');
        }
        isset($this->config['proxy']) || $this->config['proxy'] = '';
        isset($this->config['passwd']) || $this->config['passwd'] = '';
        isset($this->config['timeout']) || $this->config['timeout'] = '';
        // C包路径
        $includePath = dirname(__DIR__, 2) . '/include/';
        //C语言头WeWorkFinanceSdk_C.h
        isset($this->config['include_we_work_finance_sdk_c_h']) || $this->config['include_we_work_finance_sdk_c_h'] = $includePath . $this->cHeader;
        //C语言库 libWeWorkFinanceSdk_C.so
        isset($this->config['include_lib_we_work_finance_sdk_c_so']) || $this->config['include_lib_we_work_finance_sdk_c_so'] = $includePath . $this->cLib;;

        $this->cHeader = $this->config['include_we_work_finance_sdk_c_h'];
        $this->cLib    = $this->config['include_lib_we_work_finance_sdk_c_so'];
        // 引入ffi
        $this->ffi = FFI::cdef(file_get_contents($this->cHeader), $this->cLib);

        // WeWorkFinanceSdk_t* include
        $this->financeSdk = $this->ffi->NewSdk();
        // 初始化
        $res = $this->ffi->Init($this->financeSdk, $this->config['corpid'], $this->config['secret']);
        if ($res !== 0) {
            throw new FinanceSDKException('ffi:Init() 初始化错误');
        }
    }
}