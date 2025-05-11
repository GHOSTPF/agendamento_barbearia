<div>
    <flux:modal name="edit-people" class="md:w-900">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar Barbeiro</flux:heading>
            </div>

            <flux:input label="Barbeiro" wire:model="namebarber" placeholder="Nome do Barbeiro" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="updatePeople">Salvar Alterações</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
