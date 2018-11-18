{capture assign='pageTitle'}{lang}siraca.raceDay.title{/lang} {lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()} {lang}wcf.date.month.{$day->getMonth()->getMonthName()}{/lang} {$day->getMonth()->getYearValue()}{/capture}
{capture assign='contentTitle'}{lang}siraca.raceDay.title{/lang} {lang}wcf.date.day.{$day->getDayName()}{/lang} {$day->getDayValue()} {lang}wcf.date.month.{$day->getMonth()->getMonthName()}{/lang} {$day->getMonth()->getYearValue()}{/capture}

{include file='header'}

{include file='_raceList' objects=$races}

{include file='footer'}