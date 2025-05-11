<?php

namespace App\Livewire;

use App\Models\Haircut;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class ManageHaircuts extends Component
{
    use WithPagination;
    
    public $name;
    public $price;
    public $editingId = null;
    public $search = '';
    
    // Propriedades para edição
    public $editId;
    public $editName;
    public $editPrice;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'editName' => 'required|string|max:255',
        'editPrice' => 'required|numeric|min:0',
    ];

    public function editHaircut($id)
    {
        $haircut = Haircut::findOrFail($id);
        $this->editId = $haircut->id;
        $this->editName = $haircut->name;
        $this->editPrice = 'R$ ' . number_format($haircut->price, 2, ',', '.');
        
        Flux::modal('edit-haircut')->show();
    }

    public function updateHaircut()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editPrice' => 'required|numeric|min:0',
        ]);
        
        $price = is_numeric($this->editPrice) ? $this->editPrice : 
                 (float) str_replace(['R$', ',', '.'], ['', '', '.'], $this->editPrice);

        Haircut::find($this->editId)->update([
            'name' => $this->editName,
            'price' => $price,
        ]);

        $this->closeEditModal();
        session()->flash('message', 'Corte atualizado com sucesso!');
    }

    public function closeEditModal()
    {
        $this->reset(['editId', 'editName', 'editPrice']);
        Flux::modal('edit-haircut')->close();
    }

    // ... manter os outros métodos existentes (save, delete, etc) ...
    
    public function render()
    {
        $haircuts = Haircut::when($this->search, function($query) {
            return $query->where('name', 'like', '%'.$this->search.'%');
        })->orderBy('name', 'asc')->paginate(10);

        return view('livewire.manage-haircuts', [
            'haircuts' => $haircuts
        ]);
    }
}