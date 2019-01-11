<?php

namespace app\index\controller;

use think\Db;
use think\Session;
use think\Request;

class Shop extends Base
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

//        关闭session_star
        Session::init([
            'prefix' => 'module',
            'type' => '',
            'auto_start' => false,//先关闭session
        ]);

        if (input('sid')) {
            //登录时发送session_id给前端然后再让前端发送过来

            session_id(input('sid'));

        }
        session_start();
        //下面正常使用session('user_id');就可以的到user_id的值了

    }
	
	public function submit_money($orderid){
		if(Session::get('token') == 1){
			$orderinfo = Db::table('shop_order')->
					where('id',$orderid)->
					where('user_id',Session::get('userid'))->
					select();
					
			if(count($orderinfo)>0){
				header("Content-type:text/html;charset=utf-8");
			
				//引入支付宝支付
				require_once VENDOR_PATH . 'alipay/config.php';
				require_once VENDOR_PATH . 'alipay/wappay/service/AlipayTradeService.php';
				require VENDOR_PATH . 'alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
				
				//商户订单号，商户网站订单系统中唯一订单号，必填
				$out_trade_no = $orderinfo['0']['order_id'];

				//订单名称，必填
				$subject = $orderinfo['0']['order_id'];

				//付款金额，必填
				$total_amount = $orderinfo['0']['price'];

				//商品描述，可空
				$body = $orderinfo['0']['remark'];

				//超时时间
				$timeout_express="1m";

				$payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
				$payRequestBuilder->setBody($body);
				$payRequestBuilder->setSubject($subject);
				$payRequestBuilder->setOutTradeNo($out_trade_no);
				$payRequestBuilder->setTotalAmount($total_amount);
				$payRequestBuilder->setTimeExpress($timeout_express);
				
				$payResponse = new \AlipayTradeService($config);
				$result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

				

			}	

		}
        else{
            Session::set('onlogin',1);
            return "<script>location.href='".BASE_URL."index/login/login'</script>";
        }
	}
	

}