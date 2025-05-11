<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\People;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Notes extends Component
{
    use WithPagination;
    public $noteId;
    public $searchBarber = '';
    
    public function render()
    {
        $barbers = People::when($this->searchBarber, function($query) {
            return $query->where('namebarber', 'like', '%'.$this->searchBarber.'%');
        })->orderBy('namebarber', 'asc')->get();

        return view('livewire.notes', [
            'notes' => Note::fromToday()
                         ->orderBy('created_at', 'asc')
                         ->paginate(20),
            'barbers' => $barbers
        ]);
    }

    public function edit($id)
    {
        $this->dispatch('edit-note', id: $id)->to(EditNote::class);
    }

    public function delete($id)
    {
        $this->noteId = $id;
        Flux::modal('delete-note')->show();
    }

    public function deleteNote()
    {
        Note::find($this->noteId)->delete();
        Flux::modal('delete-note')->close();
        session()->flash('success', 'Agendamento deletado com sucesso');
        $this->redirectRoute('notes', navigate: true);
    }

    public function togglePaid($id, $checked)
    {
        Note::find($id)->update(['paid' => $checked]);
        session()->flash('success', 'Status de pagamento atualizado');
    }
}