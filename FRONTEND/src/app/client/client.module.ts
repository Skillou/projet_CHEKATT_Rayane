import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {DetailClientComponent} from "./detail-client/detail-client.component";
import {FormClientComponent} from "./form-client/form-client.component";
import {RouterModule, Routes} from "@angular/router";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {ClientComponent} from "./client/client.component";

const routes: Routes = [
  { path: '', component: ClientComponent, pathMatch: 'full' },
  { path: 'register', component: FormClientComponent },
  { path: 'result', component: DetailClientComponent }
];

@NgModule({
  declarations: [
    ClientComponent,
    FormClientComponent,
    DetailClientComponent,
  ],
  imports: [
    CommonModule,
    RouterModule.forChild(routes),
    FormsModule,
    HttpClientModule,
    ReactiveFormsModule
  ]
})


export class ClientModule { }
