import { apiFetch } from './http';

export interface RouteRequest {
  fromStationId: string;
  toStationId: string;
  analyticCode: string;
}

export interface RouteResponse extends RouteRequest {
  id: number;
  distanceKm: number;
  path: string[];
  createdAt: string;
}

export interface AnalyticDistance {
  analyticCode: string;
  tripCount: number;
  totalDistanceKm: number;
}

export function createRoute(payload: RouteRequest): Promise<RouteResponse> {
  return apiFetch<RouteResponse>('/routes', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}

export function fetchRoutes(): Promise<RouteResponse[]> {
  return apiFetch<RouteResponse[]>('/routes', {
    method: 'GET',
  });
}

export function fetchStats(): Promise<AnalyticDistance[]> {
  return apiFetch<AnalyticDistance[]>('/stats', {
    method: 'GET',
  });
}
