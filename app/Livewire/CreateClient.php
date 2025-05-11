<?php

namespace App\Livewire;
use App\Models\Client;
use Flux\Flux;
use Livewire\Component;

class CreateClient extends Component
{
    public $firstname;
    public $lastname;
    protected function rules()
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ];
    }
    public function saveClients()
    {
        $this->validate();
        
        Client::create([
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
        ]);

        $this->reset();

        Flux::modal('create-client')->close();

        session()->flash('success', 'Cliente adicionado com sucesso');

        $this->redirectRoute('client', navigate: true);
    }
    public function render()
    {
        return view('livewire.create-client');
    }
}
