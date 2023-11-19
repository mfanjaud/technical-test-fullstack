import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatGridListModule } from '@angular/material/grid-list';
import { TaskListComponent } from './component/task-list/task-list.component';
import { SearchFormComponent } from '@app/shared/search-form/search-form.component';
import { SortComponent } from '@app/shared/sort/sort.component';

@Component({
  selector: 'app-task-list-grid',
  standalone: true,
  imports: [
    CommonModule,
    MatGridListModule,
    TaskListComponent,
    SearchFormComponent,
    SortComponent,
  ],
  templateUrl: './task-list-grid.component.html',
  styleUrl: './task-list-grid.component.scss',
})
export class TaskListGridComponent {}
