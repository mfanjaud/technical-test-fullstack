export interface User {
  id?: string;
  username: string;
}

export interface CreateUserReq extends User {
  email: string;
  password: string;
  confirmPassword: string;
}

export interface LogInUserReq {
  username: string;
  password: string;
}
