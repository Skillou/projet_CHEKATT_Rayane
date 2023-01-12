export interface LoginClient {
  login: string;
  password: string;
}

export interface CreateClientDTO extends Omit<LoginClient, 'password'> {
  id: string;
  civility: 'male' | 'female' | 'other';
  firstName: string;
  lastName: string;
  email: string;
  telephone: number;
  street: string;
  city: string;
  zipCode: number;
}

export interface Client extends CreateClientDTO {
  isAdmin: boolean;
  loggedIn?: boolean;
}
