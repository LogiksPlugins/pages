<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(!function_exists("printPageToolbar")) {
	function getPageComponent($compKey,$compContent) {
		if(is_array($compContent)) {
			switch ($compKey) {
				case 'toolbar':
					return printPageToolbar($compContent);
					break;
				default:
					$data=[];
					foreach ($compContent as $key => $comp) {
						$data[]=getPageComponent($compKey,$comp);
					}
					return implode("\n", $data);
					break;
			}
		} else {
			if(file_exists($compContent) && is_file($compContent)) {
				$ext=explode(".", strtolower($compContent));
				$ext=end($ext);
				if($ext=="php") {
					ob_start();
					include $compContent;
					$data=ob_get_contents();
					ob_end_clean();
					return $data;
				} elseif($ext=="tpl") {
					return _templateData($data);
				} else {
					return file_get_contents($compContent);
				}
			}  elseif(function_exists($compContent)) {
				return call_user_func($compContent);
			} else {
				return $compContent;
			}
		}
		return false;
	}
	function printPageToolbar($btns) {
		$template=getPageToolbarElements();

		$html=["left"=>[],"right"=>[]];
		$moreLinks = ["menu"=>[], "title"=>_ling("more")];
		foreach($btns as $a=>$b) {
			if(isset($b['policy']) && strlen($b['policy'])>0) {
				$allow=checkUserPolicy($b['policy']);
        		if(!$allow) continue;
			}

			if(!isset($b['align']) || !in_array($b['align'], ['left','right'])) $b['align']="left";
			if(!isset($b['type'])) $b['type']="button";

			if(!isset($b['id'])) $b['id']="toolbtn_{$a}";
			if(!isset($b['title'])) $b['title']="";
			if(!isset($b['icon'])) $b['icon']="";
			if(!isset($b['tips'])) $b['tips']=$b['title'];
			if(!isset($b['class'])) $b['class']="";
			if(!isset($b['cmd'])) $b['cmd']=$a;
			if(!isset($b['subtype'])) $b['subtype']="";
			if(!isset($b['more'])) $b['more']=false;

			if(!isset($b['options'])) $b['options']="";
			elseif(is_array($b['options'])) {
				foreach ($b['options'] as $cmd => $title) {
					$title=_ling($title);
					if($b['subtype']=="checkbox") {
						$b['options'][$cmd]="<li><a data-drop='{$cmd}' href='#'><label><input type='checkbox' name='{$b['id']}' class='selectorbox' /> {$title}</label></a></li>";
					} elseif($b['subtype']=="radio") {
						$b['options'][$cmd]="<li><a data-drop='{$cmd}' href='#'><label><input type='radio' name='{$b['id']}' class='selectorbox' /> {$title}</label></a></li>";
					} else {
						$b['options'][$cmd]="<li><a data-drop='{$cmd}' href='#'>{$title}</a></li>";
					}
				}
				$b['options']=implode("", array_values($b['options']));
			}
			//if(!isset($b['onclick'])) $b['onclick']="";

			if($b['more']) {
				$b['type'] = "button";
				if(in_array("active", explode(" ",strtolower($b['class'])))) {
					$moreLinks['title'] = _ling($b['title']);
				}
				$moreLinks['menu'][]=sprintf($template[$b['type']],_ling($b['title']),$b['icon'],$b['id'],$b['tips'],$b['class'],$b['cmd'],$b['options']);
			} else {
				if(isset($template[$b['type']])) {
					$html[$b['align']][]=sprintf($template[$b['type']],_ling($b['title']),$b['icon'],$b['id'],$b['tips'],$b['class'],$b['cmd'],$b['options']);
				} else {
					$html[$b['align']][]=sprintf($template['button'],_ling($b['title']),$b['icon'],$b['id'],$b['tips'],$b['class'],$b['cmd']);
				}
			}
		}
		$htmlOut="<ul class='nav navbar-nav navbar-left'>".implode("", $html['left'])."</ul>";
		if($moreLinks['menu'] && count($moreLinks['menu'])>0) {
			$htmlOut.="<ul class='nav navbar-nav navbar-right'>".implode("", $html['right']).
						"<li id='%3\$s' class='btn-group %5\$s' title='%4\$s' data-cmd='%6\$s'>
							<button type='button' class='btn btn-default btn-more dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' >
						    	".$moreLinks['title']." <span class='caret'></span>
							</button>
							<ul class='dropdown-menu'>".implode("", $moreLinks['menu'])."</ul>
					  	</li>".
					"</ul>";
		} else {
			$htmlOut.="<ul class='nav navbar-nav navbar-right'>".implode("", $html['right'])."</ul>";
		}
		return $htmlOut;
	}
	function getPageToolbarElements() {
		$comps=[
				"button"=>"<li class='%5\$s'><a id='%3\$s' title='%4\$s' data-cmd='%6\$s' href='#'>%2\$s %1\$s</a></li>",
				"search"=>"<form id='pgToolbarSearch' class='navbar-form navbar-left %5\$s' role='search' title='%4\$s'>
					        <div class='form-group'>
					          <input type='text' class='form-control' placeholder='%1\$s'>
					        </div>
					      </form>",
				"dropdown"=>"<li id='%3\$s' class='btn-group %5\$s' title='%4\$s' data-cmd='%6\$s'>
								<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' >
							    	%2\$s %1\$s <span class='caret'></span>
								</button>
								<ul class='dropdown-menu'>
									%7\$s
								</ul>
						  </li>",
				"bar"=>"<li class='bar'><i class='glyphicon glyphicon-option-vertical'></i><li>"
			];
		return $comps;
	}
}
?>