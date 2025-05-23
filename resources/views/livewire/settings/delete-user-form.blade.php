<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Deletar conta?') }}</flux:heading>
        <flux:subheading>{{ __('Exclua sua conta e todos os seus recursos') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Deletar conta') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Tem certeza de que deseja excluir sua conta?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Após a exclusão da sua conta, todos os seus recursos e dados serão excluídos permanentemente. Digite sua senha para confirmar que deseja excluir sua conta permanentemente.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Senha')" type="password" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Deletar conta') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
