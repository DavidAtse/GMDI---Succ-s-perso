import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth.guard';
export const routes: Routes = [
  { path: '', redirectTo: 'communication', pathMatch: 'full' },
  { path: 'login', loadComponent: () => import('./pages/login/login.component').then(m => m.LoginComponent) },
  { path: 'communication', canActivate: [authGuard], loadComponent: () => import('./modules/communication/pages/shell/communication-shell.component').then(m => m.CommunicationShellComponent) },
  { path: '**', redirectTo: 'communication' },
];
