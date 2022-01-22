import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import { NgxUiLoaderModule, NgxUiLoaderHttpModule, NgxUiLoaderConfig, SPINNER, POSITION, PB_DIRECTION } from 'ngx-ui-loader';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';

import { ToastrModule } from 'ngx-toastr';

const ngxUiLoaderConfig: NgxUiLoaderConfig = {
  //bgsColor: "red",
  bgsOpacity: 0.5,
  bgsPosition: "bottom-right",
  bgsSize: 60,
  bgsType: "ball-spin-clockwise",
  blur: 5,
  delay: 0,
  fastFadeOut: true,
  //fgsColor: "red",
  fgsPosition: "center-center",
  fgsSize: 60,
  fgsType: "ball-spin-clockwise",
  gap: 24,
  logoPosition: "center-center",
  logoSize: 120,
  logoUrl: "",
  masterLoaderId: "master",
  overlayBorderRadius: "0",
  overlayColor: "rgba(40, 40, 40, 0.8)",
  //pbColor: "red",
  pbDirection: "ltr",
  pbThickness: 3,
  hasProgressBar: true,
  text: "",
  textColor: "#FFFFFF",
  textPosition: "center-center",
  maxTime: -1,
  minTime: 300
};

@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    HttpClientModule,
    //NgxUiLoaderModule.forRoot(ngxUiLoaderConfig),
    //NgxUiLoaderHttpModule,
    FormsModule,
    ReactiveFormsModule,
    ToastrModule.forRoot({
      timeOut: 5000,
      progressBar: true,
      preventDuplicates: true,
      positionClass: 'toast-top-right',
      closeButton: true,
      resetTimeoutOnDuplicate: true,
      maxOpened:2,
      autoDismiss: true
    })
  ]
})
export class SharedModule { }
