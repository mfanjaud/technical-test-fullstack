import { Component, OnDestroy, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ElementsModule } from '../../elements/elements.module';
import { MatDialogModule } from '@angular/material/dialog';
import { MatDialog } from '@angular/material/dialog';
import { AuthModalComponent } from '../../modals/auth-modal/auth-modal.component';
import { TokenResponse as Token } from '@app/core/models/token';
import { AuthService } from '@app/core/services/auth.service';
import { Observable, ReplaySubject, map, takeUntil } from 'rxjs';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, RouterModule, ElementsModule, MatDialogModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss',
})
export class NavbarComponent implements OnInit, OnDestroy {
  public logoPath = 'assets/images/mpp-logo.png';
  public isLoggedIn$?: Observable<boolean | null>;

  // Used to cancel observables that could leave beyond their component
  private _destroySub = new ReplaySubject(1);

  constructor(private _dialog: MatDialog, private _authService: AuthService) {}
  ngOnInit(): void {
    this.isLoggedIn$ = this._authService.token.pipe(
      map((t) => t != null),
      takeUntil(this._destroySub)
    );
  }

  ngOnDestroy() {
    this._destroySub.next(true);
    this._destroySub.complete();
  }

  public onConnect(): void {
    this._dialog.open(AuthModalComponent, {});
  }

  public onLogout(): void {
    this._authService.logoutUser();
  }
}
