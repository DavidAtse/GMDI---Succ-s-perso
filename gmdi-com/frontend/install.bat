@echo off
echo ========================================================
echo   GMDI Communication - Installation Frontend Angular
echo   Republique de Cote d'Ivoire
echo ========================================================
echo.

echo [>>] Installation des dependances Node.js...
npm install
if errorlevel 1 ( echo [ERREUR] npm install echoue & pause & exit /b 1 )
echo [OK] Dependances installees

echo.
echo ========================================================
echo   Demarrage du serveur de developpement...
echo   URL : http://localhost:4200
echo   Proxy API : http://localhost:8000/api
echo ========================================================
echo.
echo    Assurez-vous que le backend Laravel est demarre
echo    (cd backend && php artisan serve)
echo.
npm start
