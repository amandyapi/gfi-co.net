import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MailRoutingModule } from './mail-routing.module';
import { MailListComponent } from './mail-list/mail-list.component';
import { MailInfoComponent } from './mail-info/mail-info.component';


@NgModule({
  declarations: [
    MailListComponent,
    MailInfoComponent
  ],
  imports: [
    CommonModule,
    MailRoutingModule
  ]
})
export class MailModule { }
