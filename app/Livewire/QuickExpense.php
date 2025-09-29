<?php

namespace App\Livewire;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class QuickExpense extends Component
{
    #[Rule('required|string|min:2')]
    public string $title = '';

    #[Rule('required|numeric|min:0')]
    public string $amount = '';

    public string $note = '';

    public function save(): void
    {
        $this->validate();

        Expense::create([
            'title' => $this->title,
            'amount' => (float) $this->amount,
            'user_id' => Auth::id(),
            'expense_date' => now(),
            'note' => $this->note ?: null,
        ]);

        $this->reset(['title', 'amount', 'note']);
        $this->dispatch('toast', message: 'Expense saved');
    }

    public function render()
    {
        return view('livewire.quick-expense');
    }
}


