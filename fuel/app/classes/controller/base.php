<?php
class Controller_Base extends Controller
{
	//クリックジャッキング攻撃用
	public function before()
	{
		parent::before();
		header('X-Frame-Options: DENY');
	}
}
?>