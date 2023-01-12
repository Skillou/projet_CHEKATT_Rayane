import {Produit} from "../../Models/Produit";

export interface CartProduit {
  produit: Produit;
  quantity: number;
}

export class CartStateModel {
  produits!: CartProduit[];
}
