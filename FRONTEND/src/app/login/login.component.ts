import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators} from "@angular/forms";
import {Router} from "@angular/router";
import {ClientService} from "../Utils/Services/client.service";
import {LoginService} from "../Utils/Services/login.service";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  // jwtError: boolean | undefined;
  loginForm = this.fb.nonNullable.group({
    login: ['', Validators.required],
    password: ['', Validators.required]
  });

  constructor(private readonly fb: FormBuilder, protected readonly clientService: ClientService, private readonly router: Router) { }

  ngOnInit(): void {
    if (this.loginForm.valid) {

    }
    else {
      console.error("Error : ", this.loginForm);
    }
  }

  onSubmit() {
    this.clientService.login(this.loginForm.getRawValue()).subscribe(() => this.router.navigateByUrl('/'));
  }
}
