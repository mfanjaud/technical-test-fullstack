import {
  HttpHandler,
  HttpHandlerFn,
  HttpInterceptor,
  HttpRequest,
  HttpResponse,
} from '@angular/common/http';
import { inject } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { environment } from '@environment/environment';

export function authInterceptor(req: HttpRequest<any>, next: HttpHandlerFn) {
  const oauthService: AuthService = inject(AuthService);
  const authUser = oauthService.tokenValue;
  const isApiUrl = req.url.startsWith(environment.apiUrl);
  if (isApiUrl && authUser) {
    req = req.clone({
      setHeaders: {
        Authorization: `Bearer ${authUser}`,
      },
    });
  }
  return next(req);
}
