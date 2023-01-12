import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http'
import { AppComponent } from './app.component';
import { HeaderComponent } from './header/header.component';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { FooterComponent } from './footer/footer.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DirClientDirective } from './Utils/Directives/dir-client.directive';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { PanierComponent } from './panier/panier.component';
import { AppRoutingModule } from './app-routing.module';
import { HomeComponent } from './home/home.component';
import {NgxsModule} from "@ngxs/store";
import {CartState} from "./Utils/States/panier.states";
import { LoginComponent } from './login/login.component';
import { FormProduitComponent } from './catalog/form-produit/form-produit.component';
import { AppInterceptor } from './shared/interceptors/app.interceptor';

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    FooterComponent,
    DirClientDirective,
    PanierComponent,
    HomeComponent,
    LoginComponent,
    FormProduitComponent
  ],
  imports: [
    HttpClientModule,
    NgxsModule.forRoot([
      CartState
    ]),
    BrowserModule,
    NgbModule,
    FormsModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    AppRoutingModule
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: AppInterceptor, multi: true }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
