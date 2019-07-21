import {Component, OnInit, ViewChild} from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { switchMap } from 'rxjs/operators';
import { ParamMap } from '@angular/router';
import { ChartDataSets, ChartOptions } from 'chart.js';
import {BaseChartDirective, Color, Label} from 'ng2-charts';
import {HttpClient} from '@angular/common/http';
import * as pluginAnnotations from 'chartjs-plugin-annotation';

@Component({
  selector: 'app-vehicles',
  templateUrl: './vehicles.component.html',
  styleUrls: ['./vehicles.component.scss']
})
export class VehiclesComponent implements OnInit {
  Lables$ = [];
  LineData$ = [];
  median;
  descriptions;
  public lineChartPlugins = [pluginAnnotations];
  public lineChartData: ChartDataSets[] = [
    { data: [65, 59, 80, 81, 56, 55, 40], label: 'Маршрут 1'  }
  ];
  public lineChartLabels: Label[] = ['Остановка 1', 'Остановка 2', 'Остановка 3', 'Остановка 4', 'Остановка 5', 'Остановка 6', 'Остановка 7'];
  public lineChartOptions: (ChartOptions & { annotation: any }) = {
    responsive: true,
    scales: {
      // We use this empty structure as a placeholder for dynamic theming.
      xAxes: [{}],
      yAxes: [
        {
          id: 'y-axis-0',
          position: 'left',
        },
      ]
    },
    annotation: {
      annotations: [
        {
          type: 'line',
          mode: 'horizontal',
          scaleID: 'y-axis-0',
          value: 200,
          borderColor: 'orange',
          borderWidth: 2,
          label: {
            enabled: true,
            fontColor: 'orange'
          }
        }
      ],
    },
  };
  public lineChartColors: Color[] = [];
  public lineChartLegend = false;
  public lineChartType = 'line';

  vehicle$;
  buses$;
  constructor(private route: ActivatedRoute, private http: HttpClient) { }

  ngOnInit() {
    this.vehicle$ = this.route.snapshot.params['number'];
    this.getBuses();
    this.getStats();
  }

  getBuses(){
    this.http.get('https://6a3a31ab.ngrok.io/api/route/' + this.vehicle$).subscribe(
      (resp) => this.buses$ = resp['buses']
    );
  }
  getStats(){
    this.http.get('https://6a3a31ab.ngrok.io/api/route/' + this.vehicle$ + '/stats').subscribe(
      resp => {
        this.Lables$ = resp['x']['data'];
        for (let i=0; i<resp['series'].length; i++){
          this.LineData$[i] = {data: resp['series'][i]['data'], label: resp['series'][i]['name']}
        }
        this.lineChartLabels = this.Lables$;
        this.lineChartData = this.LineData$;
        this.median = resp['median'];
        this.descriptions = resp['title'] + " " + resp['description'];
      }
    );
  }
}
