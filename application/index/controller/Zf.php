<?php
namespace app\index\controller;
use think\Controller;
class Zf extends Controller
{
    public function index()
    {
    	$score=request()->param('score');
    	$ts=request()->param('ts');
        $openid=request()->param('openid');

        $content=mmpy($score);

        db('user')->where('openid',$openid)->update(['score' =>$score,'ts'=>$ts]);

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
    	$this->assign('score',$score);
    	$this->assign('ts',$ts);
        $this->assign('name',$res['name']);
        $this->assign('content',$content);
        $this->assign('res1',$res1);
        $this->assign('userpm',$userpm);
        $this->assign('sum',$sum);
        $this->assign('openid',$openid);
        return $this->fetch();
    }
    public function test()
    {
    	echo "测试方法";
    }
}
