{capture assign='pageTitle'}{lang}siraca.calendar.title{/lang} {$month->getTitle()}{/capture}
{capture assign='contentTitle'}{lang}siraca.calendar.title{/lang} {$month->getTitle()}{/capture}

{capture assign='contentHeaderNavigation'}
    <ol class="buttonGroup">
        <li><a class="button {if !$month->getPreviousMonth()}disabled{/if}"
            {if $month->getPreviousMonth()}href="{link controller="RaceCalendar"
            year=$month->getPreviousMonth()->getYearValue()
            month=$month->getPreviousMonth()->getMonthValue()}{/link}{/if}">
            <span class="icon icon16 fa-chevron-left"></span></a>
        </li>
        <li><a class="button {if $month->isCurrentMonth()}disabled{/if}"
            href="{link controller="RaceCalendar"}{/link}">{lang}siraca.calendar.monthView.thisMonth{/lang}</a>
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
    {foreach name=days from=$monthView->getDays() item=day}
        {if $tpl.foreach.days.iteration % 7 == 1}
            <ol class="siracaMonthLine">
        {/if}
        <li class="{if $day->isToday()}today{/if} {if !$month->contains($day)}dayOffMonth{/if}">
            {if $month->contains($day)}
                <div class="dayName"><a href="{link controller='RaceDay' year=$month->getYearValue() month=$month->getMonthValue() day=$day->getDayValue()}{/link}">{$day->getTitleShort()}</a></div>
                {assign var=dayRaces value=$raceMonth->getDayRaces($day->getDayValue(), 4)}
                {if $dayRaces|count}
                    <div class="dayRaces">
                        <ol>
                            {foreach from=$dayRaces item=race}
                                <li class="{if $race->isParticipant()}participant{/if} {if $race->isParticipationConfirmed()}participationConfirmed{/if}">
                                    <a href="{$race->getLink()}">{$race->title}</a>
                                </li>
                            {/foreach}
                        </ol>
                        {if $raceMonth->getDayRaces($day->getDayValue())|count > 4}
                            <a class="showMore" href="{link controller='RaceDay' year=$month->getYearValue() month=$month->getMonthValue() day=$day->getDayValue()}{/link}">{lang}siraca.calendar.monthView.showMoreRaces{/lang}</a>
                        {/if}
                    </div>
                {/if}
            {/if}
        </li>
        {if $tpl.foreach.days.iteration % 7 == 0}
            </ol>
        {/if}
    {/foreach}
</div>

{include file='footer'}