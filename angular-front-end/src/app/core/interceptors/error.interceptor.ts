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
      if (err.status === 401) {
        // auto logout if 401 response returned from api
        authService.logoutUser();
      }

      const error = err.error?.message || err.statusText;
      return throwError(() => error);
    })
  );
}
