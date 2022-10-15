<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("printPageContent")) {
	include_once __DIR__."/api.php";

	function printPageComponent($layout,$compParams=null,$classes=array(),$opts=array()) {
		if(!$layout || $layout=="auto" || $layout=="layout" || !file_exists($layout)) $layout=__DIR__."/layout.tpl";

		if(!$compParams) $compParams=listPageParams();
		else $compParams = array_merge(listPageParams(), $compParams);

		$temp = explode("/", $compParams["BASE_DIR"]);
		$compParams['MODULE'] = end($temp);

		if(!is_array($classes)) {
			$classes=array();
		}
		if(!is_array($opts)) {
			$opts=array();
		}

		if(isset($opts['policy']) && strlen($opts['policy'])>0) {
			$allow=checkUserPolicy($opts['policy']);
        	if(!$allow) {
        		trigger_logikserror("Sorry, <strong>{This Module}</strong> is not accessible to you. Contact Admin!",E_LOGIKS_ERROR,401);
        		return false;
        	}
		}

		$dataParams=[];

		foreach ($compParams as $compKey => $comp) {
			$dataParams[$compKey]=getPageComponent($compKey,$comp,$compParams);
		}
		//printArray($dataParams);
		
		echo _css(['pages']);
		echo _js(['pages']);
		
		return _template($layout,$dataParams);
	}
	function listPageParams() {
		$arr=array(
				"BASE_DIR"=>__DIR__,
				"MODULE"=>"pages",
				"toolbar"=>null,
				"contentArea"=>"",
				"sidebar"=>null,
				"footer"=>null,
				"slug"=>"?/page/a/b/c/d",
				//"autopopup"=>"",
				//"hidden"=>"",
			);
		return $arr;
	}
}
?>