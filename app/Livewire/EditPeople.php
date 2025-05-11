<?php

namespace App\Livewire;

use App\Models\People;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class EditPeople extends Component
{
    public $noteId, $namebarber;
    #[On('edit-people')]
    public function editPeoples($id)
    {
        $peoples = People::findOrFail($id);
        $this->noteId = $id;
        $this->namebarber = $peoples->namebarber;
        Flux::modal('edit-people')->show();
    }
    public function updatePeople()
    {
        $validated = $this->validate([
            'namebarber' => ['required', 'string', 'max:255'],
        ]);



        People::find($this->noteId)->update($validated);

        session()->flash('success', 'Barbeiro editado com sucesso');
        Flux::modal('edit-people')->close();
        $this->redirectRoute('peoples', navigate: true);
    }
    public function render()
    {
        return view('livewire.edit-people');
    }
}
