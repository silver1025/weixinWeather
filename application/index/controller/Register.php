<?php
namespace app\index\controller;
 
use think\Controller;
 
class Register extends Controller
{
    public function index()
    {
    	return $this->fetch();
    }   

    // 处理登录逻辑
    public function doRegister()
    {
    	$param = input('post.');
    	if(empty($param['user_name'])){
    		
    		$this->error('用户名不能为空');
    	}
    	
    	if(empty($param['user_pwd1'])){
    		
    		$this->error('密码不能为空');
    	}
      
     	if(empty($param['user_pwd2'])){
    		
    		$this->error('请输入确认密码');
    	}
      
      	if($param['user_pwd1']!=$param['user_pwd2']){
    		
    		$this->error('密码不一致');
    	}
      
    	//用户名是否已存在
        $has = db('users')->where('user_name', $param['user_name'])->find();
      	if(!empty($has)){
    		$this->error('用户名已存在');
    	}
      
    	// 插入数据
      	$data = ['user_name' =>$param['user_name'], 'user_pwd' =>md5($param['user_pwd1'])];
		db('users')->insert($data);
    	
    	// 记录用户登录信息
      	$has = db('users')->where('user_name', $param['user_name'])->find();
    	cookie('user_id', $has['id'], 3600);  // 一个小时有效期
    	cookie('user_name', $has['user_name'], 3600);
    	
    	$this->redirect(url('index/index'));
    }
 }