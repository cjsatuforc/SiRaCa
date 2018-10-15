{include file='header' pageTitle='siraca.race.'|concat:$action}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}siraca.race.{$action}{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='RaceList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}siraca.menu.link.race.list{/lang}</span></a></li>
			
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{link controller='RaceAdd'}{/link}">
	<div class="section">
		<dl {if $errorField == 'title'} class="formError"{/if}>
			<dt><label for="title">{lang}siraca.race.title{/lang}</label></dt>
			<dd>
				<input type="text" id="title" name="title" value="{$title}" required autofocus maxlength="255" class="long">
				{if $errorField == 'title'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
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