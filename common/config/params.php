<?php
return [
    'system_version' => '1.1.3',
    'sys_tags' => '1',
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'service@wewok.co.uk',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 13600,

    'sqlCommentLanguage' => 'zh-CN', //sql的字段注释语言
    'httpProtocol' => 'https://', //网站域名使用https开头

    'defaultBackendLanguage' => 'zh-CN', //默认语言
    'defaultStoreId' => 2, //默认店铺ID，当不存在时用该id
    'defaultUserId' => 2, //默认用户ID
    'defaultSort' => 50, //默认排序ID
    'defaultFeedbackMessageTypeId' => 7, //默认在线反馈消息ID
    'defaultWebsiteLogo' => '/resources/images/default-logo.png', //默认在线反馈消息ID
    'defaultWebsiteBanner' => '/resources/images/default-banner.png', //默认在线反馈消息ID
    'defaultContactAddress' => '7 Wardour St, London W1D 6PE', //默认在线反馈消息ID
    'defaultContactMobile' => '0796 7888 883', //默认在线反馈消息ID
    'defaultLogo' => '/resources/images/default-logo.png', //默认在线反馈消息ID

    // 默认错误码，在返回前还会做i18n翻译
    'errorCode' => [
        200 => 'Operate Successfully',
        403 => 'No Auth',
        404 => 'Non-existent',
        422 => 'Input Parameter Error',
        429 => 'Expired',
        500 => 'Operation Failed',
    ],
    'htmlReturnFile' => '@frontend/views/site/html-return.php', // html样式返回成功失败

    /* 高并发，默认为false, new model 后直接使用 $model->id = IdHelper::snowFlakeId(); */
    'highConcurrency' => false,

    // Snowflake唯一ID
    'snowFlakeUniqueId' => false, // 修改此处不会影响ID顺序，如ID按照15261***开头，修改还是15261***开头，并且按照先后顺序
    'snowFlakeDataCenterId' => 0,
    'snowFlakeWorkerId' => 0,
    'snowFlakeStartAt' => '', //推荐不设置，设置会增加id长度，修改此处会影响ID顺序
    'snowFlakeRedis' => [
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
    ],

    // 定时任务存储路径
    'scheduleFile' => '@console/runtime/schedule/schedule.php',

    // 百度编辑器
    'UEditorUploadDriver' => 'local',

    // 全局上传配置
    'uploaderConfig' => [
        'rootPath' => '@attachment',
        // 图片
        'image' => [
            'httpPrefix' => 'http://www.funboot.com/attachment/', // http前缀
            'originalName' => false, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'driver' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 10,// 图片最大上传大小,默认10M
            'ext' => ["png", "jpg", "jpeg", "gif", "bmp", "ico"],// 可上传图片后缀不填写即为不限
            'path' => 'images/', // 图片创建路径
            'subName' => 'Y/m/d', // 图片上传子目录规则
            'prefix' => 'image_', // 图片名称前缀
            'mimeTypes' => 'image/*', // 媒体类型
            'compress' => false, // 是否开启压缩
            'compressibility' => [ // 100不压缩 值越大越清晰 注意先后顺序
                1024 * 100 => 100, // 0 - 100k 内不压缩
                1024 * 1024 => 30, // 100k - 1M 区间压缩质量到30
                1024 * 1024 * 2  => 20, // 1M - 2M 区间压缩质量到20
                1024 * 1024 * 1024  => 10, // 2M - 1G 区间压缩质量到20
            ],
            // 缩略图
            'thumb' => [
                'path' => 'thumb/',// 图片创建路径
            ],
        ],
        // 文件
        'file' => [
            'httpPrefix' => 'http://www.funboot.com/attachment/', // http前缀
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'driver' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 150,// 最大上传大小,默认150M
            'ext' => [],// 可上传文件后缀不填写即为不限
            'path' => 'files/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'file_',// 名称前缀
            'mimeTypes' => '*', // 媒体类型
            'blacklist' => [ // 文件后缀黑名单
                'php', 'php5', 'php4', 'php3', 'php2', 'php1',
                'java', 'asp', 'jsp', 'jspa', 'javac',
                'py', 'pl', 'rb', 'sh', 'ini', 'svg', 'html', 'jtml','phtml','pht', 'js'
            ],
        ],
        // 视频
        'video' => [
            'httpPrefix' => 'http://www.funboot.com/attachment/', // http前缀
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'driver' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 50,// 最大上传大小,默认50M
            'ext' => ['mp4', 'mp3'],// 可上传文件后缀不填写即为不限
            'path' => 'videos/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'video_',// 名称前缀
            'mimeTypes' => 'video/*', // 媒体类型
        ],
        // 语音
        'voice' => [
            'httpPrefix' => 'http://www.funboot.com/attachment/', // http前缀
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'driver' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 30,// 最大上传大小,默认30M
            'ext' => ['amr', 'mp3'],// 可上传文件后缀不填写即为不限
            'path' => 'voice/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'voice_',// 名称前缀
            'mimeTypes' => 'image/*', // 媒体类型
        ],
    ],

    'funPay' => [
        'adminEmail' => '3375074@qq.com',
    ],


];
