import { FormControl } from '@angular/forms';

export enum SUBMIT_TYPE {
  LOGIN,
  SIGNUP,
}

export enum AUTH_FIELDS {
  EMAIL = 'email',
  USERNAME = 'username',
  PASSWORD = 'password',
  CONFIRMPASSWORD = 'confirmPassword',
}

export interface SignUpFormGroup {
  [AUTH_FIELDS.EMAIL]: FormControl<string | null>;
  [AUTH_FIELDS.USERNAME]: FormControl<string | null>;
  [AUTH_FIELDS.PASSWORD]: FormControl<string | null>;
  [AUTH_FIELDS.CONFIRMPASSWORD]: FormControl<string | null>;
}

export interface LogInFormGroup {
  [AUTH_FIELDS.PASSWORD]: FormControl<string | null>;
  [AUTH_FIELDS.USERNAME]: FormControl<string | null>;
}
