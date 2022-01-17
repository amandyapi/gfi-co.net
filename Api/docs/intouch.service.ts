import { Injectable } from "@angular/core";
import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { CheckStatusResponseModel } from "../models/intouch/checkStatusResponse.model";
import { CheckStatusRequestModel } from "../models/intouch/checkStatusRequest.model";
import { AirtimeRequestModel } from "../models/intouch/airtimeRequest.model";
import { AirtimeResponseModel } from "../models/intouch/airtimeResponse.model";
import { PaymentRequestModel } from "../models/intouch/paymentRequest.model";
import { PaymentResponseModel } from "../models/intouch/paymentResponse.model";
import { GetBillRequestModel } from "../models/intouch/getBillRequest.model";
import { GetBillResponseModel } from "../models/intouch/getBillResponse.model";
import { PayBillRequestModel } from "../models/intouch/payBillRequest.model";

@Injectable({
    providedIn: 'root'
  })
export class IntouchService{

    partner_id= "PW03409602";
    login_api= "01458365";
    password_api= "ylsToXUTR@roIs?0wAyO";
    baseUrl="https://api.gutouch.com/v1/";
    agenceCode="ATHAR5510";
    authUser="MTN";
    auhPassword="passer";
    call_back_url="";
    constructor(
        private http: HttpClient
    ){

    }

    checkStatus(partner_transaction_id){
        const url= this.baseUrl + this.agenceCode + "/check_status";
        const body: CheckStatusRequestModel= {
            partner_id: this.partner_id,
            partner_transaction_id: partner_transaction_id,
            login_api: this.login_api,
            password_api: this.password_api
        }

        const headers= new  HttpHeaders({
            'Content-Type':  'application/json',
            'Authorization': 'Basic ' + btoa(this.auhPassword+":"+this.auhPassword)
        });
        
          return this.http.post<CheckStatusResponseModel>(url,body, {responseType: 'json', headers});        
    }

    airtime(recipient_phone_number, amount, service_id, partner_transaction_id ){
        const url= this.baseUrl + this.agenceCode + "/airtime";
        const body: AirtimeRequestModel= {
            recipient_phone_number: recipient_phone_number,
            amount: amount,
            partner_id: this.partner_id,
            service_id: service_id,
            password_api: this.password_api,
            partner_transaction_id: partner_transaction_id,
            login_api: this.login_api,
            call_back_url: this.call_back_url,
        }

        const headers= new  HttpHeaders({
            'Content-Type':  'application/json',
            'Authorization': 'Basic ' + btoa(this.auhPassword+":"+this.auhPassword)
        });
        
          return this.http.post<AirtimeResponseModel>(url,body, {responseType: 'json', headers});
    }

    cashin(partner_transaction_id,recipientEmail, recipientFirstName, recipientLastName, destinataire, amount, AgentNumber, serviceCode ){
        const url= "https://api.gutouch.com/dist/api/touchpayapi/v1/" + this.agenceCode + "/transaction?loginAgent=" + this.login_api + "&passwordAgent=" + this.password_api;
        const body: PaymentRequestModel= {
            idFromClient: partner_transaction_id,
            additionnalInfos: {
                recipientEmail: recipientEmail,
                recipientFirstName: recipientFirstName,
                recipientLastName: recipientLastName,
                destinataire: destinataire,
            },
            amount: amount,
            callback: this.call_back_url,
            recipientNumber: AgentNumber,
            serviceCode: serviceCode
        }

        const headers= new  HttpHeaders({
            'Content-Type':  'application/json',
            'Authorization': 'Digest ' + btoa(this.auhPassword+":"+this.auhPassword)
        });
        
          return this.http.post<PaymentResponseModel>(url,body, {responseType: 'json', headers});
    }

    getBill(numeroFacture, facturier, serviceId){
        const url= this.baseUrl + this.agenceCode + "/smartmotic/getbill";
        const body: GetBillRequestModel= {
            partnerId : this.partner_id,
            loginApi : this.login_api,
            passwordApi : this.password_api,
            numeroFacture : numeroFacture,
            facturier : facturier,
            serviceId : serviceId
        }

        const headers= new  HttpHeaders({
            'Content-Type':  'application/json',
            'Authorization': 'Basic ' + btoa(this.auhPassword+":"+this.auhPassword)
        });
        
          return this.http.post<GetBillResponseModel>(url,body, {responseType: 'json', headers});
    }

    payBill(telephone, montant, serviceId, partner_transaction_id){
        const url= this.baseUrl + this.agenceCode + "/smartmotic/paybill";
        const body: PayBillRequestModel= {
            partnerId : this.partner_id,
            loginApi : this.login_api,
            passwordApi : this.password_api,
            callBackUrl : this.call_back_url,
            telephone : telephone,
            montant : montant,
            serviceId : serviceId,
            partnerTransactionId : partner_transaction_id,
            dateLimite : "",
            codeExpiration : 0,
            merchant : "string",
            totAmount : 0,
            typeFacture : 0 ,
            heureEnreg :  "string",
            refBranch : "string",
            numFacture : 0,
            idAbonnement : "string",
            dateEnreg :  "string",
            perFacture : 0
        }

        const headers= new  HttpHeaders({
            'Content-Type':  'application/json',
            'Authorization': 'Basic ' + btoa(this.auhPassword+":"+this.auhPassword)
        });
        
          return this.http.post<GetBillResponseModel>(url,body, {responseType: 'json', headers});
    }
    
}