import { Component, inject, OnInit } from '@angular/core';
import { RouterOutlet, RouterLink, RouterLinkActive } from '@angular/router';
import { FinancesService } from './core/services/finances.service';
import { AuthService } from './core/services/auth.service';
import { ToastService } from './core/services/toast.service';
import { FcfaPipe } from './shared/pipes/fcfa.pipe';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, RouterLink, RouterLinkActive, FcfaPipe],
  template: `
    @if (auth.connecte()) {
      <!-- Topbar -->
      <div class="topbar">
        <div class="tb-brand">
          <div class="tb-flag">
            <span></span><span></span><span></span>
          </div>
          <div>
            <div class="tb-title"><span>GMDI</span> — Module Finances</div>
            <div class="tb-sub">République de Côte d'Ivoire · Gestion Municipale</div>
          </div>
        </div>
        <div class="tb-user">
          <div class="av">{{ auth.initiales() }}</div>
          <span><strong>{{ auth.user()?.name }}</strong> · {{ auth.user()?.role }}</span>
          <button class="btn-logout" (click)="auth.logout()" title="Déconnexion">
            <i class="ti ti-logout"></i>
          </button>
        </div>
      </div>

      <!-- Layout -->
      <div class="layout">
        <nav class="sidebar">
          <div class="sb-logo">
            <div class="sb-logo-icon"><i class="ti ti-building-community"></i></div>
            <div class="sb-logo-text">
              Finances
              <small>Exercice 2025</small>
            </div>
          </div>
          <div class="sb-sec">Navigation</div>
          <a class="sb-item" routerLink="/budget"       routerLinkActive="act"><i class="ti ti-file-invoice"></i>Budget</a>
          <a class="sb-item" routerLink="/recettes"     routerLinkActive="act"><i class="ti ti-arrow-down-circle"></i>Recettes</a>
          <a class="sb-item" routerLink="/depenses"     routerLinkActive="act"><i class="ti ti-arrow-up-circle"></i>Dépenses</a>
          <a class="sb-item" routerLink="/comptabilite" routerLinkActive="act"><i class="ti ti-notebook"></i>Comptabilité</a>
          <a class="sb-item" routerLink="/tresorerie"   routerLinkActive="act"><i class="ti ti-building-bank"></i>Trésorerie</a>
          <a class="sb-item" routerLink="/rapports"     routerLinkActive="act"><i class="ti ti-report"></i>Rapports</a>
        </nav>

        <div class="main">
          <div class="hdr">
            <div class="hl">
              <i class="ti ti-coin hi"></i>
              <div>
                <h2>Finances communales</h2>
                <p>Gestion budgétaire et financière · Exercice 2025</p>
              </div>
            </div>
            <div class="mkpis">
              <div class="mk">
                <span class="mk-v">{{ svc.dashboardStats().totalRecettes | fcfa }}</span>
                <span class="mk-l">Recettes</span>
              </div>
              <div class="mk">
                <span class="mk-v">{{ svc.dashboardStats().totalDepenses | fcfa }}</span>
                <span class="mk-l">Dépenses</span>
              </div>
              <div class="mk">
                <span class="mk-v">{{ svc.dashboardStats().tauxExecution }}%</span>
                <span class="mk-l">Exécution</span>
              </div>
              <div class="mk">
                <span class="mk-v">{{ svc.dashboardStats().tauxDematerialise }}%</span>
                <span class="mk-l">Dématérialisé</span>
              </div>
            </div>
          </div>
          <router-outlet />
        </div>
      </div>
    } @else {
      <router-outlet />
    }

    <!-- Toasts -->
    @for (t of toast.toasts(); track t.id) {
      <div class="global-toast">
        <i class="ti ti-check"></i> {{ t.message }}
      </div>
    }
  `,
  styles: [`
    :host { display: block; }
    .btn-logout {
      background: none; border: none; cursor: pointer; color: inherit;
      padding: 4px 8px; border-radius: 6px; font-size: 16px;
      opacity: .7; transition: opacity .2s;
    }
    .btn-logout:hover { opacity: 1; background: rgba(255,255,255,.15); }
    .tb-user { display: flex; align-items: center; gap: 10px; }
    .global-toast {
      position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;
      background: #e8f8f0; color: #0f6e56; border: .5px solid #9fd9b8;
      padding: 10px 16px; border-radius: 8px; font-size: 13px;
      display: flex; align-items: center; gap: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,.1);
      animation: slideIn .2s ease;
    }
    @keyframes slideIn {
      from { transform: translateX(20px); opacity: 0; }
      to   { transform: translateX(0);    opacity: 1; }
    }
  `]
})
export class AppComponent implements OnInit {
  svc   = inject(FinancesService);
  auth  = inject(AuthService);
  toast = inject(ToastService);

  ngOnInit() {
    if (this.auth.connecte()) {
      this.svc.chargerTout();
    }
  }
}
