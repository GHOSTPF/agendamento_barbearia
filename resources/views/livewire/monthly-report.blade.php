<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Relatório Mensal') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Relatório de agendamentos por mês de cada barbeiro') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <div class="flex justify-between items-center mb-6 mt-6">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                @php
                    $meses = [
                        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                @endphp
                <label for="month" class="text-sm text-white">Mês:</label>
                <select 
                    id="month" 
                    wire:model="month" 
                    style="background-color:#474747;"
                    class="px-3 py-2  rounded-md text-sm text-white border border-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition cursor-pointer"
                >
                    @foreach($meses as $num => $nome)
                        <option value="{{ $num }}" style="background-color:#474747;" class=" text-white">
                            {{ $nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-center gap-2">
                <label for="year" class="text-sm text-white">Ano:</label>
                <input 
                    type="number" 
                    id="year" 
                    wire:model="year" 
                    min="2000" 
                    max="{{ date('Y')+1 }}"
                    style="background-color:#474747;"
                    class="px-3 py-2  rounded-md text-sm text-white border border-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition"
                >
            </div>
            
            <div class="flex items-center gap-2">
                <label for="selectedBarber" class="text-sm text-white">Barbeiro:</label>
                <select 
                    id="selectedBarber" 
                    wire:model="selectedBarber" 
                    style="background-color:#474747;"
                    class="px-3 py-2  rounded-md text-sm text-white border border-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition cursor-pointer"
                >
                    <option value="Todos" style="background-color:#474747;" class=" text-white">Todos</option>
                    @foreach($barbers as $barber)
                        <option value="{{ $barber->namebarber }}" style="background-color:#474747;" class=" text-white">{{ $barber->namebarber }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-center gap-2">
                <flux:button wire:click="generateReport" class="bg-purple-600 hover:bg-purple-700 text-white">Gerar Relatório</flux:button>
                @if($showReport)
                    <flux:button wire:click="resetReport" variant="ghost" class="text-white hover:bg-gray-600">Limpar</flux:button>
                @endif
            </div>
        </div>
    </div>

    @if($showReport)
        <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; background-color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 0.375rem; margin-top: 1.25rem; min-width: 600px;">
                <thead style="background-color: #0f172a;">
                    <tr>
                        <th style="padding: 0.5rem 1rem; text-align: left;">Barbeiro</th>
                        <th style="padding: 0.5rem 1rem; text-align: left;">Cliente</th>
                        <th style="padding: 0.5rem 1rem; text-align: left;">Valor</th>
                        <th style="padding: 0.5rem 1rem; text-align: left;">Data/Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notes as $note)
                        <tr style="border-top: 1px solid #334155;">
                            <td style="padding: 0.5rem 1rem;">{{ $note->barber }}</td>
                            <td style="padding: 0.5rem 1rem;">{{ $note->title }}</td>
                            <td style="padding: 0.5rem 1rem;">R$ {{ number_format($note->price, 2, ',', '.') }}</td>
                            <td style="padding: 0.5rem 1rem;">
                                {{ $note->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 0.5rem 1rem; text-align: center; color: gray;">
                                Nenhum agendamento encontrado para este período
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($notes->isNotEmpty())
            <div class="mt-4 p-4 bg-gray-800 rounded-md">
                <h3 class="text-lg font-semibold text-white">Total: R$ {{ number_format($total, 2, ',', '.') }}</h3>
            </div>
        @endif
    @endif
</div>