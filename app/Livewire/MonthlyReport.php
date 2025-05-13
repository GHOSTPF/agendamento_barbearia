<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\People;
use Livewire\Component;

class MonthlyReport extends Component
{
    public $month;
    public $year;
    public $selectedBarber = 'Todos';
    public $searchBarber = '';
    public $showReport = false;
    public $total = 0;

    public function mount()
    {
        $this->month = date('m');
        $this->year = date('Y');
    }

    public function render()
    {
        $barbers = People::when($this->searchBarber, function($query) {
            return $query->where('namebarber', 'like', '%'.$this->searchBarber.'%');
        })->orderBy('namebarber', 'asc')->get();

        $notes = collect();
        $this->total = 0;

        if ($this->showReport) {
            $notes = Note::query()
                ->byMonth($this->month, $this->year)
                ->when($this->selectedBarber !== 'Todos', function($query) {
                    return $query->where('barber', $this->selectedBarber);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            $this->total = $notes->sum('price');
        }

        return view('livewire.monthly-report', [
            'notes' => $notes,
            'barbers' => $barbers
        ]);
    }

    public function generateReport()
    {
        $this->validate([
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric|min:2000|max:'.(date('Y')+1),
        ]);

        $this->showReport = true;
    }

    public function resetReport()
    {
        $this->reset(['month', 'year', 'selectedBarber', 'showReport', 'total']);
        $this->month = date('m');
        $this->year = date('Y');
    }
}