import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
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
  public searchForm = new FormGroup({
    searchControl: new FormControl(''),
  });
}
