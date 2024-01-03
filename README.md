# 微信会话内容存档

## 要求
* 需要PHP扩展 ext-wxwork_finance_sdk 或者 ext-ffi，二选一
* ext-ffi PHP编译安装时 `—with-ffi`
* 容器 `docker pull zhiqiangwang/php:7.4-cli-wxwork-finance` && `docker pull zhiqiangwang/php:7.4-fpm-wxwork-finance`
* ext-wxwork_finance_sdk 安装详见: https://github.com/pangdahua/php7-wxwork-finance-sdk

## 安装
~~~
composer require pkg6/wework-finance
~~~

## 使用

```
## 企业配置
$corpConfig = [
    'corpid'       => "foo",
    'secret'       => "foo",
    'private_keys' => [
        "v1" => "foo",
    ],
];
## 包配置
$srcConfig = [
    'default'   => 'ext',
    'providers' => [
        'ext' => \Pkg6\WeWorkFinance\Provider\PHPExtProvider::class,
        'ffi' => \Pkg6\WeWorkFinance\Provider\FFIProvider::class,
    ],
];

## 1、实例化
$sdk = new \Pkg6\WeWorkFinance\SDK($corpConfig, $srcConfig);

## 获取聊天记录
$chatData = $sdk->getDecryptChatData($seq, $limit);

## 解析media
$medium = $sdk->getMediaData($sdkFileId, $ext)

## 获取解密之后的聊天记录
$medium = $sdk->getDecryptChatData(int $seq, int $limit, int $retry = 0)
```

# 感谢与相关链接

- https://github.com/pangdahua/php7-wxwork-finance-sdk
- https://developer.work.weixin.qq.com/document/path/91774
