import {Injectable} from '@angular/core';
import {Client, CreateClientDTO, LoginClient} from "../../Models/Client";
import {HttpClient} from '@angular/common/http';
import {Observable, map, ReplaySubject, shareReplay, startWith, tap} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ClientService {

  private readonly client$$ = new ReplaySubject<Client>(1);
  public readonly client$ = this.client$$.asObservable();

  public readonly isLoggedIn$ = this.client$$.pipe(
    map(({ loggedIn }) => loggedIn),
    startWith(false)
  );
  
  constructor(private readonly httpClient: HttpClient) {
  }

  public getClient(): Observable<Client[]> {
    return this.httpClient.get<Client[]>('/clients');
  };

  public signup(client: CreateClientDTO): Observable<Client> {
    return this.httpClient.post<Client>('/auth/signup', client).pipe(tap((client) => this.client$$.next({ ...client, loggedIn: true })));
  }

  public login(client: LoginClient): Observable<Client> {
    return this.httpClient.post<Client>('/auth/login', client).pipe(tap((client) => this.client$$.next({ ...client, loggedIn: true })));
  }
}
