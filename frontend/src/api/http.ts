const API_BASE_URL = import.meta.env.VITE_API_BASE_URL ?? '/api/v1';

interface RequestOptions extends RequestInit {
  skipJson?: boolean;
}

export async function apiFetch<T>(path: string, options: RequestOptions = {}): Promise<T> {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    headers: {
      'Content-Type': 'application/json',
      ...(options.headers ?? {}),
    },
    ...options,
  });

  const content = options.skipJson ? null : await response.json().catch(() => null);

  if (!response.ok) {
    const message = (content as { error?: string } | null)?.error ?? `HTTP ${response.status}`;
    throw new Error(message);
  }

  return content as T;
}
