import { Component, signal, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  template: `
<div class="login-root">
  <div class="login-card">
    <div class="login-header">
      <div class="login-logo"><i class="ti ti-speakerphone"></i></div>
      <div class="login-title">GMDI — Communication</div>
      <div class="login-sub">Direction de la Communication et des Relations Publiques</div>
    </div>
    <div class="login-body">
      @if (error()) { <div class="login-error"><i class="ti ti-alert-circle"></i>{{ error() }}</div> }
      <div class="fg">
        <div class="fl">Adresse e-mail</div>
        <input class="fi" type="email" [(ngModel)]="email" placeholder="votre@mairie.ci" [disabled]="loading()" (keyup.enter)="login()"/>
      </div>
      <div class="fg" style="margin-top:10px">
        <div class="fl">Mot de passe</div>
        <div style="position:relative">
          <input class="fi" [type]="showPwd()?'text':'password'" [(ngModel)]="password" [disabled]="loading()" (keyup.enter)="login()" style="padding-right:36px"/>
          <button type="button" (click)="showPwd.set(!showPwd())" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#6b7280;padding:0">
            <i class="ti" [class.ti-eye]="!showPwd()" [class.ti-eye-off]="showPwd()"></i>
          </button>
        </div>
      </div>
      <button class="login-btn" [disabled]="loading()" (click)="login()" style="margin-top:18px">
        @if (loading()) { <i class="ti ti-loader-2" style="animation:spin 1s linear infinite"></i>Connexion… }
        @else { <i class="ti ti-login"></i>Se connecter }
      </button>
    </div>
    <div class="login-footer">
      <span>République de Côte d'Ivoire</span><span>·</span><span>GMDI v2.0</span><span>·</span><span>UVCI — FabLab</span>
    </div>
  </div>
</div>
<style>
.login-root { min-height:100vh;background:linear-gradient(135deg,#003366 0%,#004080 40%,#F77F00 100%);display:flex;align-items:center;justify-content:center;font-family:'Inter',system-ui,sans-serif;padding:1rem; }
.login-card { background:#fff;border-radius:12px;width:100%;max-width:380px;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden; }
.login-header { background:linear-gradient(135deg,#003366,#F77F00);padding:2rem 1.5rem 1.5rem;text-align:center; }
.login-logo { width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:26px;color:#fff; }
.login-title { color:#fff;font-size:15px;font-weight:600; }
.login-sub { color:rgba(255,255,255,.75);font-size:11px;margin-top:3px; }
.login-body { padding:1.5rem; }
.login-error { background:#fce8e8;color:#a32d2d;border:.5px solid #f5c6c6;border-radius:6px;padding:8px 12px;font-size:12px;margin-bottom:12px;display:flex;align-items:center;gap:6px; }
.fg { display:flex;flex-direction:column;gap:4px; }
.fl { font-size:11px;color:#6b7280;font-weight:500; }
.fi { height:36px;border:1px solid #d1d5db;border-radius:7px;padding:0 10px;font-size:13px;width:100%;outline:none;transition:all .15s; }
.fi:focus { border-color:#F77F00;box-shadow:0 0 0 3px rgba(247,127,0,.12); }
.fi:disabled { background:#f9fafb;opacity:.7; }
.login-btn { width:100%;height:40px;background:#F77F00;color:#003366;border:none;border-radius:7px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:opacity .15s; }
.login-btn:hover:not(:disabled) { opacity:.88; }
.login-btn:disabled { opacity:.55;cursor:not-allowed; }
.login-footer { border-top:.5px solid #e5e7eb;padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:center;gap:6px;font-size:10px;color:#9ca3af; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>
  `
})
export class LoginComponent {
  private auth   = inject(AuthService);
  private router = inject(Router);
  email    = '';
  password = '';
  loading  = signal(false);
  error    = signal('');
  showPwd  = signal(false);

  login(): void {
    if (!this.email || !this.password) { this.error.set('Veuillez renseigner vos identifiants.'); return; }
    this.loading.set(true); this.error.set('');
    this.auth.login({ email: this.email, password: this.password }).subscribe({
      next: () => this.router.navigate(['/communication']),
      error: (err: Error) => { this.error.set(err.message); this.loading.set(false); }
    });
  }
}
