@props([
    'review',
    'hasActiveProvider' => false,
    'alternatives' => collect(),
])

@if($hasActiveProvider && $review->provider)
    @php
        $p = $review->provider;
        $bestCoupon = $p->coupons->first();
    @endphp
    <!-- WIDGET PROMOCIONAL DIRECTO DEL PROVEEDOR (ALTA CONVERSIÓN) -->
    <aside class="smart-deal-card" style="background: linear-gradient(145deg, rgba(16, 185, 129, 0.08) 0%, var(--bg-surface) 65%); border: 1.5px solid rgba(16, 185, 129, 0.35); border-radius: var(--radius-lg); padding: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35), 0 0 25px rgba(16, 185, 129, 0.1); position: relative; overflow: hidden; margin-bottom: 2.5rem;">
        <!-- Píldora de Estado Superior -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div style="display: inline-flex; align-items: center; gap: 0.45rem; background: var(--emerald-subtle); border: 1px solid rgba(16, 185, 129, 0.3); color: var(--emerald-primary); padding: 0.3rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; font-family: var(--font-mono);">
                <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i>
                <span>Proveedor Auditado & Verificado</span>
            </div>

            @if($p->badge)
                <span class="badge-pill badge-emerald" style="font-weight: 800;">★ {{ $p->badge }}</span>
            @endif
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.75rem;">
            <!-- Marca y Plan -->
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                @if($p->resolved_logo_url)
                    <img src="{{ $p->resolved_logo_url }}" alt="{{ $p->name }}" style="height: 58px; max-width: 140px; object-fit: contain; background: #FFFFFF; padding: 6px; border-radius: var(--radius-md); border: 1px solid var(--border-color); flex-shrink: 0;">
                @else
                    <div style="width: 58px; height: 58px; background: var(--bg-subtle); border: 1px solid var(--border-color); color: var(--text-main); font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; justify-content: center; border-radius: var(--radius-md); flex-shrink: 0;">
                        {{ substr($p->name, 0, 2) }}
                    </div>
                @endif
                <div>
                    <h3 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin: 0; line-height: 1.1;">{{ $p->name }}</h3>
                    <div style="font-size: 0.88rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Plan destacado: <strong style="color: var(--text-main);">{{ $p->plan }}</strong>
                    </div>
                </div>
            </div>

            <!-- Precio y Descuento -->
            <div style="text-align: right;">
                <span style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono); display: block; margin-bottom: 0.15rem;">Oferta Exclusiva Desde</span>
                <div style="font-family: var(--font-mono); font-size: 2.2rem; font-weight: 800; color: var(--text-main); line-height: 1;">
                    ${{ number_format($p->price_from, 2) }}
                    <span style="font-size: 0.85rem; font-weight: 400; color: var(--text-muted);">/{{ $p->period }}</span>
                </div>
                @if($p->discount_percent > 0)
                    <span style="display: inline-block; font-family: var(--font-mono); font-size: 0.75rem; font-weight: 700; color: var(--rose-primary); background: rgba(244, 63, 94, 0.12); padding: 0.15rem 0.5rem; border-radius: 4px; margin-top: 0.25rem;">
                        -{{ $p->discount_percent }}% DE DESCUENTO
                    </span>
                @endif
            </div>
        </div>

        <!-- Métricas Clave Rápidas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; padding: 1rem; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); text-align: center;">
            <div>
                <div style="font-size: 0.68rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono);">Nota Editorial</div>
                <div style="font-family: var(--font-mono); font-size: 1.15rem; font-weight: 800; color: var(--emerald-primary);">★ {{ $p->overall_score }}/10</div>
            </div>
            <div>
                <div style="font-size: 0.68rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono);">Velocidad TTFB</div>
                <div style="font-family: var(--font-mono); font-size: 1.15rem; font-weight: 800; color: var(--text-main);">{{ $p->score_rendimiento }}/10</div>
            </div>
            <div>
                <div style="font-size: 0.68rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono);">Soporte</div>
                <div style="font-family: var(--font-mono); font-size: 1.15rem; font-weight: 800; color: var(--text-main);">{{ $p->score_soporte }}/10</div>
            </div>
            <div>
                <div style="font-size: 0.68rem; color: var(--text-dim); text-transform: uppercase; font-family: var(--font-mono);">Disponibilidad</div>
                <div style="font-family: var(--font-mono); font-size: 1.15rem; font-weight: 800; color: var(--emerald-primary);">{{ $p->uptime }}%</div>
            </div>
        </div>

        <!-- Cupón si existe -->
        @if($bestCoupon)
        <div style="background: rgba(14, 165, 233, 0.08); border: 1px dashed rgba(14, 165, 233, 0.4); border-radius: var(--radius-md); padding: 0.85rem 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <i data-lucide="ticket" style="width: 18px; height: 18px; color: var(--sky-primary);"></i>
                <div>
                    <strong style="font-size: 0.88rem; color: #FFFFFF;">Cupón: {{ $bestCoupon->discount }}</strong>
                    <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">{{ $bestCoupon->condition ?: 'Aplica en contratación' }}</span>
                </div>
            </div>
            <button type="button" onclick="copyVoucher('{{ $bestCoupon->code }}', '{{ route('go', $p->slug) }}')" class="btn btn-secondary btn-sm" style="font-family: var(--font-mono); font-weight: 700;">
                <span style="color: var(--sky-primary);">{{ $bestCoupon->code }}</span>
                <i data-lucide="copy" style="width: 12px; height: 12px; margin-left: 0.4rem;"></i>
            </button>
        </div>
        @endif

        <!-- Botones de Acción -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <a href="{{ route('providers.show', $p->slug) }}" class="btn btn-secondary" style="font-size: 0.85rem;">
                <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
                <span>Ficha Técnica Completa</span>
            </a>

            <a href="{{ route('go', $p->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-emerald" style="padding: 0.85rem 2rem; font-size: 1rem; font-weight: 800; box-shadow: 0 4px 20px var(--emerald-glow);">
                <span>Aprovechar Oferta en {{ $p->name }}</span>
                <i data-lucide="external-link" style="width: 16px; height: 16px;"></i>
            </a>
        </div>
    </aside>

@else
    <!-- WIDGET DE ALTERNATIVAS RECOMENDADAS (CUANDO EL PROVEEDOR NO ESTÁ PUBLICADO) -->
    <aside class="smart-alternatives-card" style="background: var(--bg-surface); border: 1.5px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-md); margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 42px; height: 42px; border-radius: var(--radius-md); background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: var(--amber-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="trophy" style="width: 20px; height: 20px;"></i>
            </div>
            <div>
                <span style="font-family: var(--font-mono); font-size: 0.72rem; font-weight: 700; color: var(--amber-primary); text-transform: uppercase; letter-spacing: 0.06em;">Recomendación Editorial</span>
                <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin: 0.15rem 0 0.4rem 0;">
                    Alternativas Recomendadas con Máximo Rendimiento
                </h3>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin: 0; line-height: 1.5;">
                    Analizamos <strong>{{ $review->resolved_provider_name }}</strong> a fondo. Si buscas opciones con servidores ultrarrápidos, soporte 24/7 y mejor relación calidad/precio verificada, estas son las mejores opciones del podio:
                </p>
            </div>
        </div>

        <!-- Grid de 3 Alternativas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
            @foreach($alternatives as $idx => $alt)
            <div style="background: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.15s ease, border-color 0.15s ease; position: relative;">
                <!-- Distintivo de Posición -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem;">
                    <span style="font-family: var(--font-mono); font-size: 0.72rem; font-weight: 800; color: var(--emerald-primary); background: var(--emerald-subtle); padding: 0.15rem 0.5rem; border-radius: 4px;">
                        #{{ $idx + 1 }} Top Elección
                    </span>
                    <span style="font-family: var(--font-mono); font-size: 0.82rem; font-weight: 800; color: var(--emerald-primary);">
                        ★ {{ $alt->overall_score }}/10
                    </span>
                </div>

                <!-- Logo & Nombre -->
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    @if($alt->resolved_logo_url)
                        <img src="{{ $alt->resolved_logo_url }}" alt="{{ $alt->name }}" style="height: 38px; max-width: 90px; object-fit: contain; background: #FFFFFF; padding: 4px; border-radius: 4px; border: 1px solid var(--border-color);">
                    @else
                        <div style="width: 38px; height: 38px; border-radius: 4px; background: var(--bg-hover); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--text-main);">
                            {{ substr($alt->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--text-main); margin: 0;">{{ $alt->name }}</h4>
                        <span style="font-size: 0.75rem; color: var(--text-dim);">Plan: {{ $alt->plan }}</span>
                    </div>
                </div>

                <!-- Precio -->
                <div style="margin-bottom: 1.15rem; padding: 0.65rem 0.85rem; background: var(--bg-surface); border-radius: var(--radius-sm); display: flex; align-items: baseline; justify-content: space-between;">
                    <span style="font-size: 0.75rem; color: var(--text-dim);">Desde:</span>
                    <div style="font-family: var(--font-mono); font-size: 1.25rem; font-weight: 800; color: var(--text-main);">
                        ${{ number_format($alt->price_from, 2) }}<span style="font-size: 0.75rem; font-weight: 400; color: var(--text-muted);">/{{ $alt->period }}</span>
                    </div>
                </div>

                <!-- Botones -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="{{ route('go', $alt->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-emerald btn-sm" style="width: 100%; justify-content: center; font-weight: 700;">
                        <span>Ver Oferta Oficial</span>
                        <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                    </a>
                    <a href="{{ route('providers.show', $alt->slug) }}" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; font-size: 0.75rem; border-color: transparent;">
                        <span>Auditoría completa</span>
                        <i data-lucide="arrow-right" style="width: 11px; height: 11px;"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </aside>
@endif
