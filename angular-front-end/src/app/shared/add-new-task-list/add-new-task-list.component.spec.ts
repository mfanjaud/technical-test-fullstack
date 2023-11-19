import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddNewTaskListComponent } from './add-new-task-list.component';

describe('AddNewTaskListComponent', () => {
  let component: AddNewTaskListComponent;
  let fixture: ComponentFixture<AddNewTaskListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AddNewTaskListComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AddNewTaskListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
