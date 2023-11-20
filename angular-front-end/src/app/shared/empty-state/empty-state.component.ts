import { Component, EventEmitter, Input, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ElementsModule } from '@app/elements/elements.module';
import { DISPLAY_MODE } from '@app/core/enums/diplay-mode';

@Component({
  selector: 'app-empty-state',
  standalone: true,
  imports: [CommonModule, ElementsModule],
  templateUrl: './empty-state.component.html',
  styleUrl: './empty-state.component.scss',
})
export class EmptyStateComponent {
  @Input() mode!: DISPLAY_MODE;
  @Input() isLoggedIn!: boolean;
  @Input() buttonLabel!: string;

  @Output() clickActionEvent = new EventEmitter<void>();

  public DISPLAY_MODE = DISPLAY_MODE;
}
