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
import { TaskListCollectionResponse } from '../models/task-list';

@Injectable({ providedIn: 'root' })
export class TaskListService {
  private httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
  };

  constructor(private router: Router, private http: HttpClient) {}

  public getCollection(): Observable<TaskListCollectionResponse> {
    return this.http.get<any>(`${environment.apiUrl}/task_lists`).pipe(
      map((res) => ({
        totalItems: res['hydra:totalItems'],
        taskLists: res['hydra:member'],
      }))
    );
  }
}
