{include file='header'}

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="MyParticipationsList" link="pageNo=%d"}{/content}
	</div>
{/hascontent}

{if $items}
	<div class="section sectionContainerList">
		<ol class="containerList raceList">
			{foreach from=$objects item=participation}
				<li>
					<div class="box48">
						<span class="icon icon48 fa-car"></span>
						
						<div class="details raceInformation">
							<div class="containerHeadline">
								<h3><a href="{$participation->getRace()->getLink()}">{$participation->getRace()}</a></h3>
								<span>{lang}{$participation->getRace()->getParticipation()->getType()->shortTextLangId}{/lang}</span>
							</div>
						</div>
					</div>
				</li>
			{/foreach}
		</ol>
	</div>
	
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
