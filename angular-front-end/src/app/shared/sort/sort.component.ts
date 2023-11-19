import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatSelectModule } from '@angular/material/select';

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
  public selected = 'option2';
}
