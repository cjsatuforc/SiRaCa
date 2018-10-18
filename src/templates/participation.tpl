{capture assign='pageTitle'}{lang}siraca.participation.title{/lang}{$race->title}{/capture}

{capture assign='contentTitle'}{lang}siraca.participation.title{/lang}{$race->title}{/capture}

{include file='header'}

{if $race->isParticipant()}<p>{lang}siraca.participation.registration.registered{/lang}</p>
{else}<p>{lang}siraca.participation.registration.notRegistered{/lang}</p>
{/if}

{include file='formError'}

<form method="post" action="{link controller='Participation' object=$race}{/link}">
	<section class="section">
		<dl {if $errorField == 'participationType'} class="formError"{/if}>
			<dt><label for="participationTypeId">{lang}siraca.participation.form.presence{/lang}</label></dt>
			<dd>
				<select name="participationType" id="participationType">
					{foreach from=$participationTypes item=participationType}
						<option value="{$participationType}"{if $participation->type == $participationType} selected{/if}>{lang}{$participation->getLangId($participationType)}{/lang}</option>
					{/foreach}
				</select>
				{if $errorField == 'participationType'}
					<small class="innerError">
						{if $errorType == 'noChange'}
							{lang}siraca.participation.form.error.noChange{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>
	</section>

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
