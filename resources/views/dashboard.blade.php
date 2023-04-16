<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    <br><br>
                    @if(\Illuminate\Support\Facades\Auth::user()->is_admin == 0)
                    <a href="{{ route('reservations.step.one') }}" class="btn btn-primary">Zloz zamówienie</a>

                    <br><br>
                    {{ __("Twoje zamówienia:") }}
                    <?php
                    $rezerwacje = \App\Models\Reservation::all();
                    $stoliki = \App\Models\Table::all();

                    foreach ($rezerwacje as $rezerwacja) {
                        foreach ($stoliki as $stolik) {
                            if ($stolik['id'] == $rezerwacja['table_id']) {
                                $imie_stolika = $stolik['name'];
                            }
                    }
                        if (\Illuminate\Support\Facades\Auth::user()->id == $rezerwacja['used_id']) {
                            echo "<br>";
                            echo "ID: " . $rezerwacja['id'];

                            echo ", First name: " . $rezerwacja['first_name'] . ", Last Name: " . $rezerwacja['last_name'] . ", reservation date: " . $rezerwacja['res_date'] . ", liczba gośći: " . $rezerwacja['guest_number'] . ", imie stolika: " . $imie_stolika;
//                            echo $rezerwacja;
                        }
                    }
                        ?>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



