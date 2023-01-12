import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Router} from "@angular/router";
import { categories } from 'src/app/Models/Produit';
import {CatalalogueService} from "../../Utils/Services/catalalogue.service";

@Component({
  selector: 'app-form-produit',
  templateUrl: './form-produit.component.html',
  styleUrls: ['./form-produit.component.css']
})
export class FormProduitComponent implements OnInit {

  constructor(private fb: FormBuilder, private catalalogueService: CatalalogueService,private router: Router) { }

  validate: boolean = false;
  protected categories = categories;

  public productForm: FormGroup = this.fb.group({
    name: ['', Validators.required],
    description: ['', Validators.required],
    price: ['', [Validators.required, Validators.pattern('^[1-9][0-9]*(\.[0-9]+)?|0+\.[0-9]*[1-9][0-9]*$')]],
    category: ['', Validators.required],
    image: ['', Validators.required],
    summary: ['', Validators.required],
  });

  ngOnInit(): void {
  }

  onSubmit(): void {
    this.validate = true;
    if (this.productForm.valid) {
      this.catalalogueService.addProduct(this.productForm.value).subscribe(() => this.router.navigateByUrl('/catalogue'));
    }
  }
}
