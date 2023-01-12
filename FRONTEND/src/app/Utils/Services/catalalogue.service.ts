import { Injectable } from '@angular/core';
import {Observable, ReplaySubject, tap} from "rxjs";
import {Produit} from "../../Models/Produit";
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class CatalalogueService {

  constructor(private readonly httpClient: HttpClient) { }

  private readonly produit$$ = new ReplaySubject<Produit>(1);
  public readonly produit$ = this.produit$$.asObservable();

  public getCatalogue() : Observable<Produit[]> {
    return this.httpClient.get<Produit[]>('/products');
  };

  public addProduct(produit: Produit): Observable<Produit> {
    return this.httpClient.post<Produit>('/products', produit).pipe(tap((produit) => this.produit$$.next(produit)));
  }
}
