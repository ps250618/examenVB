<?php

namespace App\Livewire;

use App\Models\Company as CompanyModel;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Company extends Component
{
    public $companies = [];

    public $company = [
        'name' => '',
        'address' => '',
        'email' => '',
        'postal_code' => '',
        'phone_number' => '',
    ];

    public array $rows = [];

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'company.name' => 'required|string|max:255',
            'company.address' => 'required|string|max:255',
            'company.email' => 'required|email|max:255',
            'company.postal_code' => 'required|string|max:20',
            'company.phone_number' => 'required|string|max:30',
        ];
    }

    public function mount(): void
    {
        $this->refreshCompany();
    }

    public function openForm(): void
    {
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->reset('company', 'showForm');
        $this->resetValidation();
    }

    public function refreshCompany(): void
    {
        try {
            $this->companies = CompanyModel::all();

            $this->rows = $this->companies
                ->mapWithKeys(fn ($c) => [$c->id => $c->only([
                    'name', 'address', 'postal_code', 'email', 'phone_number',
                ])])
                ->toArray();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het ophalen van de bedrijven.');

            $this->companies = [];
            $this->rows = [];
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            CompanyModel::create($this->company);

            $this->reset('company', 'showForm');

            $this->refreshCompany();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het opslaan.');

            return;
        }
    }

    public function updateCompany(int $companyId): void
    {
        $this->validate(
            collect($this->rules())
                ->mapWithKeys(fn ($rule, $field) => [
                    "rows.$companyId.".Str::after($field, 'company.') => $rule,
                ])
                ->all()
        );

        try {
            CompanyModel::findOrFail($companyId)->update($this->rows[$companyId]);
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het bijwerken.');
        }
    }

    public function delete($companyId): void
    {
        try {
            $company = CompanyModel::findOrFail($companyId);

            $company->delete();

            $this->refreshCompany();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het verwijderen: '.$e->getMessage());

            return;
        }
    }

    public function render()
    {
        return view('livewire.company');
    }
}
