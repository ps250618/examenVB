<?php

namespace App\Livewire;

use App\Models\Company as CompanyModel;
use App\Models\Employee as EmployeeModel;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Employee extends Component
{
    public $employees = [];

    public $employee = [
        'name' => '',
        'email' => '',
        'phone_number' => '',
        'company_id' => '',
    ];

    public $rows = [];

    public $showForm = false;

    protected function rules(): array
    {
        return [
            'employee.name' => 'required|string|max:255',
            'employee.email' => 'required|email|max:255',
            'employee.phone_number' => 'required|string|max:30',
            'employee.company_id' => 'nullable|exists:companies,id',
        ];
    }

    public function mount(): void
    {
        $this->refreshEmployees();
    }

    public function openForm(): void
    {
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->reset('employee', 'showForm');
        $this->resetValidation();
    }

    public function refreshEmployees(): void
    {
        try {
            $this->employees = EmployeeModel::all();

            $this->rows = $this->employees
                ->mapWithKeys(fn ($e) => [$e->id => $e->only([
                    'name',
                    'email',
                    'phone_number',
                    'company_id',
                ])])
                ->toArray();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het ophalen van de werknemers.');

            $this->employees = [];
            $this->rows = [];
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            $data = $this->employee;
            $data['company_id'] = $data['company_id'] ?: null;

            EmployeeModel::create($data);

            $this->reset('employee', 'showForm');

            $this->refreshEmployees();
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Er is iets misgegaan bij het opslaan.');
        }
    }

    public function updateEmployee(int $employeeId): void
    {
        $this->validate(collect($this->rules())
            ->mapWithKeys(fn ($rule, $field) => [
                "rows.$employeeId.".Str::after($field, 'employee.') => $rule,
            ])
            ->all()
        );

        try {
            $data = $this->rows[$employeeId];
            $data['company_id'] = $data['company_id'] ?: null;

            EmployeeModel::findOrFail($employeeId)->update($data);
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Er is iets misgegaan bij het bijwerken.');
        }
    }

    public function delete($employeeId): void
    {
        try {
            EmployeeModel::findOrFail($employeeId)->delete();

            $this->refreshEmployees();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het verwijderen.');

            return;
        }
    }

    public function render()
    {
        return view('livewire.employee', [
            'companies' => CompanyModel::orderBy('name')->get(),
        ]);
    }
}
