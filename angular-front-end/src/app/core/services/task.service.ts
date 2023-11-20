import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { environment } from '@environment/environment';
import { Observable } from 'rxjs';
import { CreateTaskRequest, Task } from '../models/task';

@Injectable({ providedIn: 'root' })
export class TaskService {
  constructor(private _httpClient: HttpClient) {}

  public createTask(task: CreateTaskRequest): Observable<Task> {
    return this._httpClient.post<Task>(`${environment.apiUrl}/tasks`, task);
  }

  public deleteTask(taskId: number): Observable<void> {
    return this._httpClient.delete<void>(
      `${environment.apiUrl}/task/${taskId}`
    );
  }
}
