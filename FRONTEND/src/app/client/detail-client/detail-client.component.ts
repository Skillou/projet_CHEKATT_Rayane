import { Component } from '@angular/core';
import { ClientService } from "../../Utils/Services/client.service";

@Component({
  selector: 'app-detail-client',
  templateUrl: './detail-client.component.html',
  styleUrls: ['./detail-client.component.css']
})
export class DetailClientComponent {

  constructor(protected clientService: ClientService) {}

}
