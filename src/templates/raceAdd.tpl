{if $action == 'add'}
    {capture assign='pageTitle'}{lang}siraca.race.add.title{/lang}{/capture}
    {capture assign='contentTitle'}{lang}siraca.race.add.title{/lang}{/capture}
{else}
    {capture assign='pageTitle'}{lang}siraca.race.edit.title{/lang} {$race->title}{/capture}
    {capture assign='contentTitle'}{lang}siraca.race.edit.title{/lang} {$race->title}{/capture}
{/if}

{include file='header'}

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link controller='RaceAdd'}{/link}{else}{link controller='RaceEdit' object=$race}{/link}{/if}">
	<div class="section">
		<dl {if $errorField == 'title'} class="formError"{/if}>
			<dt><label for="title">{lang}siraca.race.add.form.title{/lang}</label></dt>
			<dd>
				<input type="text" id="title" name="title" value="{$title}" required autofocus maxlength="255" class="long">
				{if $errorField == 'title'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{* TODO gestion d'erreur ? *}
							{lang}siraca.race.title.error.{$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>
		
		{event name='dataFields'}
	</div>
	
	{event name='sections'}
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}