<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Dt extends Controller
{
    public function index()
    {
    	header("Content-type: text/html; charset=utf-8"); 
    	$openid=input('openid');
    	$num = 10;    //需要抽取的默认条数
    	$table = 'tk';    //需要抽取的数据表
    	$countcus = Db::name($table)->count();    //获取总记录数
    	$min = Db::name($table)->min('id');    //统计某个字段最小数据
	    if($countcus < $num){$num = $countcus;}
	    $i = 1;
	    $flag = 0;
	    $ary = array();
	    while($i<=$num){
	        $rundnum = rand($min, $countcus);//抽取随机数
	        if($flag != $rundnum){
	            //过滤重复 
	            if(!in_array($rundnum,$ary)){
	                $ary[] = $rundnum;
	                $flag = $rundnum;
	            }else{
	                $i--;
	            }
	            $i++;
	        }
	    }
	    //$res = Db::name($table)->limit(25)->select();
    $res = Db::name($table)->where('id','in',$ary,'or')->select();
    //print_r($res);die;
    $this->assign('res',$res);

    $this->assign('num',$num);
    
	$this->assign('openid',$openid);


        return $this->fetch('dt');
    }
    public function test()
    {
    	echo "测试方法";
    }
}
