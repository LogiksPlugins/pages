<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("printPageContent")) {
	include_once __DIR__."/api.php";

	function printPageComponent($layout,$compParams=null,$classes=array(),$opts=array()) {
		if(!$layout || $layout=="auto" || $layout=="layout" || !file_exists($layout)) $layout=__DIR__."/layout.tpl";

		if(!$compParams) $compParams=listPageParams();

		if(!is_array($classes)) {
			$classes=array();
		}
		if(!is_array($opts)) {
			$opts=array();
		}

		$dataParams=[];

		foreach ($compParams as $compKey => $comp) {
			$dataParams[$compKey]=getPageComponent($compKey,$comp);
		}
		//printArray($dataParams);
		
		echo _css(['pages']);
		echo _js(['pages']);
		
		return _template($layout,$dataParams);
	}
	function listPageParams() {
		$arr=array(
				"toolbar"=>null,
				"contentarea"=>"",
				"sidebar"=>null,
				"footer"=>null,
				//"autopopup"=>"",
				//"hidden"=>"",
			);
		return $arr;
	}
}
?>