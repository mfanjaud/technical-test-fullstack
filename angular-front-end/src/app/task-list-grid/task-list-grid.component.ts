import { CommonModule } from '@angular/common';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatPaginatorModule, PageEvent } from '@angular/material/paginator';
import { DISPLAY_MODE } from '@app/core/enums/diplay-mode';
import { SORT_FIELDS } from '@app/core/enums/sort-fields';
import { TaskList } from '@app/core/models/task-list';
import { AlertService } from '@app/core/services/alert.service';
import { AuthService } from '@app/core/services/auth.service';
import { TaskListService } from '@app/core/services/task-list.service';
import { TaskService } from '@app/core/services/task.service';
import { ElementsModule } from '@app/elements/elements.module';
import { AddTaskListComponent } from '@app/modals/add-task-list/add-task-list.component';
import { AddTaskComponent } from '@app/modals/add-task/add-task.component';
import { EmptyStateComponent } from '@app/shared/empty-state/empty-state.component';
import { SearchFormComponent } from '@app/shared/search-form/search-form.component';
import { SortComponent } from '@app/shared/sort/sort.component';
import {
  Observable,
  ReplaySubject,
  Subject,
  map,
  startWith,
  switchMap,
  takeUntil,
  tap,
} from 'rxjs';
import { TaskListComponent } from './component/task-list/task-list.component';

interface RequestBody {
  pageIndex: number;
  pageSize: number;
  sort: string | undefined;
  search: string | undefined;
}

@Component({
  selector: 'app-task-list-grid',
  standalone: true,
  imports: [
    CommonModule,
    MatGridListModule,
    TaskListComponent,
    SearchFormComponent,
    SortComponent,
    ElementsModule,
    EmptyStateComponent,
    MatPaginatorModule,
  ],
  templateUrl: './task-list-grid.component.html',
  styleUrl: './task-list-grid.component.scss',
})
export class TaskListGridComponent implements OnInit, OnDestroy {
  public pageSizes = [6, 12, 24, 100];
  public taskListSortFields = [SORT_FIELDS.NAME, SORT_FIELDS.CREATED_AT];

  public taskLists$!: Observable<TaskList[]>;
  public isLoggedIn$?: Observable<boolean | null>;
  public loadGridSub = new Subject<RequestBody>();

  public DISPLAY_MODE = DISPLAY_MODE;

  public paginatorSetup = {
    pageIndex: 0,
    pageSize: 6,
    length: 100,
    sort: undefined,
    search: undefined,
  };

  // Used to cancel observables that could leave beyond their component
  private _destroySub = new ReplaySubject(1);

  constructor(
    private _taskListService: TaskListService,
    private _taskService: TaskService,
    private _authService: AuthService,
    private _alertService: AlertService,
    private _dialog: MatDialog
  ) {}
  ngOnInit(): void {
    this.setObservables();
  }

  ngOnDestroy() {
    this._destroySub.next(true);
    this._destroySub.complete();
  }

  private setObservables(): void {
    this.isLoggedIn$ = this._authService.token.pipe(
      map((t) => t != null),
      takeUntil(this._destroySub)
    );

    this.taskLists$ = this.loadGridSub.pipe(
      startWith(this.paginatorSetup),
      switchMap((p) => {
        const page = p.pageIndex + 1;
        return this._taskListService.getCollectionBy(
          page,
          p.pageSize,
          p.sort,
          p.search
        );
      }),
      map((res) => res.taskLists),
      takeUntil(this._destroySub)
    );
  }

  public addNewTaskList(): void {
    this._dialog
      .open(AddTaskListComponent, {})
      .afterClosed()
      .subscribe(() => {
        this.loadGridSub.next(this.paginatorSetup);
      });
  }

  public addNewTaskToList(taskListId: number): void {
    this._dialog
      .open(AddTaskComponent, {
        data: { taskLisId: taskListId },
      })
      .afterClosed()
      .subscribe(() => {
        this.loadGridSub.next(this.paginatorSetup);
      });
  }

  public deleteTaskList(taskListId: number): void {
    this._taskListService
      .deleteTaskList(taskListId)
      .pipe(takeUntil(this._destroySub))
      .subscribe({
        next: () => {
          this.loadGridSub.next(this.paginatorSetup);
          this._alertService.success(
            `Task list n° ${taskListId} successfully deleted`
          );
        },
        error: (error) => {
          this._alertService.error(error.error.message);
        },
      });
  }

  public deleteTask(taskId: number): void {
    this._taskService
      .deleteTask(taskId)
      .pipe(takeUntil(this._destroySub))
      .subscribe({
        next: () => {
          this.loadGridSub.next(this.paginatorSetup);
          this._alertService.success(`Task n° ${taskId} successfully deleted`);
        },
        error: (error) => {
          this._alertService.error(error.error.message);
        },
      });
  }

  public sortTaskList(sort: string) {
    const paginator = { ...this.paginatorSetup, sort: sort };
    this.loadGridSub.next(paginator);
  }

  public searchTaskList(search: string) {
    const paginator = { ...this.paginatorSetup, search: search };
    this.loadGridSub.next(paginator);
  }

  public loagGridOnChange($event: PageEvent) {
    this.paginatorSetup.pageIndex = $event.pageIndex;
    this.paginatorSetup.pageSize = $event.pageSize;
    this.loadGridSub.next(this.paginatorSetup);
  }
}
