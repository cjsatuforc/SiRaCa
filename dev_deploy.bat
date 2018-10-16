@echo off

set woltlabPath=E:\easyPhp\eds-www\woltlab

if exist src\files\lib xcopy /Y /S src\files\lib\* %woltlabPath%\lib\  || goto :error
if exist src\templates xcopy /Y /S src\templates\* %woltlabPath%\templates\  || goto :error
if exist src\acptemplates xcopy /Y /S src\acptemplates\* %woltlabPath%\acp\templates\  || goto :error

:: COMPLETE
echo [102m[30mFILES DEPLOYED[0m
::timeout 2
goto :eof

:error
echo [101m[30mERROR[0m
pause
goto :eof