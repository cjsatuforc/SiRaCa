@echo off

setlocal enabledelayedexpansion

set sourceFolder=src
set tempPackageFolder=temp_package
set subFolders=files files_update templates acptemplates
set packageFileName=fr.chatcureuil.siraca.tar
set zip="C:\Program Files\7-Zip\7z.exe"
set batchName=%~n0%~x0

echo Clean
if exist "%tempPackageFolder%" rmdir /S /Q "%tempPackageFolder%"
if exist "%packageFileName%" (del "%packageFileName%")

echo Create temporary folder %tempPackageFolder%
mkdir "%tempPackageFolder%" || goto :error

for /d %%i in ("%sourceFolder%\*") do (
	set isArchive=false
	
	for %%j in (%subFolders%) do (
		if %%~ni == %%j (
			set isArchive=true
		)
	)
	
	if !isArchive! == true (
		echo Tar %%i
		%zip% a "%tempPackageFolder%\%%~ni.tar" ".\%%i\*" > nul || goto :error
	) else (
		echo Copy %%i
		mkdir "%tempPackageFolder%\%%~ni" || goto :error
		copy "%%i" "%tempPackageFolder%\%%~ni" > nul || goto :error
	)
)

for /r %%i in ("%sourceFolder%\*") do (
	echo Copy %%~nxi
	copy "%%i" "%tempPackageFolder%" > nul || goto :error
)

echo Create package %packageFileName%
%zip% a "%packageFileName%" "./%tempPackageFolder%/*" > nul || goto :error

echo Clean
if exist "%tempPackageFolder%" rmdir /S /Q "%tempPackageFolder%" || goto :error

echo [102m[30mCOMPLETE[0m
rem timeout 1
goto :eof

:error
echo [101m[30mERROR[0m
pause
goto :eof

