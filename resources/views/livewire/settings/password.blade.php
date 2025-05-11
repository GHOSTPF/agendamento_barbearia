<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Atualizar senha')" :subheading="__('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Senha atual')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="__('Nova senha')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirmar a nova senha')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Salvar') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Salva.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
