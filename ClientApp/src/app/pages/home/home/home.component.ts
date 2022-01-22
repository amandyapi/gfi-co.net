import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Routes } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  lang: any = {};

  constructor(
    private actRoute: ActivatedRoute
    ) {
      console.log('Home')
      this.lang = this.actRoute.snapshot.params['id'];
      console.log('lang', this.lang);
    }

  ngOnInit(): void {
  }

}
