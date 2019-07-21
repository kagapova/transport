import { Component, OnInit } from '@angular/core';
import {NgbDate, NgbCalendar} from '@ng-bootstrap/ng-bootstrap';
import {HttpClient} from '@angular/common/http';
import { ChartOptions, ChartType, ChartDataSets } from 'chart.js';
import { Label } from 'ng2-charts';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {
  page = 1;
  pageSize = 4;
  vehicles$;
  vehicles;
  pageFilter = 3;
  collectionSize = 0;
  hoveredDate: NgbDate;
  fromDate: NgbDate;
  toDate: NgbDate;
  isCalendar = false;
  public barChartOptions: ChartOptions = {
    responsive: true,
    // We use these empty structures as placeholders for dynamic theming.
    scales: { xAxes: [{}], yAxes: [{}] },
    plugins: {
      datalabels: {
        anchor: 'end',
        align: 'end',
      }
    }
  };
  public barChartLabels: Label[] = ['2006', '2007', '2008', '2009', '2010', '2011', '2012'];
  public barChartType: ChartType = 'bar';
  public barChartLegend = true;

  public barChartData: ChartDataSets[] = [
    { data: [65, 59, 80, 81, 56, 55, 40], label: 'Трамвай' },
    { data: [28, 21, 4, 19, 86, 27, 15], label: 'Автобус' },
    { data: [28, 35, 23, 17, 51, 41, 35], label: 'Метро' },
    { data: [28, 16, 15, 25, 34, 28, 50], label: 'Тролейбус' },
    { data: [28, 9, 30, 9, 19, 20, 15], label: 'Маршрутное такси' }
  ];

  constructor(private http: HttpClient, calendar: NgbCalendar) {
    this.fromDate = calendar.getToday();
    this.toDate = calendar.getNext(calendar.getToday(), 'd', 10);
  }

  ngOnInit() {
    this.getVehicles();
  }

  changeCalendar(){
    this.isCalendar = !this.isCalendar;
  }

  getVehicles() {
    this.http.get('https://6a3a31ab.ngrok.io/api/routes').subscribe(
      (response) => {
        this.vehicles$ = response;
        this.vehicles = this.vehicles$;
        this.collectionSize = this.vehicles$.length;
      }
    );
  }

  filter() {
     let buf = [];
     for (let veh in this.vehicles$){
       if (this.vehicles$[veh]['state'] === this.pageFilter){
         buf.push(this.vehicles$[veh]);
       }
     }
     if(this.pageFilter === 3){
       this.vehicles = this.vehicles$;
     } else {
       this.vehicles = buf;
     }
  }
  onDateSelection(date: NgbDate) {
    if (!this.fromDate && !this.toDate) {
      this.fromDate = date;
    } else if (this.fromDate && !this.toDate && date.after(this.fromDate)) {
      this.toDate = date;
    } else {
      this.toDate = null;
      this.fromDate = date;
    }
  }

  isHovered(date: NgbDate) {
    return this.fromDate && !this.toDate && this.hoveredDate && date.after(this.fromDate) && date.before(this.hoveredDate);
  }

  isInside(date: NgbDate) {
    return date.after(this.fromDate) && date.before(this.toDate);
  }

  isRange(date: NgbDate) {
    return date.equals(this.fromDate) || date.equals(this.toDate) || this.isInside(date) || this.isHovered(date);
  }
}

