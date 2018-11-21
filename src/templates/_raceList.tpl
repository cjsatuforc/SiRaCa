{if $objects|count}
    <div class="section siracaRaceList">
        <ol>
            {foreach from=$objects item=race}
                <li class="raceContainer">
                    <div class="title"><a href="{$race->getLink()}">{$race}</a></div>
                    <div class="date">{$race->getFormattedStartTime()}</div>
                    <div class="participationContainer">
                        <div class="participants">{include file='_participantsSummary'}</div>
                        {if $race->isParticipant()} - <div class="participation">{lang}{$race->getParticipationType()->shortTextLangId}{/lang}</div>{/if}
                    </div>
                </li>
            {/foreach}
        </ol>
    </div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{* Version avec les CSS WoltLab.

{if $objects|count}
    <div class="section sectionContainerList">
        <ol class="containerList">
            {foreach from=$objects item=race}
                <li>
                    <div class="box48">
                        <span class="icon icon48 fa-car"></span>
                        
                        <div class="details">
                            <div class="containerHeadline">
                                <h3><a href="{$race->getLink()}">{$race}</a></h3>
                                <div>{$race->getFormattedStartTime()}</div>
                                <div>{include file='_participantsSummary'}</div>
                            </div>
                        </div>
                    </div>
                </li>
            {/foreach}
        </ol>
    </div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}
*}