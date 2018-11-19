{capture assign='pageTitle'}{lang}siraca.calendar.title{/lang} {lang}wcf.date.month.{$month->getMonthName()}{/lang} {$month->getYearValue()}{/capture}
{capture assign='contentTitle'}{lang}siraca.calendar.title{/lang} {lang}wcf.date.month.{$month->getMonthName()}{/lang} {$month->getYearValue()}{/capture}

{capture assign='contentHeaderNavigation'}
    <ol class="buttonGroup">
        <li><a class="button {if !$month->getPreviousMonth()}disabled{/if}"
            {if $month->getPreviousMonth()}href="{link controller="RaceCalendar"
            year=$month->getPreviousMonth()->getYearValue()
            month=$month->getPreviousMonth()->getMonthValue()}{/link}{/if}">
            <span class="icon icon16 fa-chevron-left"></span></a>
        </li>
        <li><a class="button {if !$month->getNextMonth()}disabled{/if}"
            {if $month->getNextMonth()}href="{link controller="RaceCalendar"
            year=$month->getNextMonth()->getYearValue()
            month=$month->getNextMonth()->getMonthValue()}{/link}{/if}">
            <span class="icon icon16 fa-chevron-right"></span></a>
        </li>
    </ol>
{/capture}

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