<?php
namespace Swoolefy\Core;

use Swoolefy\Core\Application;

class Init {
	/**
	 * _init 初始化一下超全局变量,兼容php-fpm的web模式
	 */
	public static function _init() {
		// 每一次请求清空,再初始化
		$_POST = $_GET = $_REQUEST = [];
		//请求对象
		$request = Application::$app->request;
		self::resetServer($request);
		self::resetPost($request);
		self::resetGet($request);
		self::resetCookie($request);
		self::resetFile($request);
		// 设置在最后执行
		self::resetRequest($request);
	}
	/**
	 * resetServer 重置SERVER超全局数组
	 * @param  $request 请求对象
	 * @return void
	 */
	public static function resetServer($request) {
		foreach($request->server as $p=>$val) {
			$request->server[strtoupper($p)] = $val;
			unset($request->server[$p]);
		}
		foreach ($request->header as $key => $value) {
            $_key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
            $request->server[$_key] = $value;
        }
	}

	/**
	 * resetPost 重置POST超全局数组
	 * @param  $request 请求对象
	 * @return void
	 */
	public static function resetPost($request) {
		if(isset($request->post)) {
			$_POST = array_merge($_POST,$request->post);
		}
	}

	/**
	 * resetGet 重置GET超全局数组
	 * @param  $request 请求对象
	 * @return void
	 */
	public static function resetGet($request) {
		if(isset($request->get)) {
			$_GET = array_merge($_GET,$request->get);
		}
	}

	/**
	 * resetCookie 重置COOKIE超全局数组,由于COOKIE是一个常驻内存的全局变量,每个请求设置的cookie可能会被覆盖
	 * 这里不需要重新设置合并,每个请求通过request->cookie获取cookie的值，而不要通过$_COOKIE
	 * @param  $request 请求对象
	 * @return void
	 */
	public static function resetCookie($request) {
		
	}

	/**
	 * resetFile 重置FILE超全局数组
	 * @param  $request 请求对象
	 * @return void
	 */
	public static function resetFile($request) {
		if(isset($request->fiels)) {
			$_FILES = array_merge($_FILES,$request->fiels);
		}
	}

	/**
	 * resetRequest 重置REQUEST超全局数组
	 * @param  $request 请求对象
	 * @return void
	 */
	public static function resetRequest($request) {
		$_REQUEST = array_merge($_POST,$_GET,$request->cookie);
	}
}