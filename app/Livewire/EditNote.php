<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\People;
use App\Models\Client;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Haircut;

class EditNote extends Component
{
    public $noteId;
    public $title;
    public $barber;
    public $haircut;
    public $price;
    public $paid = false;
    public $searchBarber = '';
    public $searchClient = '';
    public $selectedClient = '';
    public $selectedBarber = '';
    public $searchHaircut = '';
    public $selectedHaircut = '';

    #[On('edit-note')]
    public function editNote($id)
    {
        $note = Note::findOrFail($id);
        $this->noteId = $id;
        $this->title = $note->title;
        $this->selectedClient = $note->title; // Set selected client from title
        $this->barber = $note->barber;
        $this->selectedBarber = $note->barber; // Set selected barber
        $this->haircut = $note->haircut;
        $this->price = $this->formatPriceForInput($note->price);
        $this->paid = $note->paid;
        Flux::modal('edit-note')->show();
    }
    public function updatedSelectedHaircut($value)
    {
        if ($value) {
            $haircut = Haircut::where('name', $value)->first();
            if ($haircut) {
                $this->price = 'R$ ' . number_format($haircut->price, 2, ',', '.');
                $this->haircut = $haircut->name;
            }
        }
    }
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'barber' => 'required|string|max:255',
            'haircut' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'paid' => 'boolean'
        ];
    }

    public function render()
    {
        $clientsQuery = Client::query();

        if (strlen($this->searchClient) > 0) {
            $clientsQuery->where(function($query) {
                $query->where('firstname', 'like', '%' . $this->searchClient . '%')
                    ->orWhere('lastname', 'like', '%' . $this->searchClient . '%');
            });
        }

        $clients = $clientsQuery->orderBy('firstname', 'asc')->get();

        if ($this->selectedClient) {
            $selectedClient = Client::whereRaw("CONCAT(firstname, ' ', lastname) = ?", [$this->selectedClient])->first();
            if ($selectedClient && !$clients->contains('id', $selectedClient->id)) {
                $clients->push($selectedClient);
            }
        }

        $barbers = People::when($this->searchBarber, function($query) {
            return $query->where('namebarber', 'like', '%' . $this->searchBarber . '%');
        })->orderBy('namebarber', 'asc')->get();

        if ($this->selectedBarber) {
            $selectedBarber = People::where('namebarber', $this->selectedBarber)->first();
            if ($selectedBarber && !$barbers->contains('id', $selectedBarber->id)) {
                $barbers->push($selectedBarber);
            }
        }
        $haircuts = Haircut::when($this->searchHaircut, function($query) {
            return $query->where('name', 'like', '%' . $this->searchHaircut . '%');
        })->orderBy('name', 'asc')->get();

        return view('livewire.edit-note', [
            'barbers' => $barbers,
            'clients' => $clients,
            'haircuts' => $haircuts
        ]);
    }

    public function update()
    {
        $this->validate();
        
        $price = is_numeric($this->price) ? $this->price : 
                 (float) str_replace(['R$', ',', '.'], ['', '', '.'], $this->price);

        Note::find($this->noteId)->update([
            'title' => $this->title,
            'barber' => $this->barber,
            'haircut' => $this->haircut,
            'price' => $price,
            'paid' => $this->paid,
        ]);

        session()->flash('success', 'Agendamento atualizado com sucesso');
        Flux::modal('edit-note')->close();
        $this->redirectRoute('notes', navigate: true);
    }

    protected function formatPriceForInput($price)
    {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }

    public function updatedSelectedClient($value)
    {
        $this->title = $value;
    }

    public function updatedSelectedBarber($value)
    {
        $this->barber = $value;
    }

    public function updatedSearchClient($value)
    {
        if ($value === '') {
            $this->reset('searchClient');
        }
    }
}