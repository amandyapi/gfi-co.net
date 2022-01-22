import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AboutRoutingModule } from './about-routing.module';
import { AboutComponent } from './about/about.component';
import { TeamComponent } from './team/team.component';
import { SharedModule } from 'src/app/shared/shared.module';

@NgModule({
  declarations: [
    AboutComponent,
    TeamComponent
  ],
  imports: [
    CommonModule,
    AboutRoutingModule,
    SharedModule
  ]
})
export class AboutModule { }
