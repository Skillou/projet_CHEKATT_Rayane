import { Component, OnInit } from '@angular/core';
import { Store } from '@ngxs/store';
import { CartState } from '../Utils/States/panier.states';
import { ClientService } from '../Utils/Services/client.service';
import { combineLatest } from 'rxjs';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  protected readonly produitsCount$ = this.store.select(CartState.countProduct);

  protected readonly vm$ = combineLatest({
    loggedIn: this.clientService.isLoggedIn$
  })

  constructor(private readonly store: Store, private readonly clientService: ClientService) { }

  ngOnInit(): void { }
}
