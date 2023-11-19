import { Component, EventEmitter, Input, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ElementsModule } from '@app/elements/elements.module';
import { Task } from '@app/core/models/task';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-task',
  standalone: true,
  imports: [CommonModule, ElementsModule, MatCheckboxModule, FormsModule],
  templateUrl: './task.component.html',
  styleUrl: './task.component.scss',
})
export class TaskComponent {
  @Input() task!: Task;
  @Input() isLoggedIn!: boolean;

  @Output() setTaskStatusEvent = new EventEmitter<{
    isDone: boolean;
    id: number;
  }>();
  @Output() deleteTaskEvent = new EventEmitter<number>();
}
