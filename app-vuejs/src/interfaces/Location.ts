import { Forecast } from "./Forecast";

export interface Location {
  id?: number;
  city: string;
  state?: string;
  forecsts: Forecast[];
}

export interface LocationListResponse {
  data: Location[];
}

export interface LocationResponse {
  data: Location;
}

