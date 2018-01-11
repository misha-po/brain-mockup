@echo off
setlocal
set PATH=%PATH%;..\php-7.0.8-Win32-VC14-x64
php -S 0.0.0.0:8787 -t ".\doc_root"
endlocal
@echo on
