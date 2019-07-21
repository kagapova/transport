import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {DashboardComponent} from './dashboard/dashboard.component';
import {VehiclesComponent} from './vehicles/vehicles.component';


const routes: Routes = [
  {path: '', component: DashboardComponent},
  {path: 'vehicles/:number', component: VehiclesComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
