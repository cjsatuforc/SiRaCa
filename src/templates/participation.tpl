{capture assign='pageTitle'}{lang}siraca.participation.title{/lang}{$race->title}{/capture}

{capture assign='contentTitle'}{lang}siraca.participation.title{/lang}{$race->title}{/capture}

{include file='header'}

{if $race->isParticipant()}<p>{lang}siraca.participation.registration.registered{/lang}</p>
{else}<p>{lang}siraca.participation.registration.notRegistered{/lang}</p>
{/if}

<form method="post" action="{link controller='Participation' object=$race}{/link}">
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
