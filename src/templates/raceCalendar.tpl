{capture assign='pageTitle'}{lang}siraca.calendar.title{/lang}{/capture}
{capture assign='contentTitle'}{lang}siraca.calendar.title{/lang}{/capture}
{include file='header'}

<div class="section siracaMonthView">
    {foreach name=days from=$month->getDays() item=day}
        {if $tpl.foreach.days.iteration % 7 == 1}
            <ol class="siracaMonthLine">
        {/if}
        <li>
            <div class="dayName"><a href="{link controller='RaceDay' year=$month->getYearValue() month=$month->getMonthValue() day=$day->getDayValue()}{/link}">{lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()}</a></div>
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
                        <a class="showMore" href="{link controller='RaceDay' year=$month->getYearValue() month=$month->getMonthValue() day=$day->getDayValue()}{/link}">{lang}siraca.calendar.monthView.showMoreRaces{/lang}</a>
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