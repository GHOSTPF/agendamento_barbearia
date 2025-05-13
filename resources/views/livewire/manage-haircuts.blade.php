<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Cortes') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Gerencie seus cortes') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <div class="flex gap-4">
        <flux:modal.trigger name="manage-haircuts">
            <flux:button icon="scissors" class="mt-4">Gerenciar Cortes</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:modal name="manage-haircuts" class="w-full max-w-md md:max-w-2xl">
        <div class="space-y-4  p-4 rounded-lg">
            <div>
                <flux:heading size="lg">Gerenciar Tipos de Corte</flux:heading>
                <flux:text class="mt-1 text-gray-300">Adicione, edite ou remova tipos de corte e seus preços.</flux:text>
            </div>

            <div class=" p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                    <div>
                        <flux:input 
                            label="Nome do Corte" 
                            wire:model="name" 
                            placeholder="Ex: Corte social" 
                            class=" border-gray-600"
                        />
                    </div>
                    <div>
                        <flux:input 
                            type="text" 
                            label="Preço" 
                            wire:model.lazy="price" 
                            placeholder="R$ 0,00"
                            class=" border-gray-600"
                            x-data="{
                                formatPrice() {
                                    let value = this.$el.value.replace(/[^\d]/g, '');
                                    value = (value / 100).toLocaleString('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL',
                                        minimumFractionDigits: 2
                                    });
                                    this.$el.value = value;
                                    this.$wire.set('price', value.replace(/[^\d,]/g, '').replace(',', '.'));
                                }
                            }"
                            x-on:blur="formatPrice"
                            x-on:input.debounce.500ms="formatPrice"
                        />
                    </div>
                </div>

                <div class="flex gap-2 justify-start" style="margin-top: 10px;">
                    @if($editingId)
                        <flux:button variant="ghost" wire:click="resetForm">Cancelar</flux:button>
                    @endif
                    <flux:button 
                        variant="primary" 
                        wire:click="save"
                        :disabled="!$name || !$price"
                    >
                        {{ $editingId ? 'Atualizar' : 'Adicionar' }}
                    </flux:button>
                </div>
            </div>

            <flux:input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Pesquisar cortes..." 
                style="display:none;"
                class=" border-gray-600"
            />

            <div class="overflow-x-auto" style="margin-top: 10px;display:none;">
                <table class="w-full rounded-lg overflow-hidden" style="background-color:#0F172A;">
                    <thead style="background-color: #0f172a;">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-200">Nome</th>
                            <th class="px-4 py-2 text-left text-gray-200">Preço</th>
                            <th class="px-4 py-2 text-center text-gray-200"></th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #1E293B;">
                        @forelse($haircuts as $haircut)
                            <tr style="border-top: 1px solid #334155;">
                                <td class="px-4 py-3 text-gray-100">{{ $haircut->name }}</td>
                                <td class="px-4 py-3 text-gray-100">R$ {{ number_format($haircut->price, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-1">
                                        <flux:button 
                                            icon="pencil" 
                                            size="sm" 
                                            wire:click="editHaircut({{ $haircut->id }})"
                                        ></flux:button>
                                        <flux:button 
                                            icon="trash" 
                                            size="sm" 
                                            variant="danger"
                                            wire:click="confirmDelete({{ $haircut->id }})">
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center text-gray-400">
                                    Nenhum corte cadastrado
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $haircuts->links() }}
            </div>
        </div>
    </flux:modal>

    <flux:modal name="edit-haircut" class="w-full max-w-md md:max-w-2xl">
        <div class="space-y-4  p-4 rounded-lg">
            <div>
                <flux:heading size="lg">Editar Corte</flux:heading>
                <flux:text class="mt-1 text-gray-300">Edite os detalhes deste tipo de corte.</flux:text>
            </div>

            <div class=" p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                    <div>
                        <flux:input 
                            label="Nome do Corte" 
                            wire:model="editName" 
                            placeholder="Ex: Corte social" 
                            class=" border-gray-600"
                        />
                    </div>
                    <div>
                        <flux:input 
                            type="text" 
                            label="Preço" 
                            wire:model.lazy="editPrice" 
                            placeholder="R$ 0,00"
                            class=" border-gray-600"
                            x-data="{
                                formatPrice() {
                                    let value = this.$el.value.replace(/[^\d]/g, '');
                                    value = (value / 100).toLocaleString('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL',
                                        minimumFractionDigits: 2
                                    });
                                    this.$el.value = value;
                                    this.$wire.set('editPrice', value.replace(/[^\d,]/g, '').replace(',', '.'));
                                }
                            }"
                            x-on:blur="formatPrice"
                            x-on:input.debounce.500ms="formatPrice"
                        />
                    </div>
                </div>

                <div class="flex gap-2 justify-start" style="margin-top: 10px;">
                    <flux:button variant="ghost" wire:click="closeEditModal">Cancelar</flux:button>
                    <flux:button 
                        variant="primary" 
                        wire:click="updateHaircut"
                        :disabled="!$editName || !$editPrice"
                    >
                        Salvar Alterações
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>

    <flux:input 
    wire:model.live.debounce.300ms="search" 
    placeholder="Pesquisar cortes..." 
    style="margin-top: 10px;"
    class="border-gray-600" 
/>

    <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;" class="mt-4">
        <table style="width: 100%; background-color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-radius: 0.375rem; min-width: 600px;">
            <thead style="background-color: #0f172a;">
                <tr>
                    <th style="padding: 0.5rem 1rem; text-align: left;">Nome</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;">Preço</th>
                    <th style="padding: 0.5rem 1rem; text-align: left;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($haircuts as $haircut)
                    <tr style="border-top: 1px solid #334155;">
                        <td style="padding: 0.5rem 1rem; font-weight: 500;">{{ $haircut->name }}</td>
                        <td style="padding: 0.5rem 1rem; font-weight: 500;">R$ {{ number_format($haircut->price, 2, ',', '.') }}</td>
                        <td style="padding: 0.5rem 1rem; text-align: left;">
                            <flux:button 
                                icon="pencil" 
                                size="sm" 
                                wire:click="editHaircut({{ $haircut->id }})"
                            ></flux:button>
                            <flux:button 
                                icon="trash" 
                                size="sm" 
                                variant="danger"
                                wire:click="confirmDelete({{ $haircut->id }})"> 
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 0.5rem 1rem; text-align: center; color: gray;">
                            Nenhum corte cadastrado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $haircuts->links() }}
    </div>

    @session('message')
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

    <flux:modal name="delete-hair" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Excluir corte?</flux:heading>
                <flux:text class="mt-2">
                    <p>Você está prestes a excluir este corte,</p>
                    <p>Essa ação não pode ser desfeita!</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteNote">Excluir</flux:button>
            </div>
        </div>
    </flux:modal>
</div>