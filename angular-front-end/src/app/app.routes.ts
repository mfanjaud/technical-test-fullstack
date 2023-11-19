import { Routes } from '@angular/router';
import { TaskListGridComponent } from './task-list-grid/task-list-grid.component';

export const routes: Routes = [
  { path: '', component: TaskListGridComponent },
  { path: '**', redirectTo: 'not-found' },
];
