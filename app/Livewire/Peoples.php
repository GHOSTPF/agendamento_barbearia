<?php

namespace App\Livewire;

use App\Models\People;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Peoples extends Component
{
    use WithPagination;
    public $noteId;
    public function render()
    {
        $peoples = People::orderBy('created_at', 'asc')->paginate(5);
        return view('livewire.peoples',[
            'peoples' => $peoples
        ]);
    }
    public function editPeople($id)
    {
        $this->dispatch('edit-people', $id);
    }

    public function deletePeople($id)
    {
        $this->noteId = $id;
        Flux::modal('delete-people')->show();
    }

    public function deletePeoples()
    {
        People::find($this->noteId)->delete();
        Flux::modal('delete-people')->close();
        session()->flash('success', 'Barbeiro deletado com sucesso');
        $this->redirectRoute('peoples', navigate: true);
    }
}
