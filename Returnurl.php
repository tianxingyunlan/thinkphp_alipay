<?php

namespace app\index\controller;

use think\Db;
use think\Session;
use think\Request;

class Returnurl extends Base
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

//        关闭session_star
        Session::init([
            'prefix'         => 'module',
            'type'           => '',
            'auto_start'     => false,//先关闭session
        ]);

        if (input('sid')) {
            //登录时发送session_id给前端然后再让前端发送过来

            session_id(input('sid'));

        }
        session_start();
        //下面正常使用session('user_id');就可以的到user_id的值了

    }

    public function returnurl()
    {
			require_once VENDOR_PATH . 'alipay/config.php';
			require_once VENDOR_PATH . 'alipay/wappay/service/AlipayTradeService.php';
			
			$arr=$_GET;
			$alipaySevice = new \AlipayTradeService($config); 
			$result = $alipaySevice->check($arr);

			/* 实际验证过程建议商户添加以下校验。
			1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
			2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
			3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
			4、验证app_id是否为该商户本身。
			*/
			if($result) {//验证成功
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//请在这里加上商户的业务逻辑程序代码
				
				//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
				//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

				//商户订单号

				$out_trade_no = htmlspecialchars($_GET['out_trade_no']);

				//支付宝交易号

				$trade_no = htmlspecialchars($_GET['trade_no']);
					
				Db::table('shop_order')->
					where('order_id',$_GET['out_trade_no'])->
					where('user_id',Session::get('userid'))->
					update(['state'=>'2']);
				return "<script>location.href='".BASE_URL."index/buy/buy/1'</script>";
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
			else {
				//验证失败
				echo "验证失败";
			}
    }
}
