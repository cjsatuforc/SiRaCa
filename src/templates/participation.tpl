{capture assign='pageTitle'}{$participation} - {lang}siraca.participation{/lang}{/capture}

{capture assign='contentTitle'}Course : {$race->title}{/capture}

{include file='header'}

{if $participation|isset}<p>Vous êtes inscrit.</p>
{else}<p>Vous n'êtes pas inscrit.</p>
{/if}

<form method="post" action="{link controller='Participation' object=$race}{/link}">
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
