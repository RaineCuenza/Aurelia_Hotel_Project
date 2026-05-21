<div id="booking-cancel-modal-{{ $book->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-6 backdrop-blur-sm" aria-hidden="true">
    <div class="w-full max-w-2xl rounded-2xl border border-stone-200 bg-white p-5 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-stone-500">Cancel Booking</p>
                <h3 class="mt-1 text-2xl font-bold text-stone-900">Room {{ $book->room->room_number }} • {{ $book->guest_name }}</h3>
            </div>
            <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-sm font-semibold text-stone-600 hover:bg-stone-100" data-cancel-close>Close</button>
        </div>

        <form action="{{ route('bookings.cancel', $book) }}" method="POST" class="mt-5 grid gap-4" data-no-loader>
            @csrf
            @method('PATCH')

            <div>
                <label for="cancellation_reason_{{ $book->id }}" class="mb-2 block text-sm font-semibold text-stone-800">Cancellation Reason</label>
                <textarea id="cancellation_reason_{{ $book->id }}" name="cancellation_reason" rows="4" required minlength="10" maxlength="1000" class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800" placeholder="Enter reason for cancellation...">{{ old('cancellation_reason') }}</textarea>
                @error('cancellation_reason')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="room_status_{{ $book->id }}" class="mb-2 block text-sm font-semibold text-stone-800">Room Status</label>
                <select id="room_status_{{ $book->id }}" name="room_status" class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                    <option value="available" @selected(old('room_status', 'available') === 'available')>Available</option>
                    <option value="maintenance" @selected(old('room_status') === 'maintenance')>Maintenance</option>
                </select>
                @error('room_status')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-start gap-3 rounded-xl border border-stone-200 bg-stone-50 px-4 py-3">
                <input type="checkbox" name="notify_customer" value="1" class="mt-1 rounded border-stone-300" @checked(old('notify_customer', true))>
                <span>
                    <span class="block text-sm font-semibold text-stone-900">Notify Customer</span>
                    <span class="block text-xs text-stone-600">Send a cancellation email to the guest.</span>
                </span>
            </label>

            @if ($book->status === 'checked_in')
                <label class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                    <input type="checkbox" name="allow_checked_in_cancellation" value="1" class="mt-1 rounded border-stone-300">
                    <span>
                        <span class="block text-sm font-semibold text-amber-900">Allow checked-in cancellation</span>
                        <span class="block text-xs text-amber-800">Required because this booking is already checked in.</span>
                    </span>
                </label>
                @error('allow_checked_in_cancellation')
                    <p class="-mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @endif

            <div class="flex justify-end gap-3">
                <button type="button" class="rounded-xl border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700" data-cancel-close>Dismiss</button>
                <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white">Cancel Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        if (window.bookingCancelModalInitialized) return;
        window.bookingCancelModalInitialized = true;

        const toggle = (modal, open) => {
            if (!modal) return;
            modal.classList.toggle('hidden', !open);
            modal.classList.toggle('flex', open);
            modal.setAttribute('aria-hidden', open ? 'false' : 'true');
        };

        document.addEventListener('click', (event) => {
            const opener = event.target.closest('[data-open-booking-cancel-modal]');
            if (opener) {
                toggle(document.getElementById(opener.dataset.openBookingCancelModal), true);
                return;
            }

            const closer = event.target.closest('[data-cancel-close]');
            if (closer) {
                toggle(closer.closest('[id^="booking-cancel-modal-"]'), false);
            }
        });
    })();
</script>