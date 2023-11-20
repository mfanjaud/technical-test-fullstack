import { Component, EventEmitter, Input, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatSelectModule } from '@angular/material/select';
import { SORT_FIELDS } from '@app/core/enums/sort-fields';

@Component({
  selector: 'app-sort',
  standalone: true,
  imports: [CommonModule, MatFormFieldModule, MatSelectModule],
  templateUrl: './sort.component.html',
  styleUrl: './sort.component.scss',
  host: {
    class: 'd-block',
  },
})
export class SortComponent {
  @Input() sortFields!: SORT_FIELDS[];

  @Output() sortEvent = new EventEmitter<string>();

  public selected!: SORT_FIELDS;
}
