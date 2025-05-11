<div>
    <flux:modal name="create-people" class="md:w-900">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Criar um Barbeiro</flux:heading>
            </div>

            <flux:input label="Barbeiro" wire:model="namebarber" placeholder="Nome do Barbeiro" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="savePeople">Salvar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
