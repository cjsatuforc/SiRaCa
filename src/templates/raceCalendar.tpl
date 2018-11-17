{capture assign='pageTitle'}{lang}siraca.calendar.title{/lang}{/capture}
{capture assign='contentTitle'}{lang}siraca.calendar.title{/lang}{/capture}
{include file='header'}

<ol>
    {foreach from=$month->getDays() item=day}
        <li>{$day->getDayValue()}</li>
        {assign var=dayRaces value=$raceMonth->getDayRaces($day->getDayValue())}
        {if $dayRaces|count}
        {include file='_raceList' objects=$dayRaces}
        {/if}
    {/foreach}
</ol>

{include file='footer'}