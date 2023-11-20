import { FormControl } from '@angular/forms';

export enum ADD_TASK_FIELDS {
  NAME = 'name',
  DUE_DATE = 'dueDate',
  TASK_LIST = 'taskList',
}

export interface AddTaskFormGroup {
  [ADD_TASK_FIELDS.NAME]: FormControl<string | null>;
  [ADD_TASK_FIELDS.DUE_DATE]: FormControl<Date | null>;
  [ADD_TASK_FIELDS.TASK_LIST]: FormControl<string | null>;
}
