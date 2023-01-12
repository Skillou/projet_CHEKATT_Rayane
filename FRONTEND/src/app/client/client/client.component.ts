import { Component, OnInit } from '@angular/core';
import {ClientService} from "../../Utils/Services/client.service";

@Component({
  selector: 'app-client',
  templateUrl: './client.component.html',
  styleUrls: ['./client.component.css']
})
export class ClientComponent {
  
  protected readonly clients$ = this.clientService.getClient();

  constructor(private readonly clientService: ClientService) {}

}
