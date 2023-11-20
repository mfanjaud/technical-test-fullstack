import { inject } from '@angular/core';
import { CanActivateFn } from '@angular/router';
import { AuthService } from '../services/auth.service';

export function authenticationGuard(): CanActivateFn {
  return () => {
    const oauthService: AuthService = inject(AuthService);

    if (oauthService.getAuthUser()) {
      return true;
    }
    return false;
  };
}
