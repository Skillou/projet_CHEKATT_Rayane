export const categories = ['livre', 'manga', 'jeu'] as const;

export type CategoryName = typeof categories[number];

export type Category = Record<CategoryName, boolean>;

export interface Produit  {
  id: number;
  name: string;
  description: string;
  price: number;
  category: CategoryName;
  image: string;
  summary: string;
}
