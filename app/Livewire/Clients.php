<?php

namespace App\Livewire;

use App\Models\Client;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithPagination;
    public $noteId;
    public function render()
    {
        $clients = Client::orderBy('created_at', 'asc')->paginate(5);
        return view('livewire.clients',[
            'clients' => $clients
        ]);
    }
    public function editClient($id)
    {
        $this->dispatch('edit-client', $id);
    }

    public function deleteClient($id)
    {
        $this->noteId = $id;
        Flux::modal('delete-client')->show();
    }

    public function deleteClients()
    {
        Client::find($this->noteId)->delete();
        Flux::modal('delete-client')->close();
        session()->flash('success', 'Cliente deletado com sucesso');
        $this->redirectRoute('client', navigate: true);
    }
}
