<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tests extends Model
{
    //定义主键
    public $primaryKey ='test_id';
    //指定可以添加入库的字段并不能为空
    protected $fillable = ['u_name','u_pwd'];
    //指定链接表名
    protected $table = 'test';
    //时间戳
    public $timestamps = false;

}
