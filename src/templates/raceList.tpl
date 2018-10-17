{capture assign='pageTitle'}{lang}siraca.race.list.title{/lang}{/capture}

{capture assign='contentTitle'}{lang}siraca.race.list.title{/lang}{/capture}

{capture assign='contentHeaderNavigation'}
	{if $__wcf->getSession()->getPermission('mod.siraca.canManageRace')}
	<a href="{link controller='RaceAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}siraca.race.add.link{/lang}</span></a>
	{/if}
{/capture}

{include file='header'}

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="RaceList" link="pageNo=%d"}{/content}
	</div>
{/hascontent}

{if $items}
	<div class="section sectionContainerList">
		<ol class="containerList raceList">
			{foreach from=$objects item=race}
				<li>
					<div class="box48">
						<span class="icon icon48 fa-user"></span>
						
						<div class="details raceInformation">
							<div class="containerHeadline">
								<h3><a href="{$race->getLink()}">{$race}</a></h3>
								{if $race->isParticipant()}<span>{lang}siraca.participation.registration.registeredShort{/lang}</span>{/if}
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