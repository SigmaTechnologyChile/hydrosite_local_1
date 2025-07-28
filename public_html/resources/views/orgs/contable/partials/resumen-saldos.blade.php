<div class="resumen-container" id="resumenContainer">
    <div class="resumen-card saldo-inicial">
        <h4>
            <i class="bi bi-wallet"></i> Caja General
            @if(isset($cuentaCajaGeneral) && ($cuentaCajaGeneral->banco || $cuentaCajaGeneral->numero_cuenta || $cuentaCajaGeneral->tipo_cuenta || $cuentaCajaGeneral->observaciones))
                <span title="Banco: {{ $cuentaCajaGeneral->banco ? ($cuentaCajaGeneral->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaCajaGeneral->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaCajaGeneral->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaCajaGeneral->observaciones ?? '-' }}" style="cursor: help; color: #17a2b8; font-size: 14px; margin-left: 6px;">
                    <i class="bi bi-info-circle"></i>
                </span>
            @endif
        </h4>
        <div class="valor" id="saldoCajaGeneral">
            ${{ number_format($saldosIniciales['Caja General'] ?? 0, 0, ',', '.') }}
        </div>
    </div>
    <div class="resumen-card saldo-inicial">
        <h4>
            <i class="bi bi-bank"></i> Cuenta Corriente 1
            @if(isset($cuentaCorriente1) && ($cuentaCorriente1->banco || $cuentaCorriente1->numero_cuenta || $cuentaCorriente1->tipo_cuenta || $cuentaCorriente1->observaciones))
                <span title="Banco: {{ $cuentaCorriente1->banco ? ($cuentaCorriente1->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaCorriente1->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaCorriente1->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaCorriente1->observaciones ?? '-' }}" style="cursor: help; color: #17a2b8; font-size: 14px; margin-left: 6px;">
                    <i class="bi bi-info-circle"></i>
                </span>
            @endif
        </h4>
        <div class="valor" id="saldoCuentaCorriente1">
            ${{ number_format($saldosIniciales['Cuenta Corriente 1'] ?? 0, 0, ',', '.') }}
        </div>
    </div>
    <div class="resumen-card saldo-inicial">
        <h4>
            <i class="bi bi-bank"></i> Cuenta Corriente 2
            @if(isset($cuentaCorriente2) && ($cuentaCorriente2->banco || $cuentaCorriente2->numero_cuenta || $cuentaCorriente2->tipo_cuenta || $cuentaCorriente2->observaciones))
                <span title="Banco: {{ $cuentaCorriente2->banco ? ($cuentaCorriente2->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaCorriente2->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaCorriente2->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaCorriente2->observaciones ?? '-' }}" style="cursor: help; color: #17a2b8; font-size: 14px; margin-left: 6px;">
                    <i class="bi bi-info-circle"></i>
                </span>
            @endif
        </h4>
        <div class="valor" id="saldoCuentaCorriente2">
            ${{ number_format($saldosIniciales['Cuenta Corriente 2'] ?? 0, 0, ',', '.') }}
        </div>
    </div>
    <div class="resumen-card saldo-inicial">
        <h4>
            <i class="bi bi-piggy-bank"></i> Cuenta de Ahorro
            @if(isset($cuentaAhorro) && ($cuentaAhorro->banco || $cuentaAhorro->numero_cuenta || $cuentaAhorro->tipo_cuenta || $cuentaAhorro->observaciones))
                <span title="Banco: {{ $cuentaAhorro->banco ? ($cuentaAhorro->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaAhorro->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaAhorro->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaAhorro->observaciones ?? '-' }}" style="cursor: help; color: #17a2b8; font-size: 14px; margin-left: 6px;">
                    <i class="bi bi-info-circle"></i>
                </span>
            @endif
        </h4>
        <div class="valor" id="saldoCuentaAhorro">
            ${{ number_format($saldosIniciales['Cuenta de Ahorro'] ?? 0, 0, ',', '.') }}
        </div>
    </div>
    <div class="resumen-card saldo-inicial">
        <h4><i class="bi bi-cash-coin"></i> Saldo Total</h4>
        <div class="valor" id="saldoTotal">
            ${{ number_format($saldosIniciales['Saldo Total'] ?? 0, 0, ',', '.') }}
        </div>
    </div>
</div>
