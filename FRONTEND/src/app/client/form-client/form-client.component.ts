import {CreateClientDTO} from '../../Models/Client';
import {Component} from '@angular/core';
import {FormGroup, FormBuilder, Validators} from '@angular/forms';
import {ClientService} from "../../Utils/Services/client.service";
import {Router} from "@angular/router";

@Component({
  selector: 'app-form-client',
  templateUrl: './form-client.component.html',
  styleUrls: ['./form-client.component.css']
})

export class FormClientComponent {

  civilities: string[] = ['M.', 'Mme.', 'Autres'];

  public clientForm: FormGroup = this.fb.group({
    civility: ['', Validators.required],
    firstName: ['', Validators.required],
    lastName: ['', Validators.required],
    email: ['', [Validators.required, Validators.email]],
    telephone: ['', [Validators.required, Validators.pattern(/^((\+)33|0)[1-9](\d{2}){4}$/)]],
    street: ['', Validators.required],
    city: ['', Validators.required],
    zipCode: ['', [Validators.required, Validators.pattern(/(?:0[1-9]|[13-8][0-9]|2[ab1-9]|9[0-5])(?:[0-9]{3})?|9[78][1-9](?:[0-9]{2})?/)]],
    login: ['', [Validators.required, Validators.pattern('')]], // mettre une regex pour le login
    password: ['', Validators.required],
    confirmPassword: ['', Validators.required],
  });

  constructor(private fb: FormBuilder, private clientService: ClientService, private router: Router,) {
  }

  onSubmit(): void {
    if (this.clientForm.valid) {
      this.clientService.signup(this.clientForm.value).subscribe(() => this.router.navigateByUrl('/client/result'));
    }
    else {
      console.error("Error : ", this.clientForm);
    }
  }
}
