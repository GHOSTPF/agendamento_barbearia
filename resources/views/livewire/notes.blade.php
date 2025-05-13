<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Agendamento') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Faça seu agendamento') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-2 mt-2">

    <flux:modal.trigger name="create-note">
        <flux:button icon="plus">Criar agendamento</flux:button>
    </flux:modal.trigger>

    <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <label for="startDate" class="text-sm text-white">De:</label>
                <input 
                    type="text" 
                    id="startDate" 
                    wire:model="startDate" 
                    placeholder="dd/mm/aaaa" 
                    style="background-color:#474747;"
                    class="px-3 py-2  rounded-md text-sm text-white border border-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition"
                    x-mask="99/99/9999"
                >
            </div>
            
            <div class="flex items-center gap-2">
                <label for="endDate" class="text-sm text-white">Até:</label>
                <input 
                    type="text" 
                    id="endDate" 
                    wire:model="endDate" 
                    placeholder="dd/mm/aaaa" 
                    style="background-color:#474747;"
                    class="px-3 py-2  rounded-md text-sm text-white border border-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition"
                    x-mask="99/99/9999"
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
                <flux:button wire:click="applyFilter"  class="bg-purple-600 hover:bg-purple-700 text-white">Filtrar</flux:button>
                @if($isFiltered)
                    <flux:button wire:click="resetFilter" variant="ghost" class="text-white hover:bg-gray-600">Limpar</flux:button>
                @endif
            </div>
        </div>
    </div>

    @session('success')
    <div 
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => { show = false }, 3000)"
        style="background-color: #16a34a; justify-content: flex-end; position: fixed; 
            top: 130px; 
            right: 1rem;
            z-index: 50; 
            padding: 1rem; 
            border-radius: 0.5rem; 
            font-size: 0.875rem;
            line-height: 1.25rem; 
            color: #ffffff; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            max-width: calc(100% - 2rem);"
        role="alert"
    >
        <p>{{ $value }}</p>
    </div>
    @endsession

    <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table style="width: 100%; background-color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 0.375rem; margin-top: 1.25rem; min-width: 600px;">
            <thead style="background-color: #0f172a;">
                <tr>
                    <th style="padding: 0.5rem 1rem; text-align: left;">#</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;">Cliente</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;">Barbeiro</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;">Corte</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;">Preço</th>
                    <th style="padding: 0.5rem 1rem; text-align: center;">Pago</th>
                    <th style="padding: 0.5rem 1rem; text-align: center;">Data | Hora</th>
                    <th style="padding: 0.5rem 1rem; text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notes as $key => $note)
                    <tr style="border-top: 1px solid #334155; @if($note->paid) background-color: #47CC89; @endif">
                        <td style="padding: 0.5rem 1rem; @if($note->paid) color: #1e293b; font-weight: 500; @endif">{{ $key+1 }}</td>
                        <td style="padding: 0.5rem 1rem; @if($note->paid) color: #1e293b; font-weight: 500; @endif">{{ $note->title }}</td>
                        <td style="padding: 0.5rem 1rem; @if($note->paid) color: #1e293b; font-weight: 500; @endif">{{ $note->barber }}</td>
                        <td style="padding: 0.5rem 1rem; @if($note->paid) color: #1e293b; font-weight: 500; @endif">{{ $note->haircut }}</td>
                        <td style="padding: 0.5rem 1rem; @if($note->paid) color: #1e293b; font-weight: 500; @endif">R$ {{ number_format($note->price, 2, ',', '.') }}</td>
                        <td style="padding: 0.5rem 1rem; text-align: center;">
                            <input 
                                type="checkbox" 
                                wire:change="togglePaid({{ $note->id }}, $event.target.checked)"
                                {{ $note->paid ? 'checked' : '' }}
                                style="cursor: pointer;"
                            >
                        </td>
                        <td style="padding: 0.5rem 1rem; text-align: center; @if($note->paid) color: #1e293b; font-weight: 500; @endif">
                            {{ $note->created_at->timezone(config('app.timezone'))->format('d/m/Y') }} - {{ $note->created_at->timezone(config('app.timezone'))->format('H:i') }}
                        </td>
                        <td style="padding: 0.5rem 1rem; text-align: center;">
                            <flux:button title="Editar" icon="pencil" wire:click="edit({{ $note->id }})"></flux:button>
                            <flux:button title="Excluir" icon="trash" variant="danger" wire:click="delete({{ $note->id }})"></flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 0.5rem 1rem; text-align: center; color: gray;">
                            Nenhum agendamento encontrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
        }
        
        input[type="text"] {
            width: 100px;
        }
    </style>

    <div class="mt-4">
        {{ $notes->links() }}
    </div>

    <livewire:create-note :key="'create-note-'.now()" />
    <livewire:edit-note/>

    <flux:modal name="delete-note" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Excluir agendamento ?</flux:heading>
                <flux:text class="mt-2">
                    <p>Você está prestes a excluir este agendamento,</p>
                    <p>Essa ação não pode ser desfeita!</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deleteNote()">Excluir</flux:button>
            </div>
        </div>
    </flux:modal>
</div>