<?php

namespace App\Livewire;

use App\Models\Client;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class EditClient extends Component
{
    public $noteId, $firstname, $lastname;
    #[On('edit-client')]
    public function editClients($id)
    {
        $clients = Client::findOrFail($id);
        $this->noteId = $id;
        $this->firstname = $clients->firstname;
        $this->lastname = $clients->lastname;
        Flux::modal('edit-client')->show();
    }
    public function updateClient()
    {
        $validated = $this->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
        ]);



        Client::find($this->noteId)->update($validated);

        session()->flash('success', 'Cliente editado com sucesso');
        Flux::modal('edit-client')->close();
        $this->redirectRoute('client', navigate: true);
    }
    public function render()
    {
        return view('livewire.edit-client');
    }
}
