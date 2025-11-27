import { describe, it, expect, afterEach, vi } from 'vitest';
import { useRouteCreation } from './useRouteCreation';

const originalFetch = globalThis.fetch;

describe('useRouteCreation', () => {
  afterEach(() => {
    vi.restoreAllMocks();
    if (originalFetch) {
      globalThis.fetch = originalFetch;
    } else {
      Reflect.deleteProperty(globalThis, 'fetch');
    }
  });

  it('stores the created route on success', async () => {
    const mockRoute = {
      id: 1,
      fromStationId: 'MX',
      toStationId: 'CGE',
      analyticCode: 'ANA',
      distanceKm: 1.2,
      path: ['MX', 'CGE'],
      createdAt: new Date().toISOString(),
    };

    vi.stubGlobal(
      'fetch',
      vi.fn().mockResolvedValue({
        ok: true,
        json: async () => mockRoute,
      }),
    );

    const { submit, lastRoute, error, loading } = useRouteCreation();

    await submit();

    expect(lastRoute.value).toMatchObject(mockRoute);
    expect(error.value).toBeNull();
    expect(loading.value).toBe(false);
  });

  it('exposes the backend error message', async () => {
    vi.stubGlobal('fetch', vi.fn().mockResolvedValue({
      ok: false,
      status: 422,
      json: async () => ({ error: 'Invalid data' }),
    }));

    const { submit, error } = useRouteCreation();

    await submit();

    expect(error.value).toBe('Invalid data');
  });
});
