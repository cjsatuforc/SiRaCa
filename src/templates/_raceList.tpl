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
                                {if $race->isParticipant()}<span>{lang}{$race->getParticipationType()->shortTextLangId}{/lang}</span>{/if}
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