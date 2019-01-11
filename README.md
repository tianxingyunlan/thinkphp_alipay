# thinkphp_alipay

使用thinkphp5.0调用支付宝接口实现手机网站支付宝支付功能

具体操作可参考（https://my.oschina.net/marhal/blog/1787739?p=1#comments）

简要操作：

1、从支付宝开发文档中下载的官方demo，命名为alipay，包含接口调用和测试页面

2、将notify_url.php文件改为Notifyurl模块（功能：支付宝服务器异步通知页面，检验提交订单信息是否有效）

3、将return_url.php文件改为Returnurl模块（功能：支付宝页面跳转同步通知页面，订单付款返回的后续操作）

4、在Shop模块中获取订单信息，包括商户订单号、订单名称、付款金额等等

5、在alipay/config.php文件中添加收款账号和服务器公钥等等。
