import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { NewsRoutingModule } from './news-routing.module';
import { NewsListComponent } from './news-list/news-list.component';
import { NewsInfoComponent } from './news-info/news-info.component';
import { SharedModule } from 'src/app/shared/shared.module';

@NgModule({
  declarations: [
    NewsListComponent,
    NewsInfoComponent
  ],
  imports: [
    CommonModule,
    NewsRoutingModule,
    SharedModule
  ]
})
export class NewsModule { }
