import { Injectable } from "@angular/core";
import { RequestService } from "./request.service";
import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { PayduniaResponseModel } from "../models/payduniaResponse.model";
@Injectable({
    providedIn: 'root'
  })
  

export class PayDuniaService{
    
    //prod
    
    url="https://app.paydunya.com/api/v1/checkout-invoice/create";
    _url="https://app.paydunya.com/sandbox-api/v1/checkout-invoice/confirm/";
    masterKey= "5LLMWVlg-5JmS-itq7-wEqY-7AesWjvMENtT";
    privateKey= "live_private_cNrQaAyyZt6IcBHA9xbhWDCeRow";
    token= "w9mAx6mKxkFqKCjaEUrF";
   /*
    //test
    url="https://app.paydunya.com/sandbox-api/v1/checkout-invoice/create"
    masterKey= "5LLMWVlg-5JmS-itq7-wEqY-7AesWjvMENtT";
    privateKey= "test_private_EIrDyURE66WtECqGNfcerTB7ZRM";
    token= "1CznpWg0Nzz6FHPpca2J";
    _url="https://app.paydunya.com/api/v1/checkout-invoice/confirm/";
   */
    constructor(
        private http: HttpClient
        ){}

    payService(amount, description, orderId){
        const body= {
            invoice: {
                total_amount: amount, 
                description: description
            },
            store: {
                name: "CIKHA PAY"
            },
            actions: {
                callback_url: "https://api.cikhapay.com/transactions/ " + orderId +"/callback",
                return_url: "/transaction-success",
                cancel_url: "/transaction-failed"
            }      
        }

        const headers= new  HttpHeaders({
            'Content-Type': 'application/json',
            'PAYDUNYA-MASTER-KEY': this.masterKey,
            'PAYDUNYA-PRIVATE-KEY': this.privateKey,
            'PAYDUNYA-TOKEN': this.token
        });

      
          return this.http.post<PayduniaResponseModel>(this.url,body,
          {responseType: 'json', headers});
    }

    paymentStatus(token){ 
        const headers= new  HttpHeaders({
            'Content-Type': 'application/json',
            'PAYDUNYA-MASTER-KEY': this.masterKey,
            'PAYDUNYA-PRIVATE-KEY': this.privateKey,
            'PAYDUNYA-TOKEN': this.token
        });

        return this.http.get<PayduniaResponseModel>(this._url+ token,
            {responseType: 'json', headers});
            
    }
}