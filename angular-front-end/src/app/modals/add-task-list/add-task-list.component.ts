import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { MatButtonModule } from '@angular/material/button';
import { MatButtonToggleModule } from '@angular/material/button-toggle';
import { MatDialogRef } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { AddTaskListRequest } from '@app/core/models/task-list';
import { AlertService } from '@app/core/services/alert.service';
import { TaskListService } from '@app/core/services/task-list.service';
import { ElementsModule } from '@app/elements/elements.module';
import {
  ADD_TASK_LIST_FIELDS,
  AddTaskListFormGroup,
} from '@app/modals/models/add-task-list-form';
import { ReplaySubject, takeUntil } from 'rxjs';

@Component({
  selector: 'app-add-task-list',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    ElementsModule,
    MatButtonToggleModule,
    MatButtonModule,
  ],
  templateUrl: './add-task-list.component.html',
  styleUrl: './add-task-list.component.scss',
})
export class AddTaskListComponent {
  public addTaskListForm!: FormGroup<AddTaskListFormGroup>;

  // Pour annuler des observable qui vit en dehors de ce composant (router et route)
  private _destroyed$: ReplaySubject<boolean> = new ReplaySubject(1);

  constructor(
    private _dialogRef: MatDialogRef<AddTaskListComponent>,
    private _formBuilder: FormBuilder,
    private _taskListService: TaskListService,
    private _alertService: AlertService
  ) {}

  ngOnInit(): void {
    this.addTaskListForm = this._formBuilder.group<AddTaskListFormGroup>({
      [ADD_TASK_LIST_FIELDS.NAME]: this._formBuilder.control<string>('', [
        Validators.required,
      ]),
      [ADD_TASK_LIST_FIELDS.DESCRIPTION]: this._formBuilder.control(''),
    });
  }

  ngOnDestroy() {
    this._destroyed$.next(true);
    this._destroyed$.complete();
  }

  public onAddTaskList(): void {
    if (this.addTaskListForm.invalid) return;
    this._taskListService
      .createTaskList(this.addTaskListForm.getRawValue() as AddTaskListRequest)
      .pipe(takeUntil(this._destroyed$))
      .subscribe({
        next: (res) => {
          this._alertService.success('Task List added !');
          this._dialogRef.close();
        },
        error: (error) => {
          this._alertService.error(error.error.message);
        },
      });
  }
}
