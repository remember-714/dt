<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Reg extends Controller
{
    public function index()
    {
    	header("Content-type: text/html; charset=utf-8"); 
    	$name=trim(htmlentities(request()->param('name')));
    	$openid=htmlentities(request()->param('openid'));
      
    	$yx=htmlentities(request()->param('yx'));
    	
      if(empty($name))
    	{
    		echo "<script>alert('姓名不能为空');history.back();</script>";exit;
    	}
      $is_res=db('user')->where('openid',$openid)->find();
      if($is_res)
      {
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
      //$openid=mt_rand(1000000,99999999);
    	$data = [         
                      
                      'name' => $name, 
                      //'tel' =>$tel,  
                      'yx'=>$yx,
                      'openid'=>$openid
                      
                      
                  ];

           if(db('user')->insert($data))
	  		 {        //添加数据  
                echo "<script>alert('登记成功');location.href='../index/start/?openid=$openid'</script>";exit; //成功
            }else{  
                echo "<script>alert('登记失败');history.back();</script>";exit;  
            }         





    }
    public function test()
    {
    	echo "测试方法";
    }
}
