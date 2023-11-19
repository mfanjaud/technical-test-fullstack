import { Injectable } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router, NavigationStart } from '@angular/router';
import { Observable, Subject } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AlertService {
  private subject = new Subject<any>();
  private showAfterRedirect = false;

  constructor(private router: Router, private _snackBar: MatSnackBar) {
    // clear alert messages on route change unless 'showAfterRedirect' flag is true
    this.router.events.subscribe((event) => {
      if (event instanceof NavigationStart) {
        if (this.showAfterRedirect) {
          // only keep for a single route change
          this.showAfterRedirect = false;
        } else {
          // clear alert message
          this.clear();
        }
      }
    });
  }

  onAlert(): Observable<any> {
    return this.subject.asObservable();
  }

  success(message: string, showAfterRedirect = false) {
    this.showAfterRedirect = showAfterRedirect;
    this._snackBar.open(message, undefined, {
      duration: 8000,
      verticalPosition: 'top',
      horizontalPosition: 'center',
      panelClass: ['snackbar-success'],
    });
  }

  error(message: string, showAfterRedirect = false) {
    this.showAfterRedirect = showAfterRedirect;
    this._snackBar.open(message, undefined, {
      duration: 8000,
      verticalPosition: 'top',
      horizontalPosition: 'center',
      panelClass: ['snackbar-error'],
    });
  }

  clear() {
    // clear by calling subject.next() with null
    this.subject.next(null);
  }
}
