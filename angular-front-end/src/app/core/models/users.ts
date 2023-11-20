export interface User {
  id?: string;
  username: string;
}

export interface CreateUserRequest extends User {
  email: string;
  password: string;
  confirmPassword: string;
}

export interface LogInUserRequest {
  username: string;
  password: string;
}
