<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 12:40
 */
function build_form($url, $model) {
    $sHtml = "<!DOCTYPE html><html><head><title>Waiting...</title>";
    $sHtml .= "<meta http-equiv='content-type' content='text/html;charset=utf-8'></head>
	    <body><form id='submit' name='submit' action='" . $url . "' method='POST'>";
    foreach ($model as $key => $value) {
        $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $value . "' style='width:90%;'/>";
    }
    $sHtml .= "</form>正在提交信息...";
    $sHtml .= "<script>document.forms['submit'].submit();</script></body></html>";
    return $sHtml;
}
$str = '<Ips><GateWayRsp><head><ReferenceID></ReferenceID><RspCode>000000</RspCode><RspMsg><![CDATA[交易成功！]]></RspMsg><ReqDate>20180507104921</ReqDate><RspDate>20180507105032</RspDate><Signature>20fcf99c83488202d1fb090f31d6f673</Signature></head><body><MerBillNo>2018050799485710</MerBillNo><CurrencyType>156</CurrencyType><Amount>3000</Amount><Date>20180507</Date><Status>Y</Status><Msg><![CDATA[支付成功！]]></Msg><Attach><![CDATA[3656]]></Attach><IpsBillNo>BO20180507104844074825</IpsBillNo><IpsTradeNo>2018050710492136189</IpsTradeNo><RetEncodeType>17</RetEncodeType><BankBillNo>2018050742106710006449410101400</BankBillNo><ResultType>0</ResultType><IpsBillTime>20180507104922</IpsBillTime></body></GateWayRsp></Ips>';
$ips_data['paymentResult'] = $str;

$sand_data = [
    'sign' => 'mtLbA5pKhEnBDTi1eZLhT7lJv8VWbeQqgR/97Pvg1GuTqIBk7Berecq9BaiHEurAqLnOijlAEUFk4I0hCbeUhlur8U0PWDUMhyt+3OgnrUFmopTxQEkORCA43WV2a1AgndOkElK+4gGr94QyVVSb5ifa+mzLtU2IMpUniQ8KlrXuTAfgPKLrLYCeCuQAcA7MoREAKyZgEDokk/tP8MlgurdDY0K2GDiD+duTv4sIMokIR5jXlhL3r/56R2s9bOEndTDiSvLy9q/fChwR74aSkW0WmJfW3pgHDtjL6gNUqBAX8JwkwWl4P1MAWrBf5lLkTtevc2HsnuwTjBAmqyGwOg==',
    'extend' => '',
    'signType' => '01',
    'data' => '{\\"head\\":{\\"respTime\\":\\"20180427181124\\",\\"version\\":\\"1.0\\",\\"respCode\\":\\"000000\\"},\\"body\\":{\\"clearDate\\":\\"20180427\\",\\"tradeNo\\":\\"2018042718105907851019933175\\",\\"payTime\\":\\"20180427181059\\",\\"accNo\\":\\"\\",\\"midFee\\":\\"000000000020\\",\\"mid\\":\\"19781953\\",\\"orderStatus\\":\\"1\\",\\"totalAmount\\":\\"000000000001\\",\\"buyerPayAmount\\":\\"000000000001\\",\\"bankserial\\":\\"\\",\\"orderCode\\":\\"2018042750525498\\",\\"discAmount\\":\\"000000000000\\"}}',
    'charset' => 'UTF-8',
];

$union_data = [
    'accessType' => '0',
    'bizType' => '000201',
    'currencyCode' => '156',
    'encoding' => 'utf-8',
    'merId' => '826430153110025',
    'orderId' => '2018080821331699975150',
    'queryId' => '541808082133169806058',
    'respCode' => '00',
    'respMsg' => '成功[0000000]',
    'settleAmt' => '10000',
    'settleCurrencyCode' => '156',
    'settleDate' => '0808',
    'signMethod' => '01',
    'signPubKeyCert' => '-----BEGIN CERTIFICATE-----
MIIEIDCCAwigAwIBAgIFEDRVM3AwDQYJKoZIhvcNAQEFBQAwITELMAkGA1UEBhMC
Q04xEjAQBgNVBAoTCUNGQ0EgT0NBMTAeFw0xNTEwMjcwOTA2MjlaFw0yMDEwMjIw
OTU4MjJaMIGWMQswCQYDVQQGEwJjbjESMBAGA1UEChMJQ0ZDQSBPQ0ExMRYwFAYD
VQQLEw1Mb2NhbCBSQSBPQ0ExMRQwEgYDVQQLEwtFbnRlcnByaXNlczFFMEMGA1UE
Aww8MDQxQDgzMTAwMDAwMDAwODMwNDBA5Lit5Zu96ZO26IGU6IKh5Lu95pyJ6ZmQ
5YWs5Y+4QDAwMDE2NDkzMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
tXclo3H4pB+Wi4wSd0DGwnyZWni7+22Tkk6lbXQErMNHPk84c8DnjT8CW8jIfv3z
d5NBpvG3O3jQ/YHFlad39DdgUvqDd0WY8/C4Lf2xyo0+gQRZckMKEAId8Fl6/rPN
HsbPRGNIZgE6AByvCRbriiFNFtuXzP4ogG7vilqBckGWfAYaJ5zJpaGlMBOW1Ti3
MVjKg5x8t1/oFBkpFVsBnAeSGPJYrBn0irfnXDhOz7hcIWPbNDoq2bJ9VwbkKhJq
Vz7j7116pziUcLSFJasnWMnp8CrISj52cXzS/Y1kuaIMPP/1B0pcjVqMNJjowooD
OxID3TZGfk5V7S++4FowVwIDAQABo4HoMIHlMB8GA1UdIwQYMBaAFNHb6YiC5d0a
j0yqAIy+fPKrG/bZMEgGA1UdIARBMD8wPQYIYIEchu8qAQEwMTAvBggrBgEFBQcC
ARYjaHR0cDovL3d3dy5jZmNhLmNvbS5jbi91cy91cy0xNC5odG0wNwYDVR0fBDAw
LjAsoCqgKIYmaHR0cDovL2NybC5jZmNhLmNvbS5jbi9SU0EvY3JsMjI3Mi5jcmww
CwYDVR0PBAQDAgPoMB0GA1UdDgQWBBTEIzenf3VR6CZRS61ARrWMto0GODATBgNV
HSUEDDAKBggrBgEFBQcDAjANBgkqhkiG9w0BAQUFAAOCAQEAHMgTi+4Y9g0yvsUA
p7MkdnPtWLS6XwL3IQuXoPInmBSbg2NP8jNhlq8tGL/WJXjycme/8BKu+Hht6lgN
Zhv9STnA59UFo9vxwSQy88bbyui5fKXVliZEiTUhjKM6SOod2Pnp5oWMVjLxujkk
WKjSakPvV6N6H66xhJSCk+Ref59HuFZY4/LqyZysiMua4qyYfEfdKk5h27+z1MWy
nadnxA5QexHHck9Y4ZyisbUubW7wTaaWFd+cZ3P/zmIUskE/dAG0/HEvmOR6CGlM
55BFCVmJEufHtike3shu7lZGVm2adKNFFTqLoEFkfBO6Y/N6ViraBilcXjmWBJNE
MFF/yA==
-----END CERTIFICATE-----',
    'traceNo' => '980605',
    'traceTime' => '0808213316',
    'txnAmt' => '10000',
    'txnSubType' => '01',
    'txnTime' => '20180808213316',
    'txnType' => '01',
    'version' => '5.1.0',
    'signature' => 'JVZzxcwljcaLCcxUkkOWJ8Ao4Mwuv6RwTVhC7Bf3fAjx8lmFAVM97WJRIGQbsRjTbr2CwlzYx9DLhAubUlU0EMpAAJ950NaeHFtgK0DtZ4Wpj/KOKveIAZ8puZG59MG+vmH7BjSxl16H84xixOyTwtal+2fXF6iuik4nHRctTnE68enBInI0VUFnpiA65IDBFu8RCQmRwpEBwbD/59RA0ZcM/gxAm8yl/EPdJ7x9Kl1YALYsCPJk7WJtbJKnV2lF2u2lnuRuxk9qgYwqWs7J9wNtC1G1IGzCMlfLVGoP12v21QzP7dVls/vpVG8ZUnW5j8GwuCxsQhPDb1NPIm5oCg=='
];
$str = '{"merchantId":"2120180426151349001","responseMode":"1","orderId":"20180926110628","unsTransId":"201809265035730","currencyType":"CNY","amount":"0.01","returnCode":"0000","returnMessage":"","successful":"true","paid":"false","bankCode":"unionpay_cq","mac":"A9CE657A884AD3EF58D20AE3B9638E38"}';
$uns_data = json_decode($str, true);

$yse_data = [
    'sign' => 'Ra+qebyRU/fCEZu1PGolZ84afvF4fZoAXmOMINbPFpg0qtWT2YxYhAwWgNvFa+vjQ86YCGsVICF7uW0Crand3g38TUoohRqAdYEZSUJ1RVchmWLw0Q2dU9PbEVFQZvrK4NOCiVu5K/w4FNJYz343rQBiIhf9ygeAseOtaoopH3I=',
    'total_amount' => '1.00',
    'trade_no' => '01O180612290697618',
    'notify_time' => '2018-06-12 15:32:33',
    'account_date' => '20180612',
    'sign_type' => 'RSA',
    'notify_type' => 'directpay.status.sync',
    'out_trade_no' => '2018061299564957',
    'trade_status' => 'TRADE_SUCCESS',
];

$str = '{"p1_MerId":"10022931198","r1_Code":"1","r2_TrxId":"218701652802322A","r3_Amt":"0.01","r4_Cur":"RMB","r5_Pid":"recharge","r6_Order":"2018092614254153575551","r7_Uid":"","r8_MP":"1537943141000456","r9_BType":"2","rb_BankId":"NUCC-NET","ro_BankOrderId":"2018092630000003361888040102501","rp_PayDate":"20180926142627","rq_CardNo":"","ru_Trxtime":"20180926142627","rq_SourceFee":"0.00","rq_TargetFee":"0.00","hmac_safe":"09d4ed1abb767f41dfef951d265e4ad2","hmac":"ccbaaec323fd4c0fa87c945f4d6c45d9","r0_Cmd":"Buy"}';
$yee_data = json_decode($str,true);
$form = build_form('notify.php?action=ips',$ips_data);
echo $form;
