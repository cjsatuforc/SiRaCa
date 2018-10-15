{include file='header'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}siraca.race.list{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='RaceAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}siraca.link.race.add{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

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
							</div>
						</div>
					</div>
				</li>
			{/foreach}
		</ol>
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}