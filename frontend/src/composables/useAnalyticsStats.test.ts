import { describe, it, expect, afterEach, vi } from 'vitest';
import { useAnalyticsStats } from './useAnalyticsStats';

const originalFetch = globalThis.fetch;

describe('useAnalyticsStats', () => {
  afterEach(() => {
    vi.restoreAllMocks();
    if (originalFetch) {
      globalThis.fetch = originalFetch;
    } else {
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      delete (globalThis as any).fetch;
    }
  });

  it('loads stats from the API', async () => {
    const statsPayload = [
      { analyticCode: 'ANA', tripCount: 2, totalDistanceKm: 3.5 },
      { analyticCode: 'FRET', tripCount: 1, totalDistanceKm: 10 },
    ];

    vi.stubGlobal(
      'fetch',
      vi.fn().mockResolvedValue({
        ok: true,
        json: async () => statsPayload,
      }),
    );

    const composable = useAnalyticsStats();
    await composable.refresh();

    expect(composable.stats.value).toEqual(statsPayload);
    expect(composable.error.value).toBeNull();
    expect(composable.loading.value).toBe(false);
  });

  it('captures API errors', async () => {
    vi.stubGlobal(
      'fetch',
      vi.fn().mockResolvedValue({
        ok: false,
        status: 500,
        json: async () => ({ error: 'Server boom' }),
      }),
    );

    const composable = useAnalyticsStats();
    await composable.refresh();

    expect(composable.stats.value).toEqual([]);
    expect(composable.error.value).toBe('Server boom');
  });
});
