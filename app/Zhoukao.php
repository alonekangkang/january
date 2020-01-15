<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zhoukao extends Model
{
    //定义主键
    public $primaryKey ='zhou_id';
    //指定可以添加入库的字段并不能为空
    protected $fillable = ['test_id','appid','secret'];
    //指定链接表名
    protected $table = 'zhoukao';
    //时间戳
    public $timestamps = false;

}