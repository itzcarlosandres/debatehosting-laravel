@extends('layouts.app')

@section('title', 'Cupones de Descuento de Hosting y Servidores Verificados — Debatehosting')
@section('meta_description', 'Cupones de descuento comprobados a mano para hosting web, VPS y dominios en 2026. Códigos promocionales activos para ahorrar en tu servidor hoy.')

@section('content')
<section style="padding: 3.5rem 0 5rem 0;">
    <div class="container">
        <!-- Encabezado de Sección -->
        <div class="section-headline-wrap" style="margin-bottom: 2.5rem;">
            <div class="kicker">CÓDIGOS VERIFICADOS SIN TRUCOS</div>
            <h1 class="section-title">Directorio de Cupones de Hosting</h1>
            <p class="section-subtitle">
                Códigos de descuento probados a mano. Haz clic en cualquier cupón para copiarlo al portapapeles y acceder a la promoción oficial.
            </p>
        </div>

        <!-- Buscador de Cupones -->
        <div style="max-width: 520px; margin: 0 auto 3rem auto;">
            <form method="GET" action="{{ route('coupons.index') }}" style="display: flex; gap: 0.5rem; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-full); padding: 0.35rem 0.5rem 0.35rem 1.25rem; box-shadow: var(--shadow-sm);">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por proveedor o código..." style="flex: 1; border: none; outline: none; background: transparent; font-size: 0.9rem;">
                <button type="submit" class="btn btn-emerald btn-sm" style="border-radius: var(--radius-full);">
                    <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                    <span>Buscar</span>
                </button>
            </form>
        </div>

        <!-- Grid de Cupones -->
        <div class="coupons-grid">
            @forelse($coupons as $coupon)
            <div class="voucher-clean-box">
                <div>
                    <div class="voucher-top-row">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            @if($coupon->provider->resolved_logo_url)
                                <img src="{{ $coupon->provider->resolved_logo_url }}" alt="{{ $coupon->provider->name }}" style="height: 32px; max-width: 85px; object-fit: contain;">
                            @endif
                            <h3 style="font-size: 1.15rem; font-weight: 800; margin: 0; color: var(--text-main);">{{ $coupon->provider->name }}</h3>
                        </div>
                        <span class="badge-pill badge-rose" style="font-weight: 800;">
                            {{ $coupon->discount }}
                        </span>
                    </div>

                    <p style="font-size: 0.86rem; color: var(--text-muted); line-height: 1.55; margin-bottom: 1.5rem; min-height: 48px;">
                        {{ $coupon->condition ?: 'Descuento aplicable a nuevas contrataciones anuales.' }}
                    </p>
                </div>

                <div>
                    <div style="display: flex; gap: 0.4rem;">
                        <button onclick="copyAndRedirect('{{ $coupon->code }}', '{{ route('go', $coupon->provider->slug) }}')" class="voucher-code-btn" style="flex: 1;" title="Copiar código">
                            <span style="display: flex; align-items: center; gap: 0.45rem;">
                                <i data-lucide="copy" style="width: 14px; height: 14px;"></i>
                                <span>{{ $coupon->code }}</span>
                            </span>
                            <span style="font-size: 0.72rem; color: var(--emerald-primary);">COPIAR ↗</span>
                        </button>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-dim); margin-top: 0.85rem;">
                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                            <i data-lucide="shield-check" style="width: 13px; height: 13px; color: var(--emerald-primary);"></i>
                            <span>Verificado recientemente</span>
                        </span>
                        <span>{{ $coupon->clicks }} usos</span>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
                <p style="color: var(--text-muted); margin-bottom: 1rem;">No se encontraron cupones que coincidan con la búsqueda.</p>
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Ver Todos los Cupones</a>
            </div>
            @endforelse
        </div>
    </div>
</section>

@push('scripts')
<script>
    function copyAndRedirect(code, url) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                window.showToast('¡Cupón "' + code + '" copiado al portapapeles! Abriendo tienda oficial...', 'success');
                setTimeout(() => {
                    window.open(url, '_blank');
                }, 800);
            });
        } else {
            window.showToast('Cupón: ' + code, 'info');
            window.open(url, '_blank');
        }
    }
</script>
@endpush
@endsection
