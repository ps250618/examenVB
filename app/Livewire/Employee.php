<?php

namespace App\Livewire;

use App\Models\Employee as EmployeeModel;
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

    protected function rules(): array
    {
        return [
            'employee.name' => 'required|string|max:255',
            'employee.email' => 'required|email|max:255',
            'employee.phone_number' => 'required|string|max:30',
            'employee.company_id' => 'required|exists:companies,id',
        ];
    }

    public function mount(): void
    {
        $this->refreshEmployees();
    }

    public function refreshEmployees(): void
    {
        try {
            $this->employees = EmployeeModel::all();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het ophalen van de werknemers.');

            $this->employees = [];
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            EmployeeModel::create($this->employee);

            $this->reset('employee');

            $this->refreshEmployees();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het opslaan.');

            return;
        }
    }

    public function update($employeeId): void
    {
        $this->validate();

        try {
            $employee = EmployeeModel::findOrFail($employeeId);

            $employee->update($this->employee);

            $this->reset('employee');

            $this->refreshEmployees();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het bijwerken.');

            return;
        }
    }

    public function delete($employeeId): void
    {
        try {
            $employee = EmployeeModel::findOrFail($employeeId);

            $employee->delete();

            $this->refreshEmployees();
        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets misgegaan bij het verwijderen.');

            return;
        }
    }

    public function render()
    {
        return view('livewire.employee');
    }
}
