{capture assign='pageTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentHeaderNavigation'}
	<ul>
		<li><span>
			{if $race->isParticipant()}{lang}siraca.participation.registration.registered{/lang}
			{else}{lang}siraca.participation.registration.notRegistered{/lang}{/if}
		</li></span>
		
		{if $__wcf->getSession()->getPermission('user.siraca.canParticipateRace')}
			<li>
				<a href="{link controller='Participation' object=$race}{/link}" class="button"><span class="icon icon16 fa-sign-in"></span> <span>{lang}siraca.participation.link{/lang}</span></a>
			</li>
		{/if}
	</ul>
{/capture}

{include file='header'}


{include file='footer'}
