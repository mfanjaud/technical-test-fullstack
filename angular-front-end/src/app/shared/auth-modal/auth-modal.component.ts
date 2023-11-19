import { CommonModule } from '@angular/common';
import { HttpEvent } from '@angular/common/http';
import { Component, OnDestroy, OnInit } from '@angular/core';
import {
  FormBuilder,
  FormControl,
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
import { MatSelectModule } from '@angular/material/select';
import { AlertService } from '@app/core/services/alert.service';
import { ReplaySubject, filter, takeUntil, tap } from 'rxjs';
import { CreateUserReq } from '../../core/models/users';
import { AuthService } from '../../core/services/auth.service';
import { ElementsModule } from '../../elements/elements.module';

export enum SUBMIT_TYPE {
  LOGIN,
  SIGNUP,
}

const enum FIELDS {
  EMAIL = 'email',
  USERNAME = 'username',
  PASSWORD = 'password',
  CONFIRMPASSWORD = 'confirmPassword',
}

export interface SignUpFormGroup {
  [FIELDS.EMAIL]: FormControl<string | null>;
  [FIELDS.USERNAME]: FormControl<string | null>;
  [FIELDS.PASSWORD]: FormControl<string | null>;
  [FIELDS.CONFIRMPASSWORD]: FormControl<string | null>;
}

export interface LogInFormGroup {
  [FIELDS.PASSWORD]: FormControl<string | null>;
  [FIELDS.USERNAME]: FormControl<string | null>;
}

@Component({
  selector: 'app-auth',
  standalone: true,
  imports: [
    FormsModule,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatSelectModule,
    MatInputModule,
    ElementsModule,
    MatButtonToggleModule,
    CommonModule,
    MatButtonModule,
  ],
  templateUrl: './auth-modal.component.html',
  styleUrl: './auth-modal.component.scss',
})
export class AuthModalComponent implements OnInit, OnDestroy {
  public signUpFormGroup!: FormGroup;
  public logInFormGroup!: FormGroup;
  public hidePassword = true;
  public SUBMIT_TYPE = SUBMIT_TYPE; // expose to template
  public chosenSubmitType = SUBMIT_TYPE.LOGIN;

  // Pour annuler des observable qui vit en dehors de ce composant (router et route)
  private _destroyed$: ReplaySubject<boolean> = new ReplaySubject(1);

  constructor(
    public dialogRef: MatDialogRef<AuthModalComponent>,
    private _formBuilder: FormBuilder,
    private _usersService: AuthService,
    private _alertService: AlertService
  ) {}

  ngOnInit(): void {
    this.createForm();
  }

  ngOnDestroy() {
    this._destroyed$.next(true);
    this._destroyed$.complete();
  }

  private createForm(): void {
    this.signUpFormGroup = this._formBuilder.group<SignUpFormGroup>({
      [FIELDS.USERNAME]: this._formBuilder.control('', [Validators.required]),
      [FIELDS.EMAIL]: this._formBuilder.control('', [Validators.required]),
      [FIELDS.PASSWORD]: this._formBuilder.control('', Validators.required),
      [FIELDS.CONFIRMPASSWORD]: this._formBuilder.control(
        '',
        Validators.required
      ),
    });

    this.logInFormGroup = this._formBuilder.group<LogInFormGroup>({
      [FIELDS.USERNAME]: this._formBuilder.control('', [Validators.required]),
      [FIELDS.PASSWORD]: this._formBuilder.control('', Validators.required),
    });
  }

  public onLogin(): void {
    if (this.logInFormGroup.invalid) return;
    this._usersService
      .loginUser(this.logInFormGroup.getRawValue())
      .pipe(
        filter((res) => res !== ''),
        takeUntil(this._destroyed$)
      )
      .subscribe({
        next: (res) => {
          this._alertService.success('You are now connected !');
          this.dialogRef.close();
        },
        error: (error) => {
          this._alertService.error(error.error.message);
        },
      });
  }

  public onSignUp(): void {
    if (this.signUpFormGroup.invalid) return;
    const newUser: CreateUserReq = this.signUpFormGroup.getRawValue();
    this._usersService
      .createUser(newUser)
      .pipe(takeUntil(this._destroyed$))
      .subscribe({
        next: (res) => {
          this._alertService.success('You can acces your account now !');
          this.chosenSubmitType = SUBMIT_TYPE.LOGIN;
        },
        error: (error) => {
          this._alertService.error(error.error.message);
        },
      });
  }
}
