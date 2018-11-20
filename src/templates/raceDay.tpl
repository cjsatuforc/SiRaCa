{capture assign='pageTitle'}{lang}siraca.raceDay.title{/lang} {$day->getTitle()}{/capture}
{capture assign='contentTitle'}{lang}siraca.raceDay.title{/lang} {$day->getTitle()}{/capture}

{include file='header'}

{assign var="yearValue" value=$day->getMonth()->getYearValue()}
{assign var="monthValue" value=$day->getMonth()->getMonthValue()}
{assign var="dayValue" value=$day->getDayValue()}

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="RaceDay" link="pageNo=%d&year=$yearValue&month=$monthValue&day=$dayValue"}
        {/content}
	</div>
{/hascontent}

{include file='_raceList'}

<footer class="contentFooter">
    {hascontent}
        <div class="paginationBottom">
            {content}{@$pagesLinks}{/content}
        </div>
    {/hascontent}

    {hascontent}
        <nav class="contentFooterNavigation">
            <ul>
                {content}
                    {include file="_organizeRaceButton" startTimestamp=$day->getStartTime()}
                    
                    {event name='contentFooterNavigation'}
                {/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

{include file='footer'}