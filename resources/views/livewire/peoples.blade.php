<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Barbeiro') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Crie seu Barbeiro') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <flux:modal.trigger name="create-people">
        <flux:button icon="plus" class="mt-4">Criar Barbeiro</flux:button>
    </flux:modal.trigger> 
    <livewire:create-people />
    <livewire:edit-people />

    @session('success')
    <div 
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => { show = false }, 3000)"
        style="background-color: #16a34a; justify-content: flex-end; position: fixed; 
            top: 130px; 
            right: 1rem; /* Alterado de margin-left para right */
            z-index: 50; 
            padding: 1rem; 
            border-radius: 0.5rem; 
            font-size: 0.875rem;
            line-height: 1.25rem; 
            color: #ffffff; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            max-width: calc(100% - 2rem); /* Garante que não ultrapasse a tela em mobile */
            transform: translateX(0); /* Remove qualquer transformação */
            left: auto; /* Garante que o left não interfira */
            margin-left: 0; /* Remove a margem esquerda fixa */"
        role="alert"
    >
        <p>{{ $value }}</p>
    </div>
    @endsession

    <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table style="width: 100%; background-color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 0.375rem; margin-top: 1.25rem; min-width: 600px;">
            <thead style="background-color: #0f172a;">
                <tr>
                    <th style="padding: 0.5rem 1rem; text-align: left;">ID</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;">Barbeiro</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peoples as  $people)
                    <tr style="border-top: 1px solid #334155;">
                        <td style="padding: 0.5rem 1rem;">{{ $people->id }}</td>
                        <td style="padding: 0.5rem 1rem;">{{ $people->namebarber }}</td>
                        <td style="padding: 0.5rem 1rem; text-align: center;">
                            <flux:button title="Editar" icon="pencil" wire:click="editPeople({{ $people->id }})"></flux:button>
                            <flux:button title="Excluir" icon="trash" variant="danger" wire:click="deletePeople({{ $people->id }})"></flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 0.5rem 1rem; text-align: center; color: gray;">
                            Nenhum barbeiro
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <flux:modal name="delete-people" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Excluir Barbeiro ?</flux:heading>

                <flux:text class="mt-2">
                    <p>Você está prestes a excluir este Barbeiro</p>
                    <p>Essa ação não pode ser desfeita!</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger" wire:click="deletePeoples()">Excluir</flux:button>
            </div>
        </div>
    </flux:modal>
</div>