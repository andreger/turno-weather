import { Forecast } from "./Forecast";

export type Location = {
  id: number;
  city: string;
  state?: string;
  forecasts: Forecast[];
}

export type CreateLocation = Omit<Location, 'id' | 'forecasts'>

export type LocationListResponse = {
  data: Location[];
}

export type LocationResponse = {
  data: Location;
}

