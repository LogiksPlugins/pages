<div class="pageComp withFixedBar">

	{if isset($toolbar) && $toolbar}
		<nav id="pgtoolbar" class="pageCompToolBar navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<div class="collapse navbar-collapse">
			      {$toolbar}
			    </div>
			</div>
		</nav>
	{/if}

	{if isset($sidebar) && $sidebar}
		<div id="pgworkspace" class="pageCompContainer withSidebar 
			{if isset($toolbar) && $toolbar}
				withToolbar
			{/if}
		 toggled container-fluid">
			<div id='pgsidebar' class='pageCompSidebar col-xs-12 col-sm-3 col-md-3'>
				{$sidebar}  
			</div>
			<div id='pgcontent' class='pageCompContent col-xs-12 col-sm-9 col-md-9'>
				{if isset($contentArea) && $contentArea}
					{$contentArea}  
				{/if}
			</div>
		</div>
	{else}
		<div id="pgworkspace" class="pageCompContainer container-fluid">
			{if isset($contentArea) && $contentArea}
				{$contentArea}
			{/if}
		</div>
	{/if}
	

	{if isset($footer) && $footer}
		<footer class="footer">
		  <div class="container-fluid">
		   	{$footer}
		  </div>
		</footer>
	{/if}
</div>