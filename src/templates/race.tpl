{capture assign='pageTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentHeaderNavigation'}
	{if $__wcf->getSession()->getPermission('user.siraca.canParticipateRace')}
	<a href="{link controller='Participation' object=$race}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}siraca.participation.link{/lang}</span></a>
	{/if}
{/capture}

{include file='header'}

{if $race->isParticipant()}<span>{lang}siraca.participation.registration.registered{/lang}</span>
{else}<span>{lang}siraca.participation.registration.notRegistered{/lang}{/if}

{include file='footer'}
