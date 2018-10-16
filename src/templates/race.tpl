{capture assign='pageTitle'}{$race} - {lang}siraca.race.detail{/lang}{/capture}

{capture assign='contentTitle'}{$race}{/capture}

{include file='header'}


<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}siraca.race.participation{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='Participation' object=$race}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}siraca.link.participation{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>


{if $race->isParticipant()}<span>Inscrit</span>{/if}

{include file='footer'}
