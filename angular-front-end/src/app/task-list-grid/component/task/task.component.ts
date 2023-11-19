import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ElementsModule } from '@app/elements/elements.module';

@Component({
  selector: 'app-task',
  standalone: true,
  imports: [CommonModule, ElementsModule],
  templateUrl: './task.component.html',
  styleUrl: './task.component.scss',
})
export class TaskComponent {}
