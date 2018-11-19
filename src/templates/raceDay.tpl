{capture assign='pageTitle'}{lang}siraca.raceDay.title{/lang} {lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()} {lang}wcf.date.month.{$day->getMonth()->getMonthName()}{/lang} {$day->getMonth()->getYearValue()}{/capture}
{capture assign='contentTitle'}{lang}siraca.raceDay.title{/lang} {lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()} {lang}wcf.date.month.{$day->getMonth()->getMonthName()}{/lang} {$day->getMonth()->getYearValue()}{/capture}

{include file='header'}

{include file='_raceList' objects=$races}

<footer class="contentFooter">
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
                {/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

{include file='footer'}