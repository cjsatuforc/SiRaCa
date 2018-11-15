<div>
    <strong>{lang}{$race->getParticipationType()->longTextLangId}{/lang}</b></strong>
    <br />
    <small>{if !$race->isParticipant()}
        {if $race->getTitularListFreeSlots() > 0}
            {lang}siraca.participation.detail.freeTitularList{/lang}
        {else}
            {lang}siraca.participation.detail.waitingListCount{/lang}
        {/if}
    {else}
        {if $race->isTitular()}
            {lang}siraca.participation.detail.titularPosition{/lang}
        {else}
            {if $race->isParticipationConfirmed()}
                {lang}siraca.participation.detail.waitingPosition{/lang}
            {else}
                {if $race->getTitularListFreeSlots() > 0}
                    {lang}siraca.participation.detail.freeTitularList{/lang}
                {else}
                    {lang}siraca.participation.detail.waitingPosition{/lang}
                {/if}
            {/if}
        {/if}
    {/if}</small>
</div>