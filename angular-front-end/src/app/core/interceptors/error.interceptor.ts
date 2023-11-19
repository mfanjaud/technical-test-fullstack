import { HttpRequest, HttpHandlerFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { catchError, throwError } from 'rxjs';
import { AuthService } from '../services/auth.service';

export function errorInterceptor(
  request: HttpRequest<any>,
  next: HttpHandlerFn
) {
  const authService = inject(AuthService);
  return next(request).pipe(
    catchError((err) => {
      if ([401, 403].includes(err.status)) {
        // auto logout if 401 or 403 response returned from api
        authService.logoutUser();
      }

      const error = err.error?.message || err.statusText;
      console.error(err);
      return throwError(() => error);
    })
  );
}
