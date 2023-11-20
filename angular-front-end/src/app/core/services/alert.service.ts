import { Injectable } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router, NavigationStart } from '@angular/router';
import { Observable, Subject } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AlertService {
  private _subject = new Subject<any>();
  private _showAfterRedirect = false;

  constructor(private _router: Router, private _snackBar: MatSnackBar) {
    // clear alert messages on route change unless 'showAfterRedirect' flag is true
    this._router.events.subscribe((event) => {
      if (event instanceof NavigationStart) {
        if (this._showAfterRedirect) {
          // only keep for a single route change
          this._showAfterRedirect = false;
        } else {
          // clear alert message
          this.clear();
        }
      }
    });
  }

  onAlert(): Observable<any> {
    return this._subject.asObservable();
  }

  success(message: string, showAfterRedirect = false) {
    this._showAfterRedirect = showAfterRedirect;
    this._snackBar.open(message, undefined, {
      duration: 8000,
      verticalPosition: 'top',
      horizontalPosition: 'center',
      panelClass: ['snackbar-success'],
    });
  }

  error(message: string, showAfterRedirect = false) {
    this._showAfterRedirect = showAfterRedirect;
    this._snackBar.open(message, undefined, {
      duration: 8000,
      verticalPosition: 'top',
      horizontalPosition: 'center',
      panelClass: ['snackbar-error'],
    });
  }

  clear() {
    // clear by calling subject.next() with null
    this._subject.next(null);
  }
}
