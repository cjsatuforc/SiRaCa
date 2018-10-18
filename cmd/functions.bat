@echo off

if "%~3" == "true" (
	set catBkg=[46m
	set catFgd=[97m
	set taskDot=[96m
	set taskText=[36m
	set errBkg=[101m
	set errFgd=[30m
	set doneBkg=[102m
	set doneFgd=[30m
)

if %~1 == cat (call:cat "%~2" & goto:eof)
if %~1 == task (call:task "%~2" & goto:eof)
if %~1 == err (call:err "%~2" & goto:eof)
if %~1 == done (call:done "%~2" & goto:eof)
echo %~2

goto:eof

:: Functions
:cat
echo.
echo %catBkg%%catFgd%%~1[0m
goto:eof

:task
echo %taskDot%  .%taskText% %~1[0m
goto:eof

:err
echo.
echo %errBkg%%errFgd%%~1[0m
goto:eof

:done
echo.
echo %doneBkg%%doneFgd%%~1[0m
goto:eof