import {
  Component,
  DestroyRef,
  OnDestroy,
  OnInit,
  inject,
} from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatGridListModule } from '@angular/material/grid-list';
import { TaskListComponent } from './component/task-list/task-list.component';
import { SearchFormComponent } from '@app/shared/search-form/search-form.component';
import { SortComponent } from '@app/shared/sort/sort.component';
import { TaskListService } from '@app/core/services/task-list.service';
import {
  Observable,
  ReplaySubject,
  first,
  map,
  switchMap,
  takeUntil,
  tap,
} from 'rxjs';
import { TaskList } from '@app/core/models/task-list';
import { ElementsModule } from '@app/elements/elements.module';
import { EmptyStateComponent } from '@app/shared/empty-state/empty-state.component';
import { DISPLAY_MODE } from '@app/core/enums/diplay-mode';
import { AuthService } from '@app/core/services/auth.service';

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
  ],
  templateUrl: './task-list-grid.component.html',
  styleUrl: './task-list-grid.component.scss',
})
export class TaskListGridComponent implements OnInit, OnDestroy {
  public taskLists$!: Observable<TaskList[]>;
  public isLoggedIn$?: Observable<boolean | null>;

  public DISPLAY_MODE = DISPLAY_MODE;

  // Used to cancel observables that could leave beyond their component
  private _destroySub = new ReplaySubject(1);

  constructor(
    private _taskListService: TaskListService,
    private authService: AuthService
  ) {}
  ngOnInit(): void {
    this.setObservables();
  }

  ngOnDestroy() {
    this._destroySub.next(true);
    this._destroySub.complete();
  }

  private setObservables(): void {
    this.taskLists$ = this._taskListService.getCollection().pipe(
      tap((res) => console.log(res)),
      map((res) => res.taskLists),
      takeUntil(this._destroySub)
    );

    this.isLoggedIn$ = this.authService.token.pipe(
      map((t) => t != null),
      takeUntil(this._destroySub)
    );
  }

  public addNewTaskList(): void {
    console.log('add new Task List');
  }
}
