<div>
    <div class="flex w-full items-start justify-between px-6 py-4">
        <h2 class="text-3xl font-bold text-[#ff8f1f]">Bedrijven</h2>

        <button type="button" wire:click="openForm"
                class="rounded-full bg-[#ff8f1f] px-6 py-2 text-sm font-semibold text-white hover:bg-orange-600">
            Voeg nieuw bedrijf toe
        </button>
    </div>

    @if (session('error'))
        <div class="mx-6 mb-4 rounded-xl bg-red-100 px-4 py-2 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($showForm)
        <form wire:submit="save"
              class="mx-6 mb-6 rounded-xl bg-[#ff8f1f] p-6 text-white">
            <h3 class="mb-4 text-xl font-bold">Nieuw bedrijf</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ([
                    'name' => 'Naam',
                    'address' => 'Adres',
                    'postal_code' => 'Postcode',
                    'email' => 'E-mail',
                    'phone_number' => 'Telefoonnummer',
                ] as $field => $label)
                    <div>
                        <label class="mb-1 block text-sm font-semibold">{{ $label }}</label>
                        <input type="text"
                               wire:model="company.{{ $field }}"
                               class="w-full rounded-md bg-white/20 px-3 py-2 text-sm text-white placeholder-white/70 focus:bg-white/30 focus:outline-none">
                        @error("company.$field")
                        <div class="mt-1 text-xs font-semibold text-red-900">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" wire:click="closeForm"
                        class="rounded-full border-2 border-white px-5 py-2 text-sm font-semibold text-white hover:bg-white/20">
                    Annuleren
                </button>
                <button type="submit"
                        class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-[#ff8f1f] hover:bg-orange-100">
                    Opslaan
                </button>
            </div>
        </form>
    @endif

    <div class="space-y-3 px-6 pb-6">
        @forelse ($companies as $company)
            <div wire:key="company-{{ $company->id }}"
                 class="grid grid-cols-1 items-start gap-3 rounded-xl bg-[#ff8f1f] px-6 py-4 text-white md:grid-cols-6">

                @foreach ([
                    'name' => 'Naam',
                    'address' => 'Adres',
                    'postal_code' => 'Postcode',
                    'email' => 'E-mail',
                    'phone_number' => 'Telefoon',
                ] as $field => $label)
                    <div>
                        <input type="text"
                               wire:model="rows.{{ $company->id }}.{{ $field }}"
                               wire:change="updateCompany({{ $company->id }})"
                               placeholder="{{ $label }}"
                               class="w-full rounded-md bg-white/20 px-3 py-2 text-sm text-white placeholder-white/70 focus:bg-white/30 focus:outline-none">
                        @error("rows.{$company->id}.{$field}")
                        <div class="mt-1 text-xs font-semibold text-red-900">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <div class="flex justify-end">
                    <button type="button"
                            wire:click="delete({{ $company->id }})"
                            wire:confirm="Weet je zeker dat je dit bedrijf wilt verwijderen?"
                            class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-[#ff8f1f] hover:bg-orange-100">
                        Verwijderen
                    </button>
                </div>
            </div>
        @empty
            <p class="text-[#ff8f1f]">Er zijn nog geen bedrijven.</p>
        @endforelse
    </div>
</div>
