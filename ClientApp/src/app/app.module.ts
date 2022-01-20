import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { SharedModule } from './shared/shared.module';
import { AdminComponent } from './layout/admin/admin.component';
import { ClientComponent } from './layout/client/client.component';
import { AuthComponent } from './layout/auth/auth.component';
import { HeaderComponent } from './layout/client/header/header.component';
import { FooterComponent } from './layout/client/footer/footer.component';
import { NavTopComponent } from './layout/client/header/nav-top/nav-top.component';
import { NavBarComponent } from './layout/client/header/nav-bar/nav-bar.component';
import { FooterContactComponent } from './layout/client/footer/footer-contact/footer-contact.component';
import { FooterSummaryComponent } from './layout/client/footer/footer-summary/footer-summary.component';
import { FooterBottomComponent } from './layout/client/footer/footer-bottom/footer-bottom.component';
import { TchatPluginComponent } from './layout/client/tchat-plugin/tchat-plugin.component';

@NgModule({
  declarations: [
    AppComponent,
    AdminComponent,
    ClientComponent,
    AuthComponent,
    HeaderComponent,
    FooterComponent,
    NavTopComponent,
    NavBarComponent,
    FooterContactComponent,
    FooterSummaryComponent,
    FooterBottomComponent,
    TchatPluginComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    SharedModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
