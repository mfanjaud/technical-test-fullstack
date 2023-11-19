import { EventEmitter, Injectable } from '@angular/core';
import { BehaviorSubject, Subscription } from 'rxjs';

@Injectable()
export class ElementsService {
  private setSvgSubject = new BehaviorSubject<string[]>([]);
  public invokeSetSvgHtml = new EventEmitter();
  public svgHtml = new Subscription();

  public onloadSetSvgHtml() {
    this.invokeSetSvgHtml.emit();
  }

  public getSvgHtml() {
    return this.setSvgSubject.asObservable();
  }

  public setSvgHtml(svgConvertedHtml: string[]) {
    this.setSvgSubject.next(svgConvertedHtml);
  }
}
