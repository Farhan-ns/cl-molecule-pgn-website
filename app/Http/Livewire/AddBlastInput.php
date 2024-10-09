<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddBlastInput extends Component
{
    public $i = 0;
    public $inputArray = [];

    public function render()
    {
        return view('livewire.add-blast-input');
    }

    public function mount($inputs) 
    {
        array_push($this->inputArray, $this->i);
    }

    public function addInput()
    {
        $i = $this->i + 1;
        $this->i = $i;
        array_push($this->inputArray, $this->i);
    }

    public function removeInput($i)
    {
        unset($this->inputArray[$i]);
    }
}
