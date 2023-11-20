import { User } from './users';

export interface Task {
  id: number;
  name: string;
  completed: boolean;
  dueDate: Date;
  author: User;
}

export interface CreateTaskRequest {
  name: string;
  dueDate: Date;
  taskList: string;
}
