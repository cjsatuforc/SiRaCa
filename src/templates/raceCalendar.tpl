{capture assign='pageTitle'}{lang}siraca.calendar.title{/lang}{/capture}
{capture assign='contentTitle'}{lang}siraca.calendar.title{/lang}{/capture}
{include file='header'}

<div class="section siracaMonthView">
    {foreach name=days from=$month->getDays() item=day}
        {if $tpl.foreach.days.iteration % 7 == 1}
            <ol class="siracaMonthLine">
        {/if}
        <li>
            <div class="dayName">{lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()}</div>
            {assign var=dayRaces value=$raceMonth->getDayRaces($day->getDayValue(), 4)}
            {if $dayRaces|count}
                <div class="dayRaces">
                    <ol>
                        {foreach name=races from=$dayRaces item=race}
                            <li>
                                <a href="{$race->getLink()}">{$race->title}</a>
                            </li>
                        {/foreach}
                    </ol>
                    {if $raceMonth->getDayRaces($day->getDayValue())|count > 4}
                        <small>{lang}siraca.calendar.monthView.showMoreRaces{/lang}</small>
                    {/if}
                </div>
            {/if}
        </li>
        {if $tpl.foreach.days.iteration % 7 == 0}
            </ol>
        {/if}
    {/foreach}
</div>

{include file='footer'}