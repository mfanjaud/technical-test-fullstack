import { Component, Inject, OnDestroy, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import {
  ADD_TASK_FIELDS,
  AddTaskFormGroup,
} from '@app/modals/models/add-task-form';
import { ReplaySubject, takeUntil } from 'rxjs';
import { MatButtonModule } from '@angular/material/button';
import { MatButtonToggleModule } from '@angular/material/button-toggle';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { ElementsModule } from '@app/elements/elements.module';
import { MatNativeDateModule } from '@angular/material/core';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { TaskService } from '@app/core/services/task.service';
import { CreateTaskRequest } from '@app/core/models/task';
import { AlertService } from '@app/core/services/alert.service';

interface DialogData {
  taskLisId: number;
}

@Component({
  selector: 'app-add-task',
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
    MatDatepickerModule,
    MatNativeDateModule,
  ],
  providers: [MatDatepickerModule],
  templateUrl: './add-task.component.html',
  styleUrl: './add-task.component.scss',
})
export class AddTaskComponent implements OnInit, OnDestroy {
  public addTaskForm!: FormGroup<AddTaskFormGroup>;
  public now = new Date();

  // Pour annuler des observable qui vit en dehors de ce composant (router et route)
  private _destroyed$: ReplaySubject<boolean> = new ReplaySubject(1);

  constructor(
    private _dialogRef: MatDialogRef<AddTaskComponent>,
    private _formBuilder: FormBuilder,
    private _taskService: TaskService,
    private _alertService: AlertService,
    @Inject(MAT_DIALOG_DATA) public data: DialogData
  ) {}

  ngOnInit(): void {
    this.addTaskForm = this._formBuilder.group<AddTaskFormGroup>({
      [ADD_TASK_FIELDS.NAME]: this._formBuilder.control<string>('', [
        Validators.required,
      ]),
      [ADD_TASK_FIELDS.DUE_DATE]: this._formBuilder.control(new Date(), [
        Validators.required,
      ]),
      [ADD_TASK_FIELDS.TASK_LIST]: this._formBuilder.control(
        `/api/task_list/${this.data.taskLisId}`,
        Validators.required
      ),
    });
  }

  ngOnDestroy() {
    this._destroyed$.next(true);
    this._destroyed$.complete();
  }

  public onAddTask(): void {
    if (this.addTaskForm.invalid) return;
    this._taskService
      .createTask(this.addTaskForm.getRawValue() as CreateTaskRequest)
      .pipe(takeUntil(this._destroyed$))
      .subscribe({
        next: (res) => {
          this._alertService.success('Task added !');
          this._dialogRef.close();
        },
        error: (error) => {
          this._alertService.error(error.error.message);
        },
      });
  }
}
