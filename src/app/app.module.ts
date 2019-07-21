import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import {NgbModule, NgbPagination, NgbPaginationModule} from '@ng-bootstrap/ng-bootstrap';
import {FormsModule} from '@angular/forms';
import {ChartsModule} from 'ng2-charts';
import { VehiclesComponent } from './vehicles/vehicles.component';
import {HttpClient, HttpClientModule} from '@angular/common/http';
import {FontAwesomeModule} from 'ngx-icons';
import {MatIconModule} from '@angular/material';

@NgModule({
  declarations: [
    AppComponent,
    DashboardComponent,
    VehiclesComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    NgbModule,
    NgbPaginationModule,
    ChartsModule,
    HttpClientModule,
    FontAwesomeModule,
    MatIconModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
