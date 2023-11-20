import { EventEmitter, Injectable } from '@angular/core';
import { BehaviorSubject, Subscription } from 'rxjs';

@Injectable()
export class ElementsService {
  public invokeSetSvgHtml = new EventEmitter();
  public svgHtml = new Subscription();

  private _setSvgSubject = new BehaviorSubject<string[]>([]);

  public onloadSetSvgHtml() {
    this.invokeSetSvgHtml.emit();
  }

  public getSvgHtml() {
    return this._setSvgSubject.asObservable();
  }

  public setSvgHtml(svgConvertedHtml: string[]) {
    this._setSvgSubject.next(svgConvertedHtml);
  }
}
