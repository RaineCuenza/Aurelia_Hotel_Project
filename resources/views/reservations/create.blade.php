@extends('layouts.app')

@section('content')
    <h1 class="section-title text-4xl font-semibold text-slate-900">New Reservation</h1>
    <p class="mt-1 text-sm text-slate-500">Fill in the guest and schedule details to create a booking.</p>

    <form action="{{ route('reservations.store') }}" method="POST" class="surface mt-6 p-5">
        @csrf
        @include('reservations._form')

        <div class="mt-5 flex gap-3">
            <button class="btn-primary px-4 py-2.5">Save Reservation</button>
            <a href="{{ route('reservations.index') }}" class="btn-secondary px-4 py-2.5">Cancel</a>
        </div>
    </form>
@endsection
