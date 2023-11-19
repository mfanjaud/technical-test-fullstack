import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { ElementsService } from './elements.service';
import { IconComponent } from './icon/icon.component';
import { SvgDefinitionsComponent } from './icon/svg-definitions/svg-definitions.component';

@NgModule({
  imports: [CommonModule, RouterModule],
  declarations: [IconComponent, SvgDefinitionsComponent],
  providers: [ElementsService],
  exports: [IconComponent, SvgDefinitionsComponent],
})
export class ElementsModule {}
