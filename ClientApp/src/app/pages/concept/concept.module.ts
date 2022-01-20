import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ConceptRoutingModule } from './concept-routing.module';
import { ConceptListComponent } from './concept-list/concept-list.component';
import { ConceptInfoComponent } from './concept-info/concept-info.component';


@NgModule({
  declarations: [
    ConceptListComponent,
    ConceptInfoComponent
  ],
  imports: [
    CommonModule,
    ConceptRoutingModule
  ]
})
export class ConceptModule { }
