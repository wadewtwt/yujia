<?php
namespace app\admin\model;

use think\Model;

class CommonModel extends Model{
    const URL_CREATE = 'create';
    const URL_UPDATE = 'update';

    const TITLE_CREATE = '新建';
    const TITLE_UPDATE = '修改';

    // 整合到更新和新建中
    public static $action = [
        'create' => [
            'url' => self::URL_CREATE,
            'title' => self::TITLE_CREATE
        ],
        'update' => [
            'url' => self::URL_UPDATE,
            'title' => self::TITLE_UPDATE
        ]
    ];
}