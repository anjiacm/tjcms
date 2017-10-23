<?php
/**
 *  后台菜单
 * 
 * @author        Sim Zhao <326196998@qq.com>
 * @copyright     Copyright (c) 2015. All rights reserved.
 */

class IndexAction extends CAction
{	
	public function run(){
        
        $this->controller->layout = false;
		$this->controller->pageTitle = Yii::t('common','Admin Manage');
		//后台头部一级菜单
		$FirstMenus = array(
//			array('url'=>'', 'name'=>Yii::t('admin','BM_Home')),
//			array('url'=>'', 'name'=>Yii::t('admin','BM_Setting')),
//			array('url'=>'', 'name'=>Yii::t('admin','BM_Catalog')),
//			array('url'=>'', 'name'=>Yii::t('admin','BM_Content')),

//			array('url'=>'', 'name'=>Yii::t('admin','BM_Ad')),
//			array('url'=>'', 'name'=>Yii::t('admin','BM_Component')),
//			array('url'=>'', 'name'=>Yii::t('admin','BM_Model')),
//            array('url'=>'', 'name'=>Yii::t('admin','BM_Oauth')),
//			array('url'=>'', 'name'=>Yii::t('admin','BM_Spider')),
//            array('url'=>'', 'name'=>Yii::t('admin','BM_BackUp')),
//			array('url'=>'', 'name'=>'渠道页'),
            array('url'=>'', 'name'=>'概况'),
            array('url'=>'', 'name'=>'渠道分析'),
            array('url'=>'', 'name'=>'付费分析'),
            array('url'=>'', 'name'=>'ARPU设置'),
            array('url'=>'', 'name'=>'扩展'),
            array('url'=>'', 'name'=>Yii::t('admin','权限管理')),
		);
		//后台左侧二级菜单
		$SecMenus = array(
//			array(
//				array('url'=> $this->controller->createUrl('default/home'), 'name'=>Yii::t('admin','System Home')),
//                array('url'=> $this->controller->createUrl('cache/index'), 'name'=>Yii::t('admin','Cache Manage')),
//			),
//			array(
//				array('url'=> $this->controller->createUrl('setting/index'), 'name'=>Yii::t('admin','Web Set')),
//				array('url'=> $this->controller->createUrl('setting/seo'), 'name'=>Yii::t('admin','SEO Set')),
//				array('url'=> $this->controller->createUrl('setting/template'), 'name'=>Yii::t('admin','Template Set')),
//				array('url'=> $this->controller->createUrl('setting/email'), 'name'=>Yii::t('admin','Email Set')),
//				array('url'=> $this->controller->createUrl('setting/cache'), 'name'=>Yii::t('admin','Cache Set')),
//				array('url'=> $this->controller->createUrl('setting/access'), 'name'=>Yii::t('admin','Access Set')),
//				array('url'=> $this->controller->createUrl('setting/custom'), 'name'=>Yii::t('admin','Custom Set')),
//			),
			/*array(
                array('url'=> $this->controller->createUrl('catalog/index'), 'name'=>Yii::t('admin','Catalog Manage')),
                array('url'=> $this->controller->createUrl('menu/index'), 'name'=>Yii::t('admin','Menu Manage')),
                array('url'=> $this->controller->createUrl('special/index'), 'name'=>Yii::t('admin','Special Manage')),					
			),
			array(
                array('url'=> $this->controller->createUrl('post/index'), 'name'=>Yii::t('admin','Article Manage')),
                array('url'=> $this->controller->createUrl('album/index'), 'name'=>Yii::t('admin','Image Manage')),
                array('url'=> $this->controller->createUrl('soft/index'), 'name'=>Yii::t('admin','Soft Manage')),
                array('url'=> $this->controller->createUrl('video/index'), 'name'=>Yii::t('admin','Video Manage')),				
                array('url'=> $this->controller->createUrl('page/index'), 'name'=>Yii::t('admin','Page Manage')),                    
                array('url'=> $this->controller->createUrl('recommend/index'), 'name'=>Yii::t('admin','Recommend Manage')),
                array('url'=> $this->controller->createUrl('recommendPosition/index'), 'name'=>Yii::t('admin','RecommendPosition Manage')),
			),*/

			/*array(
                array('url'=> $this->controller->createUrl('ad/index'), 'name'=>Yii::t('admin','Ads Manage')),
                array('url'=> $this->controller->createUrl('adPosition/index'), 'name'=>Yii::t('admin','Adposition Manage')),				
			),
			array(	
                array('url'=> $this->controller->createUrl('link/index'), 'name'=>Yii::t('admin','Link Manage')),
                array('url'=> $this->controller->createUrl('comment/index'), 'name'=>Yii::t('admin','Comment Manage')),
                array('url'=> $this->controller->createUrl('reply/index'), 'name'=>Yii::t('admin','Reply Manage')),
                array('url'=> $this->controller->createUrl('tag/index'), 'name'=>Yii::t('admin','Tags Manage')),
                array('url'=> $this->controller->createUrl('tag/words'), 'name'=>Yii::t('admin','Words Manage')),
                array('url'=> $this->controller->createUrl('maillog/index'), 'name'=>Yii::t('admin','Maillog Manage')),
			),
			array(
                array('url'=> $this->controller->createUrl('modeltype/index'), 'name'=>Yii::t('admin','Modeltype Manage')),					
			),
            array(
                array('url'=> $this->controller->createUrl('oAuth/index'), 'name'=>Yii::t('admin','OAuth Manage')),					
			),			
            array(
                array('url'=> $this->controller->createUrl('spider/index'), 'name'=>Yii::t('admin','Spider Setting')),
                array('url'=> $this->controller->createUrl('spider/post'), 'name'=>Yii::t('admin','Spider Post')),
                array('url'=> $this->controller->createUrl('spider/image'), 'name'=>Yii::t('admin','Spider Image')),
                array('url'=> $this->controller->createUrl('spider/soft'), 'name'=>Yii::t('admin','Spider Soft')),
                array('url'=> $this->controller->createUrl('spider/video'), 'name'=>Yii::t('admin','Spider Video')),
			),
            array(
                array('url'=> $this->controller->createUrl('database/index'), 'name'=>Yii::t('admin','Database Manage')),					
                array('url'=> $this->controller->createUrl('zip/index'), 'name'=>Yii::t('admin','Zip Manage')),					
			),*/
//			array(
//				array('url'=> $this->controller->createUrl('PhChannel/index'), 'name'=>'渠道列表'),
//				array('url'=> $this->controller->createUrl('PhApplication/index'), 'name'=>'应用列表'),
//				array('url'=> $this->controller->createUrl('PhChannelPage/index'), 'name'=>'渠道页列表'),
//				array('url'=> $this->controller->createUrl('PhStatistics/index'), 'name'=>'统计列表'),
//				array('url'=> $this->controller->createUrl('PhPost/index'), 'name'=>'文章列表'),
//				array('url'=> $this->controller->createUrl('PhSpecial/index'), 'name'=>'专题列表'),
//				array('url'=> $this->controller->createUrl('PhTheme/index'), 'name'=>'模版列表'),
//				array('url'=> $this->controller->createUrl('PhPlayactor/index'), 'name'=>'演员列表'),
////				array('url'=> $this->controller->createUrl('macloading/index'), 'name'=>'内容页列表'),
//			), array('url'=> $this->controller->createUrl('VipLog/dingdan'), 'name'=>'订单统计'),
            array(
                array('url'=> $this->controller->createUrl('WuTjuserall/index'), 'name'=>'实时统计'),
                array('url'=> $this->controller->createUrl('WuTjuserall/ztqs'), 'name'=>'整体趋势'),
            ),
            array(
                array('url'=> $this->controller->createUrl('WuTjuserall/qudao'), 'name'=>'渠道列表'),
                array('url'=> $this->controller->createUrl('WuTjuserall/qdlist'), 'name'=>'渠道详情'),
            ),
            array(
                array('url'=> $this->controller->createUrl('WuTjuserall/payindex'), 'name'=>'付费趋势'),
                array('url'=> $this->controller->createUrl('WuTjuserall/paychang'), 'name'=>'付费转化'),
                array('url'=> $this->controller->createUrl('WuTjuserall/paymoney'), 'name'=>'付费习惯'),
            ),
            array(
                array('url'=> $this->controller->createUrl('WuDayarpu/index'), 'name'=>'ARPU设置'),

            ),
            array(
                array('url'=> $this->controller->createUrl('WuTjuserall/kzz'), 'name'=>'渠道汇总'),
                array('url'=> $this->controller->createUrl('WuTjuserall/kzqd'), 'name'=>'联盟渠道列表'),
                array('url'=> $this->controller->createUrl('WuTjuserall/editindex'), 'name'=>'影片数据分析'),
            ),
            array(
                array('url'=> $this->controller->createUrl('user/index'), 'name'=>Yii::t('admin','User List')),
                // array('url'=> $this->controller->createUrl('user/admin'), 'name'=>Yii::t('admin','Admin List')),
                array('url'=> $this->controller->createUrl('userGroup/index'), 'name'=>Yii::t('admin','Group Manage')),
                // array('url'=> $this->controller->createUrl('question/index'), 'name'=>Yii::t('admin','Question List')),
            ),
		);
		
		//只显示授权的菜单
		$groupid = Yii::app()->user->groupid;
		$group = UserGroup::model()->findByPk($groupid);
        $OAuthSecMenus = array();
		if($groupid != User::AdminGroupID){
			//非超级管理员
			$acls = explode(',', $group->acl);
			$loginkey = array_search('default|login', $acls); //删除login授权
			$acls[$loginkey] = 'default|home';
			foreach($acls as $av){
				$av = str_replace('|', '/', $av);
				$tmpacls[] = $this->controller->createUrl($av);				
			}
            
			foreach($SecMenus as $sk => $sv){
				foreach($sv as $sek => $sev){                    
					if(in_array($sev['url'], $tmpacls)){
						$OAuthSecMenus[$sk][$sek] = $sev;
					}
				}
				if(!isset($OAuthSecMenus[$sk]) || !$OAuthSecMenus[$sk]){
					unset($FirstMenus[$sk]);
				}
			}			
		}else{
			$OAuthSecMenus = $SecMenus;
		}
        
		//取左侧菜单第一个菜单作为头部菜单的链接
		foreach($FirstMenus as $key=>$val){
            if(isset($OAuthSecMenus[$key]) && $OAuthSecMenus[$key]) {
               $firstUrl = reset($OAuthSecMenus[$key]);
               $FirstMenus[$key]['url'] = $firstUrl['url'];
            }
		}
		
		$this->controller->render('index', array('FirstMenus' => $FirstMenus, 'SecMenus' => $OAuthSecMenus));
	}
}