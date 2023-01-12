import { Injectable } from '@angular/core';
import {CreateClientDTO, LoginClient} from "../../Models/Client";
import {HttpClient} from '@angular/common/http';
import {Observable, ReplaySubject, tap} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor(private readonly httpClient: HttpClient) { }

  private readonly client$$ = new ReplaySubject<CreateClientDTO>(1);
  public readonly client$ = this.client$$.asObservable();

  public login(loginClient: LoginClient): Observable<CreateClientDTO> {
    return this.httpClient.post<CreateClientDTO>('/auth/login', loginClient).pipe(tap((client) => this.client$$.next(client)));
  }
}
