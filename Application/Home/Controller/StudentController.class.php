<?php
namespace Home\Controller;
use Think\Controller;
class StudentController extends Controller {

	public function showPhotoPassageList(){
		$type = I('path.2','','');
	    $photo_passages = M('photo_passages'); // 实例化Data数据对象  date 是你的表名
	    $count = $photo_passages->where("type = '{$type}'")->count();// 查询满足要求的总记录数 $map表示查询条件
	    $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数
	    $show = $Page->show();// 分页显示输出

	    // 进行分页数据查询
	    $list = $photo_passages->where("type = '{$type}'")->order('time desc')->page(I('path.3','','').',10')->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条

	    //去除内容的html标记
	    for($i=0;$i<count($list);$i++){
	    	$list[$i]['content'] = strip_tags($list[$i]['content']);
	    }

	    $this->assign('list',$list);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	    //var_dump($list1);

	   	$notice = M('notice');
		$notice_data = $notice->order('istop desc,time desc')->where("type='0'")->limit(5)->select();
		$notice_study = $notice->order('istop desc,time desc')->where("type='1'")->limit(5)->select();
		
		$this->assign('type',I('path.2'));
	    $this->assign('notice',$notice_data);
	    $this->assign('study',$notice_study);
	    $this->display(); // 输出模板
	}

	public function showPhotoPassage(){
		$notice = M('notice');
		$news = M('news');
		$photo_passages = M('photo_passages');
		$id = I('path.2','','');
		$result = $photo_passages->find($id);

		$max = $photo_passages->max('id');
		$result['pre'] = ($id-1<=0)?$id:$id-1;
		$result['next'] = ($id+1>$max)?$max:$id+1;

		$notice_data = $notice->order('istop desc,time desc')->where("type='0'")->limit(5)->select();
		$notice_study = $notice->order('istop desc,time desc')->where("type='1'")->limit(5)->select();
		$news_data = $news->order('time desc')->limit(0,3)->select();

		$this->assign('notice',$notice_data);
	    $this->assign('study',$notice_study);
		$this->assign('news',$news_data);
		$this->assign('result',$result);
		$this->display();
    }

}