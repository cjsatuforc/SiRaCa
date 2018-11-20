{capture assign='pageTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentTitle'}{lang}siraca.race.title{/lang}{$race}{/capture}

{capture assign='contentHeaderNavigation'}
	{include file='_participationBox'}
{/capture}

{include file='header'}

<div class="section">
    <ul>
        <li>{lang}siraca.race.starts{/lang}{$startTime}</li>
        <li>{lang}siraca.race.capacity{/lang}</li>
        <li>{include file='_participantsSummary'}</li>
    </ul>
</div>

<div class="section sectionContainerList">
    <h2 class="sectionTitle">{lang}siraca.race.participants{/lang} <span class="badge black">{$race->participationCount}</span></h2>
    {if $race->participationCount > 0}
        <div class="section">
            <h3 class="sectionTitle">{lang}siraca.participation.list.titular.title{/lang} <span class="badge blue">{$race->titularListCount}</span> <strong>/</strong> <span class="badge blue">{$race->availableSlots}</span></h3>
            <ol> 
                {foreach from=$titularList item=participation}
                    <li>
                        <div class="box16">
                            <span>{$participation->position}</span>
                            <span class="icon ico16 fa-user"></span>
                            
                            <div class="details">
                                <div class="containerHeadline">
                                    <a href="{$participation->getUserLink()}">{$participation->getUsername()}</a>
                                    {if !$participation->isConfirmed()}<small>({lang}{$participation->getType()->shortTextLangId}{/lang})</small>{/if}
                                </div>
                            </div>
                        </div>
                    </li>
                {/foreach}
            </ol>
        </div>
        
        <div class="section">
            <h3 class="sectionTitle">{lang}siraca.participation.list.waiting.title{/lang} <span class="badge brown">{$race->waitingListCount}</span></h3>
            <ol> 
                {foreach from=$waitingList item=participation}
                    <li>
                        <div class="box16">
                            <span>{$participation->position}</span>
                            <span class="icon ico16 fa-user"></span>
                            
                            <div class="details">
                                <div class="containerHeadline">
                                    <a href="{$participation->getUserLink()}">{$participation->getUsername()}</a>
                                    {if !$participation->isConfirmed()}<small>({lang}{$participation->getType()->shortTextLangId}{/lang})</small>{/if}
                                </div>
                            </div>
                        </div>
                    </li>
                {/foreach}
            </ol>
        </div>
    {/if}
</div>

<footer class="contentFooter">
	{hascontent}
		<nav class="contentFooterNavigation">
            <ul>
                {content}
                    {if $__wcf->user->userID && $__wcf->session->getPermission('mod.siraca.canManageRace')}
                        <li>
                            <a href="{link controller='RaceEdit' object=$race}{/link}" class="button siracaAdminButton">
                            <span class="icon icon16 fa-pencil"></span> <span>{lang}wcf.global.button.edit{/lang}</span></a>
                        </li>
                    {/if}
                    {event name='contentFooterNavigation'}
                {/content}
            </ul>
		</nav>
	{/hascontent}
</footer>

{include file='footer'}
