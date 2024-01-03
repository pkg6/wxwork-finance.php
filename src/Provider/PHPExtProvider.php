<?php

namespace Pkg6\WeWorkFinance\Provider;

use Pkg6\WeWorkFinance\Exception\FinanceSDKException;
use Pkg6\WeWorkFinance\Exception\InvalidArgumentException;

class PHPExtProvider extends AbstractProvider
{

    /**
     * @var \WxworkFinanceSdk
     */
    protected $financeSdk;


    /**
     * {@inheritdoc}
     */
    public function getChatData(int $seq, int $limit): string
    {
        return $this->financeSdk->getChatData($seq, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function decryptData(string $randomKey, string $encryptStr): string
    {
        return $this->financeSdk->decryptData($randomKey, $encryptStr);
    }

    /**
     * {@inheritdoc}
     * @throws FinanceSDKException
     */
    public function getMediaData(string $sdkFileId, string $ext): \SplFileInfo
    {
        $path = $this->temp_dir($sdkFileId, $ext);
        try {
            $this->financeSdk->downloadMedia($sdkFileId, $path);
        } catch (\WxworkFinanceSdkExcption $e) {
            throw new FinanceSDKException('获取文件失败' . $e->getMessage(), $e->getCode());
        }
        return new \SplFileInfo($path);
    }

    /**
     * 获取php-ext-include.
     * @param array $config ...
     * @throws FinanceSDKException
     * @throws InvalidArgumentException
     */
    protected function setFinanceSDK(array $config = []): void
    {
        if (!extension_loaded('wxwork_finance_sdk')) {
            throw new FinanceSDKException('缺少ext-wxwork_finance_sdk扩展');
        }

        $this->config = array_merge($this->config, $config);
        if (!isset($this->config['corpid'])) {
            throw new InvalidArgumentException('缺少配置:corpid');
        }
        if (!isset($this->config['secret'])) {
            throw new InvalidArgumentException('缺少配置:secret');
        }
        $options = ['timeout' => 30];
        isset($this->config['proxy']) && $options['proxy_host'] = $this->config['proxy'];
        isset($this->config['passwd']) && $options['proxy_password'] = $this->config['passwd'];
        isset($this->config['timeout']) && $options['timeout'] = $this->config['timeout'];
        $this->financeSdk = new \WxworkFinanceSdk(
            $this->config['corpid'],
            $this->config['secret'],
            $options
        );
    }
}