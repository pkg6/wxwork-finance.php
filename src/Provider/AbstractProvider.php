<?php

/*
 * This file is part of the pkg6/wework-finance.
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\WeWorkFinance\Provider;

use Pkg6\WeWorkFinance\Exception\FinanceSDKException;
use Pkg6\WeWorkFinance\Exception\InvalidArgumentException;
use Pkg6\WeWorkFinance\ProviderInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     *
     * @return void
     */
    abstract protected function setFinanceSDK(array $config = []);

    /**
     * {@inheritdoc}
     */
    public function setConfig(array $config): ProviderInterface
    {
        $this->config = array_merge($this->config, $config);
        $this->setFinanceSDK();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getTempDir(): string
    {
        isset($this->config['temp_dir']) || $this->config['temp_dir'] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "pkg6wx";

        return $this->config['temp_dir'];
    }

    /**
     * @param string $sdkFileId
     * @param string $ext
     *
     * @return string
     */
    protected function tempDirPath(string $sdkFileId, string $ext): string
    {
        $path = $this->getTempDir() . DIRECTORY_SEPARATOR . $this->config['corpid'] . DIRECTORY_SEPARATOR . md5($sdkFileId);
        $ext && $path .= '.' . $ext;
        if ( ! is_dir($basePath = pathinfo($path, PATHINFO_DIRNAME))) {
            @mkdir($basePath, 0777, true);
        }

        return $path;
    }

    /**
     * 获取会话解密记录数据.
     *
     * @param int $seq 起始位置
     * @param int $limit 限制条数
     * @param int $retry 重试次数
     *
     * @return array ...
     *
     * @throws InvalidArgumentException
     * @throws FinanceSDKException
     */
    public function getDecryptChatData(int $seq, int $limit, int $retry = 0): array
    {
        $config = $this->getConfig();
        if ( ! isset($config['private_keys'])) {
            throw new InvalidArgumentException('缺少配置:private_keys[{"version":"private_key"}]');
        }
        $privateKeys = $config['private_keys'];
        try {
            $chatData = json_decode($this->getChatData($seq, $limit), true)['chatdata'];
            $newChatData = [];
            $lastSeq = 0;
            foreach ($chatData as $i => $item) {
                $lastSeq = $item['seq'];
                if ( ! isset($privateKeys[$item['publickey_ver']])) {
                    continue;
                }
                $decryptRandKey = null;
                openssl_private_decrypt(
                    base64_decode($item['encrypt_random_key']),
                    $decryptRandKey,
                    $privateKeys[$item['publickey_ver']],
                    OPENSSL_PKCS1_PADDING
                );
                if ($decryptRandKey === null) {
                    continue;
                }
                $newChatData[$i] = json_decode($this->decryptData($decryptRandKey, $item['encrypt_chat_msg']), true);
                $newChatData[$i]['seq'] = $item['seq'];
            }
            if ( ! empty($chatData) && empty($chatData) && $retry && $retry < 10) {
                return $this->getDecryptChatData($lastSeq, $limit, ++$retry);
            }

            return $newChatData;
        } catch (\Exception $e) {
            throw new FinanceSDKException($e->getMessage(), $e->getCode());
        }
    }
}
