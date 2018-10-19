@echo off

setlocal enabledelayedexpansion

set sourceFolder=src
set tempPackageFolder=temp_package
set foldersToArchive=files files_update templates acptemplates
set packageFileName=fr.chatcureuil.siraca
set archiveFileName=%packageFileName%.tar.gz

set functionsPath=cmd\functions.bat

echo Read user options
call cmd\user_options.bat || goto:error

call:cat Preparation & call:task "Remove temporary folder"
if exist "%tempPackageFolder%" rmdir /S /Q "%tempPackageFolder%" || goto:error

call:task "Remove old package"
if exist "%archiveFileName%" del "%archiveFileName%" || goto:error

call:task "Create temporary folder"
mkdir "%tempPackageFolder%" || goto:error


call:cat Directories
for /d %%i in ("%sourceFolder%\*") do (
	set isArchive=false
	
	for %%j in (%foldersToArchive%) do (
		if %%~ni == %%j (
			set isArchive=true
		)
	)
	
	if !isArchive! == true (
		call:task "Tar %%i"
		%zip% a -ttar "%tempPackageFolder%\%%~ni.tar" ".\%%i\*" > nul || goto:error
	) else (
		call:task "Copy %%i"
		mkdir "%tempPackageFolder%\%%~ni" || goto:error
		xcopy /Y /S "%%i" "%tempPackageFolder%\%%~ni" > nul || goto:error
	)
)


call:cat Files
for /r %%i in ("%sourceFolder%\*") do (
	call:task "Copy %%~nxi"
	copy "%%i" "%tempPackageFolder%" > nul || goto:error
)


call:cat Package & call:task "Create package %packageFileName%"
%zip% a -ttar -so "%packageFileName%.tar" "./%tempPackageFolder%/*" | %zip% a -tgzip -si "%archiveFileName%" > nul || goto:error


call:cat Clean & call:task "Remove temporary folder"
if exist "%tempPackageFolder%" rmdir /S /Q "%tempPackageFolder%" || goto:error


:: COMPLETE
call:done "PACKAGE CREATED"
::timeout 2
goto :eof


:error
call:err ERROR
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