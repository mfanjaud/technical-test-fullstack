import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { environment } from '@environment/environment';
import {
  CreateUserRequest,
  LogInUserRequest,
  User,
} from '@app/core/models/users';
import { BehaviorSubject, Observable, map, tap } from 'rxjs';
import { Router } from '@angular/router';
import { TokenResponse } from '../models/token';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
  };
  private tokenSubject: BehaviorSubject<string | null>;
  public token: Observable<string | null>;

  constructor(private router: Router, private http: HttpClient) {
    this.tokenSubject = new BehaviorSubject(
      JSON.parse(localStorage.getItem('token')!)
    );
    this.token = this.tokenSubject.asObservable();
  }

  public get tokenValue() {
    return this.tokenSubject.value;
  }

  public loginUser(userReq: LogInUserRequest): Observable<string> {
    return this.http
      .post<TokenResponse>(
        `${environment.apiUrl}/login_check`,
        userReq,
        this.httpOptions
      )
      .pipe(
        map((res) => {
          localStorage.setItem('token', JSON.stringify(res.token));
          this.tokenSubject.next(res.token);
          return res.token;
        })
      );
  }

  public logoutUser() {
    // remove user from local storage and set current user to null
    localStorage.removeItem('token');
    this.tokenSubject.next(null);
  }

  public createUser(user: CreateUserRequest): Observable<any> {
    return this.http.post(
      `${environment.apiUrl}/auth/register`,
      user,
      this.httpOptions
    );
  }

  public getAuthUser() {
    return JSON.parse(localStorage.getItem('token') as string);
  }

  get isLoggedIn() {
    if (localStorage.getItem('token')) {
      return true;
    }
    return false;
  }
}
