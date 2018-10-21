{capture assign='pageTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentHeaderNavigation'}
	<ul>
		<li><span>
			{lang}{$race->getParticipationType()->longTextLangId}{/lang}
		</li></span>
		
		{if $__wcf->getSession()->getPermission('user.siraca.canParticipateRace')}
			<li>
				<a href="{link controller='Participation' object=$race}{/link}" class="button"><span class="icon icon16 fa-sign-in"></span> <span>{lang}siraca.participation.link{/lang}</span></a>
			</li>
		{/if}
	</ul>
{/capture}

{include file='header'}

<div class="section sectionContainerList">
    <div class="sectionTitle">{lang}siraca.race.participants{/lang}</div>
    <ol> 
        {foreach from=$participations item=participation}
            <li>
                <div class="box16">
                    <span class="icon ico16 fa-user"></span>
                    
                    <div class="details">
                        <div class="containerHeadline">
                            <a href="{$participation->getUserLink()}">{$participation->getUsername()}</a>
                            {if $participation->isUncertain()}<small>({lang}{$participation->getType()->shortTextLangId}{/lang})</small>{/if}
                        </div>
                    </div>
                </div>
            </li>
        {/foreach}
    </ol>
</div>

{include file='footer'}
