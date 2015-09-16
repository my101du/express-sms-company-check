<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

	public function __initialize(){
		
		header('content-type:text/html;charset=utf-8');
	}


	/**
	* +--------------------------------------------------------------------------
	* 前端页面(显示表单)
	*
	* +--------------------------------------------------------------------------
	*/
	public function index(){

		$this->display();
	}


	/**
	* +--------------------------------------------------------------------------
	* 获取快递公司列表
	* 因公司列表变化很小，而这个请求是有数量限制的，尽可能在前台写死 select 及 option 条目
	*
	* @return json
	* +--------------------------------------------------------------------------
	*/
	public function getExpressCompanies() {

		$params  = 'key='.C("EXPRESS_APP_KEY");

		$content = $this->juhecurl(C("EXPRESS_COM_URL"), $params);

		$returnArray = json_decode($content,true);

		echo $content;
	}


	/** 
	* +--------------------------------------------------------------------------
	* 获取快递数据
	*
	* @param string $get.company 快递公司代码
	* @param string $get.number 快递单号
	* @return json
	* +--------------------------------------------------------------------------
	*/
	public function getExpressData(){

		// 传入 get 参数，包括公司代号、快递单号、验证码
		$com    = I("get.company");
		$no     = I("get.number");
		$verify = I("get.verify");

		// 处理验证码
		if ( !$this->check_verify($verify) ) {

			$content = array('resultcode'=>1000, 'reason'=>'验证码填写错误');

			echo json_encode($content);
			exit();
		}

		// 处理机器人程序刷接口（目前通过IP判断）
		$ip = get_client_ip(0, true);

		$Record = M("expressrecord");
		$express_record = $Record->where("ip='" . $ip . "'")->find();

		if( $express_record && ( (time() - $express_record['time']) < 60 ) ){
			echo json_encode(array('reason'=>'60秒内只能查询一次'));
			exit();
		}

		if ( $com && $no ) {

			$params = array(
				'key' => C("EXPRESS_APP_KEY"),
				'com'  => $com,
				'no' => $no
			);

			// 开发测试阶段直接返回值，不请求 API
			// $content = $this->juhecurl(C("EXPRESS_QUERY_URL"), $params, 1);
			$content = array('resultcode'=>200, 'reason'=>'查询成功', 'result'=>array('list'=>array()));

			// 删除旧记录（如果有），然后添加新的记录
			$Record->where("ip='" . $ip . "'")->delete();

			$data = array(
				'ip' => $ip,
				'time'=>time()
			);

			$Record->add($data);

			//$returnArray = json_decode($content,true);
			echo json_encode($content, true);
		}
	}


	/**
	* +--------------------------------------------------------------------------
	* 请求发送短信接口，60秒后才能重新发送
	*
	* @param int $get.tplid 短信模板id
	* @param string $get.mobile 手机号码
	* @return json
	* +--------------------------------------------------------------------------
	*/
	public function sendSMS(){

		$tpl_id = I("get.tplid"); // 短信模板id：注册 5596 找回密码 5602 在线核名 5603
		$mobile = I("get.mobile"); // 手机号码

		// 检查数据库记录 ,是否在 60 秒内已经发送过一次
		$Record = M("smsrecord");

		$where = array(
			'mobile' => $mobile,
			'tpl_id' => $tpl_id,
		);
		$sms_record = $Record->where($where)->find();

		if( $sms_record && ( (time() - $sms_record['time']) < 60 ) ){
			echo json_encode(array('reason'=>'60秒内不能多次发送'));
			exit();
		}

		// 如果60秒内没有发过，则发送验证码短信（6位随机数字）
		$code = mt_rand(100000, 999999);

		$smsConf = array(
		    'key'   => C("SEND_SMS_KEY"), //您申请的APPKEY
		    'mobile'    => $mobile, //接受短信的用户手机号码
		    'tpl_id'    => $tpl_id, //您申请的短信模板ID，根据实际情况修改
		    'tpl_value' =>'#code#=' . $code //您设置的模板变量，根据实际情况修改 '#code#=1234&#company#=聚合数据'
		);
		 
		//测试阶段，不发短信，直接设置一个“发送成功” json 字符串
		$content = $this->juhecurl(C("SEND_SMS_URL") ,$smsConf, 1); //请求发送短信
		//$content = json_encode(array('error_code'=>0, 'reason'=>'发送成功'));

		if($content){
		    $result = json_decode($content,true);
		    $error_code = $result['error_code'];

		    if($error_code == 0){
		        // 状态为0，说明短信发送成功

		    	// 数据库存储发送记录,用于处理倒计时和输入验证，首先要删除旧记录
		    	$Record->where("mobile=" . $mobile)->delete();

					$data = array(
						'mobile' => $mobile,
						'tpl_id'=> $tpl_id,
						'code'=>$code,
						'time'=>time()
					);
					$Record->data($data)->add();

		        //echo "短信发送成功,短信ID：".$result['result']['sid'];
		    }else{
		        //状态非0，说明失败

		        //echo "短信发送失败(".$error_code.")：".$msg;
		    }
		}else{
		    //返回内容异常，以下可根据业务逻辑自行修改
		    //$result['reason'] = '短信发送失败';
		}

		echo $content;
	}


	/**
	* +--------------------------------------------------------------------------
	* 检查填写的手机验证码是否填写正确
	* 可以添加更多字段改造成注册、登录等表单
	*
	* @param string $get.verify 验证码
	* @param string $get.mobile 手机号码
	* @param int $get.tplid 短信模板ID
	* @param int $get.code 手机接收到的验证码
	* +--------------------------------------------------------------------------
	*/
	public function checkSmsCode(){

		$verify = I("get.verify");
		$tpl_id = I("get.tplid"); // 短信模板id：注册 5596 找回密码 5602 在线核名 5603
		$mobile = I("get.mobile"); // 手机号码
		$code = I("get.code"); // 手机收到的验证码

		if(!$this->check_verify($verify)){
			$content = array('resultcode'=>1000, 'reason'=>'验证码填写错误');
			echo json_encode($content);
			exit();
		}

		// 检查数据库记录，输入的手机验证码是否和之前通过短信 API 发送到手机的一致
		$Record = M("smsrecord");
		$where = array(
			'mobile' => $mobile,
			'tpl_id' => $tpl_id,
			'code' => $code,
		);
		$sms_record = $Record->where($where)->find();

		if($sms_record){
			echo json_encode(array('reason'=>'短信验证码核对成功'));

			// 处理后面的程序（如继续登录、注册等）
		}else{
			echo json_encode(array('reason'=>'短信验证码错误'));
		}
	}


	/** 
	* +--------------------------------------------------------------------------
	* 企业核名
	* 该接口 http://eci.yjapi.com/ECIFast/Search 仅支持 GET 方式，因此 $param 为字符串形式
	*
	* @param string $get.province 省份
	* @param string $get.companyName 公司名
	* @return json
	* +--------------------------------------------------------------------------
	*/
	public function getCompanyName(){

		// 传入 get 参数
		$province				= I("get.province");
		$companyName    = I("get.companyName");
		$verify         = I("get.verify");

		// 处理验证码
		if ( !$this->check_verify($verify) ) {

			$content = array('resultcode'=>1000, 'reason'=>'验证码填写错误');

			echo json_encode($content);
			exit();
		}

		// 处理机器人程序刷接口（目前通过IP判断）
		$ip = get_client_ip(0, true);

		$Record = M("companyrecord");
		$company_record = $Record->where("ip='" . $ip . "'")->find();

		// if( $company_record && ( (time() - $company_record['time']) < 60 ) ){
		// 	echo json_encode(array('reason'=>'60秒内只能查询一次企业名称'));
		// 	exit();
		// }

		$resultArray = array();

		if ( $province && $companyName ) {

			// 删除旧记录（如果有），然后添加新的记录
			$Record->where("ip='" . $ip . "'")->delete();

			// 循环处理多个公司名
			$companies = explode(",", $companyName);
			foreach ($companies as $key => $value) {
				$params = "key=" . C("COMPANY_KEY") . "&province={$province}&companyName={$value}";

				// 开发测试阶段直接返回值，不请求 API
				$content = $this->juhecurl(C("COMPANY_URL"), $params, 0);
				$returnArray = json_decode($content,true);

				// 循环插入新的结果
				$resultArray[] = $returnArray;
				//$content = array('resultcode'=>200, 'reason'=>'查询成功', 'result'=>array('list'=>array()));
			}

			$data = array(
				'ip' => $ip,
				'time'=>time()
			);

			$Record->add($data);
			$content = json_encode($resultArray, true);
			echo json_encode($content, true);
		}
	}


	/**
	* +--------------------------------------------------------------------------
	* 通用的“聚合数据”请求接口，返回JSON数据
	*
	* @param string $url 接口地址
	* @param array $params 传递的参数
	* @param int $ispost 是否以POST提交，默认GET
	* @return json
	* +--------------------------------------------------------------------------
	*/
	public function juhecurl($url,$params=false,$ispost=0){

		$httpInfo = array();
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
		curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
		if( $ispost )
		{
			curl_setopt( $ch , CURLOPT_POST , true );
			curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
			curl_setopt( $ch , CURLOPT_URL , $url );
		}
		else
		{
			if($params){
				curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
			}else{
				curl_setopt( $ch , CURLOPT_URL , $url);
			}
		}
		$response = curl_exec( $ch );
		if ($response === FALSE) {
		  	//echo "cURL Error: " . curl_error($ch);
			return false;
		}
		$httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
		$httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
		curl_close( $ch );
		return $response;
	}


	/**
	* +--------------------------------------------------------------------------
	* 生成验证码
	*
	* +--------------------------------------------------------------------------
	*/
	public function verify(){

		$Verify = new \Think\Verify();
		$Verify->entry();
	}


	/**
	* +--------------------------------------------------------------------------
	* 检查验证码
	*
	* @param string $code 输入的验证码
	* @return boolean
	* +--------------------------------------------------------------------------
	*/
	protected function check_verify($code){

		$verify = new \Think\Verify();
		return $verify->check($code);
	}

}