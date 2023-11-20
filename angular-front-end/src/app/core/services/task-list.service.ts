import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { environment } from '@environment/environment';
import { Observable, map } from 'rxjs';
import {
  AddTaskListRequest,
  TaskListCollectionResponse,
} from '../models/task-list';

@Injectable({ providedIn: 'root' })
export class TaskListService {
  constructor(private _httpClient: HttpClient) {}

  public getCollectionBy(
    pageNumber: number,
    itemsPerPage: number,
    sort: string | undefined,
    search: string | undefined
  ): Observable<TaskListCollectionResponse> {
    const apiFilterUrl = this.getApiFilterURLForGetCollection(sort, search);
    console.log(apiFilterUrl);
    return this._httpClient
      .get<any>(
        `${environment.apiUrl}/task_lists?page=${pageNumber}&itemsPerPage=${itemsPerPage}&${apiFilterUrl}`
      )
      .pipe(
        map((res) => ({
          totalItems: res['hydra:totalItems'],
          taskLists: res['hydra:member'],
        }))
      );
  }

  public createTaskList(taskList: AddTaskListRequest): Observable<void> {
    return this._httpClient.post<void>(
      `${environment.apiUrl}/task_lists`,
      taskList
    );
  }

  public deleteTaskList(taskId: number): Observable<void> {
    return this._httpClient.delete<void>(
      `${environment.apiUrl}/task_list/${taskId}`
    );
  }

  private getApiFilterURLForGetCollection(
    sort: string | undefined,
    search: string | undefined
  ): string {
    let url = '';
    if (sort) {
      url = url + `${sort}=DESC`;
    }

    if (search) {
      url = url + `name=${search}`;
    }

    return url;
  }
}
