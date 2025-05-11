<div>
    <flux:modal name="create-client" class="md:w-900">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Criar um Cliente</flux:heading>
            </div>

            <flux:input label="Nome" wire:model="firstname" placeholder="Nome" />
            <flux:input label="Sobrenome" wire:model="lastname" placeholder="Sobrenome" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="saveClients">Salvar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

