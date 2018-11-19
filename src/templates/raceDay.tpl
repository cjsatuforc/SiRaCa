{capture assign='pageTitle'}{lang}siraca.raceDay.title{/lang} {lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()} {lang}wcf.date.month.{$day->getMonth()->getMonthName()}{/lang} {$day->getMonth()->getYearValue()}{/capture}
{capture assign='contentTitle'}{lang}siraca.raceDay.title{/lang} {lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()} {lang}wcf.date.month.{$day->getMonth()->getMonthName()}{/lang} {$day->getMonth()->getYearValue()}{/capture}

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
                    {if $__wcf->user->userID && $__wcf->session->getPermission('mod.siraca.canManageRace')}
                        <li>
                            <a href="{link controller='RaceAdd' startTimestamp=$day->getStartTime()}{/link}" class="button siracaAdminButton">
                                <span class="icon icon16 fa-plus"></span> <span>{lang}siraca.race.add.link{/lang}</span>
                            </a>
                        </li>
                    {/if}
                    {event name='contentFooterNavigation'}
                {/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

{include file='footer'}