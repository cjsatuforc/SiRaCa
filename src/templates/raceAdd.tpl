{if $action == 'add'}
    {capture assign='pageTitle'}{lang}siraca.race.add.title{/lang}{/capture}
    {capture assign='contentTitle'}{lang}siraca.race.add.title{/lang}{/capture}
{else}
    {capture assign='pageTitle'}{lang}siraca.race.edit.title{/lang} {$race->title}{/capture}
    {capture assign='contentTitle'}{lang}siraca.race.edit.title{/lang} {$race->title}{/capture}
{/if}

{include file='header'}

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<div class="section">
    <form method="post" action="{if $action == 'add'}{link controller='RaceAdd'}{/link}{else}{link controller='RaceEdit' object=$race}{/link}{/if}">
        
        <dl {if $errorField == 'title'} class="formError"{/if}>
            <dt><label for="title">{lang}siraca.race.add.form.title{/lang}</label></dt>
            <dd>
                <input type="text" id="title" name="title" value="{$title}" required autofocus maxlength="255" class="long">
                {if $errorField == 'title'}
                    <small class="innerError">
                        {if $errorType == 'empty'}
                            {lang}wcf.global.form.error.empty{/lang}
                        {else}
                            {* TODO gestion d'erreur ? *}
                            {lang}siraca.race.title.error.{$errorType}{/lang}
                        {/if}
                    </small>
                {/if}
            </dd>
        </dl>

        <dl {if $errorField == 'startTime'} class="formError"{/if}>
            <dt><label for="startTime">{lang}siraca.race.add.form.startTime{/lang}</label></dt>
            <dd>
                <input type="datetime" id="startTime" name="startTime" class="medium"
                        {if $startTime|isset}value={$startTime} {/if}data-ignore-timezone="true" data-disable-clear="true">
                {if $errorField == 'startTime'}
                    <small class="innerError">
                        {if $errorType == 'empty'}
                            {lang}wcf.global.form.error.empty{/lang}
                        {else}
                            {lang}siraca.race.add.form.startTime.error.{@$errorType}{/lang}
                        {/if}
                    </small>
                {/if}
            </dd>
        </dl>
            
        <div class="formSubmit">
            <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
            {@SECURITY_TOKEN_INPUT_TAG}
        </div>
    </form>
</div>

<script data-relocate="true">
    $(function() {
        elById("test").addEventListener("click", function(){
            alert(elById("startTime").value);
        });
    });
</script>


{* DELETE RACE *}
{if $action == 'edit'}
{* <footer class="contentFooter">
		<nav class="contentFooterNavigation"> *}
            <div class="section">
                <div class="spoilerBox jsSpoilerBox">
                    <div class="jsOnly spoilerBoxHeader">
                        <a class="button small jsSpoilerToggle" data-has-custom-label="true">{lang}siraca.race.edit.form.delete.label{/lang}</a>
                    </div>

                    <div style="display: none">
                        <dl>
                            <dt></dt>
                            <dd>
                                <label><input type="checkbox" id="deleteRaceCheck" name="deleteRaceCheck" >{lang}siraca.race.edit.form.delete.label{/lang}</label>
                            </dd>
                        </dl>

                        <div class="formSubmit"><!-- TODO remplacer formSubmit par un autre style centré -->
                            <a class="button buttonPrimary" id="deleteRaceButton" name="deleteRaceButton">
                                    <span class="icon icon16 fa-trash"></span> <span>{lang}wcf.global.button.delete{/lang}</span></a>
                        </div>
                    </div>
                </div>
            </div>
		{* </nav>
</footer> *}

    <script data-relocate="true">
        elBySelAll('.jsSpoilerBox', null, function(spoilerBox) {
            spoilerBox.classList.remove('jsSpoilerBox');
            
            var toggleButton = elBySel('.jsSpoilerToggle', spoilerBox);
            var container = toggleButton.parentNode.nextElementSibling;
            
            toggleButton.addEventListener(WCF_CLICK_EVENT, function(event) {
                event.preventDefault();
                
                toggleButton.classList.toggle('active');
                window[(toggleButton.classList.contains('active') ? 'elShow' : 'elHide')](container);
                
                if (!elDataBool(toggleButton, 'has-custom-label')) {
                    toggleButton.textContent = (toggleButton.classList.contains('active')) ? 'Hide Spoiler' : 'Display Spoiler';
                }

                elById("deleteRaceCheck").checked = false;
            });
        });
    </script>

    <script data-relocate="true">
        $(function() {
            elById("deleteRaceButton").addEventListener("click", deleteRaceClickHandler);
            
            function deleteRaceClickHandler(event) {
                if($("#deleteRaceCheck").prop("checked")) {
                    confirmDeletion();
                }
                else {
                    // TODO message
                }
            }

            function confirmDeletion() {
                WCF.System.Confirmation.show(WCF.Language.get('{lang}siraca.race.edit.form.delete.confirm{/lang}'), function(action) {
					if (action === 'confirm') {
						deleteRace();
					}
				});
            }

            function deleteRace() {
                new WCF.Action.Proxy({
                        autoSend: true,
                        data: {
                            actionName: 'delete',
                            className: 'wcf\\data\\siraca\\race\\RaceAction',
                            objectIDs: [ {$race->raceID} ]
                        },
                        success: function(data) {
                            window.location = "{link controller='RaceList' encode=false}{/link}";
                        }
                    });
            }
        });
    </script>
{/if}

{include file='footer'}