<?php

namespace App\Livewire;

use App\Models\People;
use Flux\Flux;
use Livewire\Component;

class CreatePeople extends Component
{
    public $namebarber;
    protected function rules()
    {
        return [
            'namebarber' => 'required|string|max:255',
        ];
    }
    public function savePeople()
    {
        $this->validate();
        
        People::create([
            "namebarber" => $this->namebarber,
 
        ]);

        $this->reset();

        Flux::modal('create-people')->close();

        session()->flash('success', 'Barbeiro adicionado com sucesso');

        $this->redirectRoute('peoples', navigate: true);
    }
    public function render()
    {
        return view('livewire.create-people');
    }
}
