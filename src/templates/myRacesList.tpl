{include file='header'}

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="MyRacesList" link="pageNo=%d"}{/content}
	</div>
{/hascontent}

{if $items}
	
    {include file='commonRaceList'}
	
	<footer class="contentFooter">
		{hascontent}
			<div class="paginationBottom">
				{content}{@$pagesLinks}{/content}
			</div>
		{/hascontent}
	</footer>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
