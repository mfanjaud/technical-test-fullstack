import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ElementsModule } from '../../elements/elements.module';
import { MatDialogModule } from '@angular/material/dialog';
import { MatDialog } from '@angular/material/dialog';
import { AuthModalComponent } from '../auth-modal/auth-modal.component';
import { TokenResponse as Token } from '@app/core/models/token';
import { AuthService } from '@app/core/services/auth.service';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, RouterModule, ElementsModule, MatDialogModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss',
})
export class NavbarComponent {
  public logoPath = 'assets/images/mpp-logo.png';
  public isLoggedIn?: boolean | null;

  constructor(private dialog: MatDialog, private authService: AuthService) {
    this.authService.token.subscribe((x) => (this.isLoggedIn = x !== null));
  }

  public onConnect(): void {
    this.dialog.open(AuthModalComponent, {});
  }

  public onLogout(): void {
    this.authService.logoutUser();
  }
}
