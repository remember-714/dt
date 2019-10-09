<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
define("TOKEN", "weixin");

class Index extends Controller
{
   public function index()
   {
   	

			
		return $this->fetch('reg');


    }



public function reg()
{



	$openid=input('openid');


	$res=Db::table('user')->where("openid='$openid'")->find();
	if($res['openid']==$openid)
	{
		
		echo "<script>location.href='../index/start/?openid=$openid'</script>";exit; //成功
		
		
	}else{
		$this->assign('openid',$openid);
		return $this->fetch();
	}
	
	
}
public function start()
{
	$openid=input('openid');
	$res=Db::table('user')->where("openid='$openid'")->find();
	if($res['openid']==$openid)
	{
		if(empty($res['score'])){
	
			$this->assign('openid',$openid);
			return $this->fetch('index');
		}else{
			$res=db('user')->where('openid',$openid)->find();
			$res1=db('user')->where('score','neq',"")->order('score desc')->limit(5)->select();
			
			$z_res=db('user')->field('score')->order('score desc')->select();//计算排名
			$sum=count($z_res); //总人数
			$z_sc=array();
			foreach ($z_res as $k => $v) {
				array_push($z_sc,$v['score']);
			}
			$userpm=array_search($res['score'],$z_sc);
			$userpm++;

			$content=mmpy($res['score']);
			$this->assign('name',$res['name']);
    		$this->assign('score',$res['score']);
    		$this->assign('ts',$res['ts']);
    		$this->assign('content',$content);
    		$this->assign('res1',$res1);
    		$this->assign('userpm',$userpm);
    		$this->assign('sum',$sum);
    		$this->assign('openid',$openid);
    		return $this->fetch('zf/index');
		}
	}else{
		echo "<script>location.href='../index/reg/?openid=$openid'</script>";exit;
	}
	
}
 
public function adel()
{
	$openid=input('openid');
	//print_r($openid);die;
	db('user')->where('openid',$openid)->delete();
}






}
