@echo off
set PHP_BIN=C:\Agricart\php\php.exe
set PORT=8080

echo Starting Agricart on http://localhost:%PORT%...
echo Press Ctrl+C to stop the server.

"%PHP_BIN%" -S localhost:%PORT% -t .
pause
