@extends('layouts.app')

@section('title', 'Cupones de Descuento de Hosting y Servidores Verificados — Debatehosting')

@section('content')
<section style="max-width: 1200px; margin: 0 auto; padding: 3rem 1.5rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="section-kicker">CÓDIGOS VERIFICADOS SIN TRUCOS</span>
        <h1 class="section-title" style="font-size: 2.5rem; margin: 0.5rem 0;">Directorio de Cupones de Hosting</h1>
        <p class="section-subtitle">Códigos de descuento probados a mano. Haz clic para copiar y activar la promoción directa.</p>
    </div>

    <!-- Buscador de cupones -->
    <div style="max-width: 500px; margin: 0 auto 3rem auto;">
        <form method="GET" action="{{ route('coupons.index') }}" style="display: flex; gap: 0.5rem;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por proveedor o código..." style="flex: 1; padding: 0.6rem 1rem; border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-sm); font-size: 0.9rem;">
            <button type="submit" class="btn-primary-editorial">Buscar</button>
        </form>
    </div>

    <!-- Grid de Cupones -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
        @forelse($coupons as $coupon)
        <div style="background: var(--bg-surface-elevated); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md); padding: 1.75rem; box-shadow: var(--shadow-solid); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        @if($coupon->provider->resolved_logo_url)
                            <img src="{{ $coupon->provider->resolved_logo_url }}" alt="{{ $coupon->provider->name }}" style="height: 36px; max-width: 80px; object-fit: contain;">
                        @endif
                        <h3 style="font-family: var(--font-serif); font-size: 1.3rem; margin: 0;">{{ $coupon->provider->name }}</h3>
                    </div>
                    <span style="background: var(--green-tint); color: var(--green-dark); font-weight: 800; font-size: 0.9rem; padding: 0.25rem 0.6rem; border-radius: 4px; border: 1px solid var(--green-primary);">
                        {{ $coupon->discount }}
                    </span>
                </div>

                <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.5rem; min-height: 48px;">
                    {{ $coupon->condition ?: 'Descuento aplicable a nuevas contrataciones.' }}
                </p>
            </div>

            <div style="border-top: 1px dashed var(--border-ink); padding-top: 1rem;">
                <div style="display: flex; gap: 0.5rem;">
                    <button onclick="copyAndRedirect('{{ $coupon->code }}', '{{ route('go', $coupon->provider->slug) }}')" class="btn-primary-editorial" style="flex: 1; justify-content: center; font-family: var(--font-mono); font-size: 0.95rem;">
                        📋 {{ $coupon->code }}
                    </button>
                    <a href="{{ route('go', $coupon->provider->slug) }}" target="_blank" rel="noopener noreferrer" class="btn-secondary-editorial" style="padding: 0.6rem 0.8rem;" title="Ir a la web oficial">
                        ↗
                    </a>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-light); margin-top: 0.75rem;">
                    <span>✔ Verificado recientemente</span>
                    <span>{{ $coupon->clicks }} usos</span>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--bg-surface); border: var(--border-width) solid var(--border-ink); border-radius: var(--radius-md);">
            <p style="color: var(--text-muted);">No se encontraron cupones que coincidan con la búsqueda.</p>
            <a href="{{ route('coupons.index') }}" class="btn-primary-editorial" style="margin-top: 1rem; display: inline-block;">Ver Todos los Cupones</a>
        </div>
        @endforelse
    </div>
</section>

<script>
    function copyAndRedirect(code, url) {
        navigator.clipboard.writeText(code).then(() => {
            alert('¡Cupón "' + code + '" copiado al portapapeles! Abriendo la tienda oficial...');
            window.open(url, '_blank');
        });
    }
</script>
@endsection
