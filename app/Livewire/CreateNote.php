<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\People;
use App\Models\Client;
use App\Models\Haircut;
use Flux\Flux;
use Livewire\Component;

class CreateNote extends Component
{
    public $title;
    public $barber;
    public $haircut;
    public $price;
    public $paid = false;
    public $searchBarber = ''; 
    public $searchClient = '';
    public $searchHaircut = '';
    public $selectedClient = '';
    public $selectedBarber = '';
    public $selectedHaircut = '';

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
    
    public function mount()
    {
        $firstBarber = People::orderBy('namebarber', 'asc')->first();
        if ($firstBarber) {
            $this->barber = $firstBarber->namebarber;
            $this->selectedBarber = $firstBarber->namebarber;
        }
    }

    public function save()
    {
        $this->validate();
        
        $price = is_numeric($this->price) ? $this->price : 
                 (float) str_replace(['R$', ',', '.'], ['', '', '.'], $this->price);

        Note::create([
            "title" => $this->title,
            "barber" => $this->barber,
            "haircut" => $this->haircut,
            "price" => $price,
            "paid" => $this->paid, 
        ]);

        $this->reset();

        Flux::modal('create-note')->close();

        session()->flash('success', 'Agendamento feito com sucesso');

        $this->redirectRoute('notes', navigate: true);
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

        return view('livewire.create-note', [
            'barbers' => $barbers,
            'clients' => $clients,
            'haircuts' => $haircuts
        ]);
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