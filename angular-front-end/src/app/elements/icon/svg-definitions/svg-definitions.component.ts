import { Component, ElementRef, OnInit } from '@angular/core';
import { ElementsService } from '../../../core/services/elements.service';

@Component({
  selector: 'svg-definitions',
  templateUrl: './svg-definitions.component.html',
})
export class SvgDefinitionsComponent implements OnInit {
  public elRef: ElementRef;

  constructor(elRef: ElementRef, private elementsService: ElementsService) {
    this.elRef = elRef;
  }

  ngOnInit() {
    if (this.elementsService.svgHtml === undefined) {
      this.elementsService.svgHtml =
        this.elementsService.invokeSetSvgHtml.subscribe(() => {
          this.elementsService.setSvgHtml(this.convertSVGtoHtml());
        });
    }
  }

  ngOnDestroy() {
    this.elementsService.invokeSetSvgHtml.unsubscribe();
  }

  public convertSVGtoHtml(): string[] {
    const svgDefinitionHtml = this.elRef.nativeElement.innerHTML;
    const svgIds: any[] = [];

    svgDefinitionHtml
      .substring(0, svgDefinitionHtml.lastIndexOf('</defs>'))
      .substring(svgDefinitionHtml.indexOf('<symbol'))
      .trim()
      .replace(/id="/g, 'id="#')
      .split('</symbol>')
      .forEach((symbol: string, index: any, symbols: any[]) => {
        if (symbol === '') {
          symbols.splice(index, 1);
          return;
        }
        const symbolName = symbol.split('id="')[1].split('"')[0];
        svgIds.push(symbolName);
      });

    return svgIds;
  }
}
