import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { Router } from '@angular/router';
import { CreateUserRequest, LogInUserRequest } from '@app/core/models/users';
import { environment } from '@environment/environment';
import { BehaviorSubject, Observable, map } from 'rxjs';
import { TokenResponse } from '../models/token';

@Injectable({ providedIn: 'root' })
export class AuthService {
  public token: Observable<string | null>;

  private _httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
  };
  private _tokenSubject: BehaviorSubject<string | null>;

  constructor(private _httpClient: HttpClient) {
    this._tokenSubject = new BehaviorSubject(
      JSON.parse(localStorage.getItem('token')!)
    );
    this.token = this._tokenSubject.asObservable();
  }

  public get tokenValue() {
    return this._tokenSubject.value;
  }

  public loginUser(userReq: LogInUserRequest): Observable<string> {
    return this._httpClient
      .post<TokenResponse>(
        `${environment.apiUrl}/login_check`,
        userReq,
        this._httpOptions
      )
      .pipe(
        map((res) => {
          localStorage.setItem('token', JSON.stringify(res.token));
          this._tokenSubject.next(res.token);
          return res.token;
        })
      );
  }

  public logoutUser() {
    // remove user from local storage and set current user to null
    localStorage.removeItem('token');
    this._tokenSubject.next(null);
  }

  public createUser(user: CreateUserRequest): Observable<any> {
    return this._httpClient.post(
      `${environment.apiUrl}/auth/register`,
      user,
      this._httpOptions
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
