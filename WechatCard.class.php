<?php
/*
 *WeixinCard：微信卡券类
 *用于微信的开发接口类：
 *@author    teemo君
 *@mail    1301582878@qq.com
 *@blog 	 www.dtuzi.com
 *@version 	 1.0
 *@date 	2016-01-13
 */
require_once('weixinjssdkporttest.php');
class WechatCard extends JSSDK{

    private $_weixin_uploadimg_path  = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg';//卡券LOGO素材上传地址
    private $_weixin_card_path  = 'https://api.weixin.qq.com/card/';//卡券管理地址
    private $_weixin_up_code_path  = 'http://api.weixin.qq.com/card/code/deposit';//卡券CODE上传地址
    private $_weixin_card_modifystock_path  = 'https://api.weixin.qq.com/card/modifystock';//修改卡券CODE库存接口
    private $_weixin_code_get_path  = 'https://api.weixin.qq.com/card/code/get';//查询CODE
    private $_weixin_code_consume_path  = 'https://api.weixin.qq.com/card/code/consume';//核销CODE
    //private $_weixin_card_get  = 'https://api.weixin.qq.com/card/get';//查看卡券详情
    private $_weixin_getdepositcount  = 'http://api.weixin.qq.com/card/code/getdepositcount';//查询导入code数目接口

    private $_weixin_errorCode = array(
        '-1'=>'系统繁忙，此时请开发者稍候再试',
        '0'=>'请求成功',
        '40001'=>'获取 access_token 时AppSecret错误，或者 access_token 无效',
        '40002'=>'不合法的凭证类型',
        '40003'=>'不合法的OpenID',
        '40004'=>'不合法的媒体文件类型',
        '40005'=>'不合法的文件类型',
        '40006'=>'不合法的文件大小',
        '40007'=>'不合法的媒体文件id',
        '40008'=>'不合法的消息类型',
        '40009'=>'不合法的图片文件大小',
        '40010'=>'不合法的语音文件大小',
        '40011'=>'不合法的视频文件大小',
        '40012'=>'不合法的缩略图文件大小',
        '40013'=>'不合法的AppID',
        '40014'=>'不合法的access_token',
        '40015'=>'不合法的菜单类型',
        '40016'=>'不合法的按钮个数',
        '40017'=>'不合法的按钮个数',
        '40018'=>'不合法的按钮名字长度',
        '40019'=>'不合法的按钮KEY长度',
        '40020'=>'不合法的按钮URL长度',
        '40021'=>'不合法的菜单版本号',
        '40022'=>'不合法的子菜单级数',
        '40023'=>'不合法的子菜单按钮个数',
        '40024'=>'不合法的子菜单按钮类型',
        '40025'=>'不合法的子菜单按钮名字长度',
        '40026'=>'不合法的子菜单按钮KEY长度',
        '40027'=>'不合法的子菜单按钮URL长度',
        '40028'=>'不合法的自定义菜单使用用户',
        '40029'=>'不合法的oauth_code',
        '40030'=>'不合法的refresh_token',
        '40031'=>'不合法的openid列表',
        '40032'=>'不合法的openid列表长度',
        '40033'=>'不合法的请求字符，不能包含\uxxxx格式的字符',
        '40035'=>'不合法的参数',
        '40038'=>'不合法的请求格式',
        '40039'=>'不合法的URL长度',
        '40050'=>'不合法的分组id',
        '40051'=>'分组名字不合法',
        '40117'=>'分组名字不合法',
        '40118'=>'media_id大小不合法',
        '40119'=>'button类型错误',
        '40120'=>'button类型错误',
        '40121'=>'不合法的media_id类型',
        '40130'=>'至少需要同时发送两个openid',
        '40132'=>'微信号不合法',
        '40137'=>'不支持的图片格式',
        '41001'=>'缺少access_token参数',
        '41002'=>'缺少appid参数',
        '41003'=>'缺少refresh_token参数',
        '41004'=>'缺少secret参数',
        '41005'=>'缺少多媒体文件数据',
        '41006'=>'缺少media_id参数',
        '41007'=>'缺少子菜单数据',
        '41008'=>'缺少oauth code',
        '41009'=>'缺少openid',
        '42001'=>'access_token超时',
        '42002'=>'refresh_token超时',
        '42003'=>'oauth_code超时',
        '43001'=>'需要GET请求',
        '43002'=>'需要POST请求',
        '43003'=>'需要HTTPS请求',
        '43004'=>'需要接收者关注',
        '43005'=>'需要好友关系',
        '44001'=>'多媒体文件为空',
        '44002'=>'POST的数据包为空',
        '44003'=>'图文消息内容为空',
        '44004'=>'文本消息内容为空',
        '45001'=>'多媒体文件大小超过限制',
        '45002'=>'消息内容超过限制',
        '45003'=>'标题字段超过限制',
        '45004'=>'描述字段超过限制',
        '45005'=>'链接字段超过限制',
        '45006'=>'图片链接字段超过限制',
        '45007'=>'语音播放时间超过限制',
        '45008'=>'图文消息超过限制',
        '45009'=>'接口调用超过限制',
        '45010'=>'创建菜单个数超过限制',
        '45015'=>'回复时间超过限制',
        '45016'=>'系统分组，不允许修改',
        '45017'=>'分组名字过长',
        '45018'=>'分组数量超过上限',
        '46001'=>'不存在媒体数据',
        '46002'=>'不存在的菜单版本',
        '46003'=>'不存在的菜单数据',
        '46004'=>'不存在的用户',
        '47001'=>'解析JSON/XML内容错误',
        '48001'=>'api功能未授权',
        '50001'=>'用户未授权该api',
        '50002'=>'用户受限，可能是违规后接口被封禁',
        '61451'=>'参数错误(invalid parameter)',
        '61452'=>'无效客服账号(invalid kf_account)',
        '61453'=>'客服帐号已存在(kf_account exsited)',
        '61454'=>'客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)(invalid kf_acount length)',
        '61455'=>'客服帐号名包含非法字符(仅允许英文+数字)(illegal character in kf_account)',
        '61456'=>'客服帐号个数超过限制(10个客服账号)(kf_account count exceeded)',
        '61457'=>'无效头像文件类型(invalid file type)',
        '61450'=>'系统错误(system error)',
        '61500'=>'日期格式错误',
        '61501'=>'日期范围错误',
        '9001001'=>'POST数据参数不合法',
        '9001002'=>'远端服务不可用',
        '9001003'=>'Ticket不合法',
        '9001004'=>'获取摇周边用户信息失败',
        '9001005'=>'获取商户信息失败',
        '9001006'=>'获取OpenID失败',
        '9001007'=>'上传文件缺失',
        '9001008'=>'上传素材的文件类型不合法',
        '9001009'=>'上传素材的文件尺寸不合法',
        '9001010'=>'上传失败',
        '9001020'=>'帐号不合法',
        '9001021'=>'已有设备激活率低于50%，不能新增设备',
        '9001022'=>'设备申请数不合法，必须为大于0的数字',
        '9001023'=>'已存在审核中的设备ID申请',
        '9001024'=>'一次查询设备ID数量不能超过50',
        '9001025'=>'设备ID不合法',
        '9001026'=>'页面ID不合法',
        '9001027'=>'页面参数不合法',
        '9001028'=>'一次删除页面ID数量不能超过10',
        '9001029'=>'页面已应用在设备中，请先解除应用关系再删除',
        '9001030'=>'一次查询页面ID数量不能超过50',
        '9001031'=>'时间区间不合法',
        '9001032'=>'保存设备与页面的绑定关系参数错误',
        '9001033'=>'门店ID不合法',
        '9001034'=>'设备备注信息过长',
        '9001035'=>'设备申请参数不合法',
        '9001036'=>'查询起始值begin不yy',
        '40094' => '参数不正确，请检查json 字段',
        '65104' => '门店的类型不合法，必须严格按照附表的分类填写',
        '65105' => '图片url 不合法，必须使用接口1 的图片上传接口所获取的url ',
        '65106' => '门店状态必须为审核通过',
        '65107' => '扩展字段为不允许修改的状态 ',
        '65109' => '门店名为空',
        '65110' => '门店所在详细街道地址为空 ',
        '65111' => '门店的电话为空',
        '65112' => '门店所在的城市为空 ',
        '65113' => '门店所在的省份为空 ',
        '65114' => '图片列表为空',
        '65115' => 'poi_id 不正确 ',
        '40053' => '不合法的actioninfo',
        '40071' => '不合法的卡券类型',
        '40072' => '不合法的编码方式',
        '40073' => '签名错误',
        '40078' => '不合法的卡券状态 ',
        '40079' => '不合法的时间',
        '40097' => '无效的参数',
        '40080' => '不合法的CardExt ',
        '40099' => '卡券已被核销。',
        '40100' => '不合法的时间区间。 ',
        '40109' => '失败！CODE每次上传数量限制为100个',
        '40116' => '不合法的Code码。 ',
        '40122' => '不合法的库存数量。',
        '40124' => '会员卡设置查过限制的 custom_field字段',
        '40127' => '卡券被用户删除或转赠中。',
        '41012' => '缺少cardid参数',
        '45030' => '该cardid无接口权限',
        '45031' => '库存为0',
        '45033' => '用户领取次数超过限制get_limit',
        '41011' => '缺少必填字段',
        '45021' => '字段超过长度限制',
        '40056' => '不合法的Code码',
        '43009' => '自定义SN权限，请前往公众平台申请',
        '43010' => '无储值权限，请前往公众平台申请',
    );
    private $_weixin_card_color = array(
        '#63b359','#2c9f67','#509fc9','#5885cf','#9062c0','#d09a45','#e4b138',
        '#ee903c','#f08500','#a9d92d','#dd6549','#cc463d','#cf3e36','#5E6671'
    );
    private $_token = '';
    public function __construct($appid,$appSecret,$jsonPath,$token_url='') {

        parent::__construct($appid,$appSecret,$jsonPath);
        //$m = new JSSDK($this->_appid,$this->_appSecret,$this->_appid);
        if(!empty($token_url)) {
            $str = file_get_contents($token_url);
            if ($str) {
                $arr = json_decode($str, 1);
                if($arr['access_token']){
                    $this->_token = $arr['access_token'];
                }else{
                    echo "access_token_url error!";die;
                }
            }
        }else{
            $tokenjson = $this->getToken();//获取token
            $tokenAry = json_decode($tokenjson,1);
            if($tokenAry['access_token']){
                $this->_token = $tokenAry['access_token'];
            }
        }

    }

    //实时获取token
    public function setRealTimeToken(){
        $tokenjson = $this->getToken(1);//获取token
        $tokenAry = json_decode($tokenjson,1);
        if($tokenAry['access_token']){
            $this->_token = $tokenAry['access_token'];
        }
    }

    /*
     *uploadCardLogo 上传卡券LOGO
     *param string access_token		token
     *param string imgUrl			图片地址
     *return array res				array('url'=>'xxx');上传成功的LOGO的微信地址
     *error  array res 				array('errmsg'=>'xxx');上传失败返回失败信息
     */
    public function uploadCardLogo($imgUrl) {

        $res = '';
        if($imgUrl) {
            $url = "{$this->_weixin_uploadimg_path}?access_token={$this->_token}";
            $data['buffer'] = $imgUrl;
        }else{
            $res['errmsg'] = 'data error';
            return $res;
        }

        //请求
        $res =  $this->http_request($url,$data);
        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }

    //模拟 GET请求 及 POST请求
    public function http_request($url,$post=null) {
        #1.初始化curl
        $ch = curl_init();
        #2.设置请求地址
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //终端不验证curl
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //模拟 POST
        if(!empty($post)){
            curl_setopt($ch,CURLOPT_POST,1);//模拟post
            curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//post内容
        }
        #3.执行curl
        $Token_Outopt = curl_exec($ch);
        #4.关闭curl
        curl_close($ch);
        #5.格式化数据
        $access_arr = json_decode($Token_Outopt,true);
        #6.返回值
        return $access_arr;
    }

    /*
     *createCard 创建卡券
     *@param string access_token  TOKEN
     *@param arr    card          卡券信息
     *return array res(errcode！=0)		array('card_id'=>'xxx');成功的LOGO的微信ID
     *return array res(errcode=0)		array('errmsg'=>'xxx');上传失败返回失败信息
     */
    public function createCard($card) {
        $res = array();
        $url = "{$this->_weixin_card_path}create?access_token={$this->_token}";
        $data = json_encode($card);
        $res =  $this->http_request($url,urldecode($data));
        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }

    /*
     *getCard 获取卡券详情
     *@param string access_token  TOKEN
     *@param arr    card=array( "card_id"=>"pFS7Fjg8kV1IdDz01r4SQwMkuCKc") 卡券信息
     *return arr
     */
    public function getCard($card) {
        $res = array();
        $url = "{$this->_weixin_card_path}get?access_token={$this->_token}";
        $data = json_encode($card);
        $res =  $this->http_request($url,$data);
        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }

    /*
     *createCard 修改卡库存接口
     *@param string access_token  TOKEN
     *@param arr    card          卡券信息
     *return array res(errcode！=0)		array('errmsg'=>'ok');成功返回OK
     *return array res(errcode=0)		array('errmsg'=>'xxx');上传失败返回失败信息
     */
    public function modifyStock($card) {
        $res = array();
        $url = "{$this->_weixin_card_modifystock_path}?access_token={$this->_token}";
        $data = json_encode($card);
        $res =  $this->http_request($url,urldecode($data));
        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }

    /*
     *depositCode 上传卡券CODE
     *@param string access_token  TOKEN
     *@param arr    code          卡券CODE信息
     *return array res(errcode！=0)		array('errmsg'=>'ok');成功返回OK
     *return array res(errcode=0)		array('errmsg'=>'xxx');上传失败返回失败信息
     */
    public function depositCode($code) {

        $res = array();
        $url = "{$this->_weixin_up_code_path}?access_token={$this->_token}";
        $data = json_encode($code);

        //请求
        $res =  $this->http_request($url,urldecode($data));

        //如果是token错误，再实时获取一次，并做缓存
        if($res['errcode']==40001){
            $this->setRealTimeToken();
            $res =  $this->http_request($url,$data);
            $url = "{$this->_weixin_uploadimg_path}?access_token={$this->_token}";
        }

        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }

    /*
     *cardCodeGet() 查询CODE
     *@param sting code[card_id] 卡劵ID
     *@param sting code[code] code码
     *@param bool code[check_consume] 是否校验CODE核销状态
     */
    public function cardCodeGet($code){
        $res = array();
        $url = "{$this->_weixin_code_get_path}?access_token={$this->_token}";
        $data = json_encode($code);
        $res =  $this->http_request($url,urldecode($data));
        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }

    /*
     *cardCodeConsume() 核销CODE
     *@param sting code[card_id] 卡劵ID
     *@param sting code[code] code码
     */
    public function cardCodeConsume($code){
        $res = array();
        $url = "{$this->_weixin_code_consume_path}?access_token={$this->_token}";
        $data = json_encode($code);
        $res =  $this->http_request($url,urldecode($data));
        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }

    /*
     *getDepositCount() 获取提交的CODE数量
     *@param arr cardId 卡劵ID，格式，例： array('card_id'=>'poDR1t9PqSnyZCTvIiH8yVJ5g9dI')
     * return array ( "errcode"=> 0, "errmsg"=>"ok","count"=>291800)
     */
    public function getDepositCount($cardId) {
        $res = array();
        $url = "{$this->_weixin_getdepositcount}?access_token={$this->_token}";
        $data = json_encode($code);
        $res =  $this->http_request($url,$data);
        if(isset($res['errcode']) && $res['errcode'] != 0) {
            $res['errmsg'] = $this->_weixin_errorCode[$res['errcode']];
            return $res;
        }
        return $res;
    }


}