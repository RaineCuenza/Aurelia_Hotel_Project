@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold">Edit Reservation</h1>

    <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="mt-6 rounded-lg bg-white p-5 shadow">
        @csrf
        @method('PUT')
        @include('reservations._form')

        <div class="mt-5 flex gap-3">
            <button class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Update Reservation</button>
            <a href="{{ route('reservations.index') }}" class="rounded bg-slate-200 px-4 py-2 font-semibold">Cancel</a>
        </div>
    </form>
@endsection
