import { Task } from './task';
import { User } from './users';

export interface TaskListCollectionResponse {
  totalItems: number;
  taskLists: TaskList[];
}

export interface TaskList {
  id: number;
  name: string;
  description: string;
  author: User;
  createdAt: Date;
  tasks: Task[];
}
