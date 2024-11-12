export interface Location {
  id?: number;
  city: string;
  state?: string;
}

export interface LocationListResponse {
  data: Location[];
}

export interface LocationResponse {
  data: Location;
}

