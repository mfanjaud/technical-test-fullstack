import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { ElementsModule } from '@app/elements/elements.module';

@Component({
  selector: 'app-search-form',
  standalone: true,
  imports: [
    CommonModule,
    MatFormFieldModule,
    MatInputModule,
    ReactiveFormsModule,
    ElementsModule,
  ],
  templateUrl: './search-form.component.html',
  styleUrl: './search-form.component.scss',
  host: {
    class: 'd-block w-100',
  },
})
export class SearchFormComponent {
  @Input() disabled!: boolean;

  @Output() searchEvent = new EventEmitter<string>();

  public searchForm = new FormGroup({
    searchControl: new FormControl(''),
  });

  public emitSearchEvent(): void {
    const search = this.searchForm.getRawValue().searchControl ?? '';
    this.searchEvent.emit(search);
  }
}
