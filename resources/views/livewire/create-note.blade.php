<div>
    <flux:modal name="create-note" class="md:w-900">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Criar agendamento</flux:heading>
                <flux:text class="mt-2">Crie o agendamento do Cliente.</flux:text>
            </div>

            <flux:select 
                label="Cliente"
                wire:model="selectedClient"
                searchable
                wire:model.live.debounce.300ms="searchClient"
                placeholder="Pesquise um cliente..."
            >
                @foreach($clients as $client)
                    <option value="{{ $client->firstname }} {{ $client->lastname }}"
                            @if($client->firstname.' '.$client->lastname == $selectedClient) selected @endif>
                        {{ $client->firstname }} {{ $client->lastname }}
                    </option>
                @endforeach
            </flux:select>
            
            <flux:select 
                label="Barbeiro"
                wire:model="selectedBarber"
                searchable
                wire:model.live.debounce.300ms="searchBarber"
                placeholder="Pesquise um barbeiro..."
            >
                @foreach($barbers as $barberOption)
                    <option value="{{ $barberOption->namebarber }}"
                            @if($barberOption->namebarber == $selectedBarber) selected @endif>
                        {{ $barberOption->namebarber }}
                    </option>
                @endforeach
            </flux:select>
            
            <flux:select 
                label="Tipo de Corte"
                wire:model="selectedHaircut"
                searchable
                wire:model.live.debounce.300ms="searchHaircut"
                placeholder="Pesquise um tipo de corte..."
            >
                @foreach($haircuts as $haircut)
                    <option value="{{ $haircut->name }}">
                        {{ $haircut->name }} - R$ {{ number_format($haircut->price, 2, ',', '.') }}
                    </option>
                @endforeach
            </flux:select>
            
            <flux:input 
                type="text" 
                label="Preço" 
                wire:model.lazy="price" 
                placeholder="R$ 0,00"
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

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="save">Salvar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>