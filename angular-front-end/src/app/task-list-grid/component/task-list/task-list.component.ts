import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { MatDividerModule } from '@angular/material/divider';
import { TaskComponent } from '../task/task.component';
import { ElementsModule } from '@app/elements/elements.module';
import { SearchFormComponent } from '@app/shared/search-form/search-form.component';
import { TaskList } from '@app/core/models/task-list';
import { EmptyStateComponent } from '@app/shared/empty-state/empty-state.component';
import { DISPLAY_MODE } from '@app/core/enums/diplay-mode';

@Component({
  selector: 'app-task-list',
  standalone: true,
  imports: [
    CommonModule,
    MatCardModule,
    MatDividerModule,
    MatButtonModule,
    TaskComponent,
    ElementsModule,
    SearchFormComponent,
    EmptyStateComponent,
  ],
  templateUrl: './task-list.component.html',
  styleUrl: './task-list.component.scss',
  host: {
    class: 'w-100 h-100',
  },
})
export class TaskListComponent {
  @Input() taskList!: TaskList;
  @Input() isLoggedIn!: boolean;

  @Output() clickaddTaskEvent = new EventEmitter<number>();
  @Output() deleteTaskEvent = new EventEmitter<number>();
  @Output() deleteTaskListEvent = new EventEmitter<number>();

  public DISPLAY_MODE = DISPLAY_MODE;
  public addLabel = 'Add a new task';
}
