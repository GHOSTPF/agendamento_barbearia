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
    public $startDate;
    public $endDate;
    public $selectedBarber = 'Todos';
    public $isFiltered = false;
    
    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'selectedBarber' => ['except' => 'Todos']
    ];

    public function mount()
    {
        if ($this->startDate || $this->endDate || $this->selectedBarber !== 'Todos') {
            $this->isFiltered = true;
        }
    }

    public function render()
    {
        $barbers = People::when($this->searchBarber, function($query) {
            return $query->where('namebarber', 'like', '%'.$this->searchBarber.'%');
        })->orderBy('namebarber', 'asc')->get();

        $notes = Note::query();
        
        if ($this->isFiltered) {
            if ($this->startDate) {
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $this->startDate)->startOfDay();
                $notes->whereDate('created_at', '>=', $startDate);
            }
            
            if ($this->endDate) {
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $this->endDate)->endOfDay();
                $notes->whereDate('created_at', '<=', $endDate);
            }
            
            $notes->byBarber($this->selectedBarber);
        } else {
            $notes->fromToday();
        }
        
        $notes = $notes->orderBy('created_at', 'asc')->paginate(20);

        return view('livewire.notes', [
            'notes' => $notes,
            'barbers' => $barbers
        ]);
    }

    public function applyFilter()
    {
        $this->validate([
            'startDate' => 'nullable|date_format:d/m/Y',
            'endDate' => 'nullable|date_format:d/m/Y|after_or_equal:startDate',
        ]);
        
        $this->isFiltered = true;
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['startDate', 'endDate', 'selectedBarber', 'isFiltered']);
        $this->resetPage();
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