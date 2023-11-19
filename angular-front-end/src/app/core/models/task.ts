import { User } from './users';

export interface Task {
  id: number;
  name: string;
  completed: boolean;
  dueDate: Date;
  author: User;
}
