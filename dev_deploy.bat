@echo off

set functionsPath=cmd\functions.bat

echo Read user options
call cmd\user_options.bat || goto :error

if not exist "%woltlabWwwPath%" (
	echo Destination directory doesn't exist.
	goto :error
)

call:cat "Moving files" & call:task "Move lib"
if exist src\files\lib xcopy /Y /S src\files\lib\* "%woltlabWwwPath%\lib\" > nul || goto :error

call:task "Move templates"
if exist src\templates xcopy /Y /S src\templates\* "%woltlabWwwPath%\templates\" > nul || goto :error

call:task "Move acptemplates"
if exist src\acptemplates xcopy /Y /S src\acptemplates\* "%woltlabWwwPath%\acp\templates\" > nul || goto :error

:: COMPLETE
call:done "FILES DEPLOYED"
::timeout 2
goto :eof


:error
call:err "ERROR"
pause
goto:eof


:: Functions
:cat
call %functionsPath% cat "%~1" "%useColors%"
goto:eof

:task
call %functionsPath% task "%~1" "%useColors%"
goto:eof

:err
call %functionsPath% err "%~1" "%useColors%"
goto:eof

:done
call %functionsPath% done "%~1" "%useColors%"
goto:eof