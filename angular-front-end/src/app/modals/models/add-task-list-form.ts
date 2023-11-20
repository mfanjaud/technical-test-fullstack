import { FormControl } from '@angular/forms';

export enum ADD_TASK_LIST_FIELDS {
  NAME = 'name',
  DESCRIPTION = 'description',
}

export interface AddTaskListFormGroup {
  [ADD_TASK_LIST_FIELDS.NAME]: FormControl<string | null>;
  [ADD_TASK_LIST_FIELDS.DESCRIPTION]: FormControl<string | null>;
}
