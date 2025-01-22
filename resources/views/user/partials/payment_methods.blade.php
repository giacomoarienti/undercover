<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0">Payment Methods</h2>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#paymentMethodModal">
            <i class="fa fa-plus" aria-hidden="true"></i>
            Add Payment Method
        </button>
    </div>

    <div class="card-body">
        @if($paymentMethods->isEmpty())
            <p class="text-muted">No payment methods added yet.</p>
        @else
            <div class="list-group">
                @foreach($paymentMethods as $method)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($method->type === 'card')
                                    <div>
                                        <div class="fw-bold">Credit Card</div>
                                        <div class="text-muted">{{ $method->card_number }}</div>
                                        <div class="text-muted small">Expires: {{ $method->card_expiration_date }}</div>
                                    </div>
                                @else
                                    <div>
                                        <div class="fw-bold">PayPal</div>
                                        <div class="text-muted">{{ $method->paypal_email }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <form method="POST" action="{{ route('payment-methods') }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $method->id }}">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove this payment method?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Payment Method Modal -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1" aria-labelledby="paymentMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="paymentMethodForm" method="POST" action="{{ route('payment-methods') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="paymentMethodModalLabel">Add Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Type</label>
                        <div class="btn-group w-100" role="group" aria-label="Payment type selection">
                            <input type="radio" class="btn-check" name="type" id="type_credit_card" value="card" checked>
                            <label class="btn btn-outline-primary" for="type_credit_card">
                                Credit Card
                            </label>

                            <input type="radio" class="btn-check" name="type" id="type_paypal" value="paypal">
                            <label class="btn btn-outline-primary" for="type_paypal">
                                PayPal
                            </label>
                        </div>
                    </div>

                    <div id="creditCardFields">
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text"
                                   class="form-control @error('card_number') is-invalid @enderror"
                                   id="card_number"
                                   name="card_number"
                                   pattern="[0-9]{16}"
                                   maxlength="16"
                                   required>
                            @error('card_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="card_expiration_date" class="form-label">Expiration Date</label>
                                <input type="text"
                                       class="form-control @error('card_expiration_date') is-invalid @enderror"
                                       id="card_expiration_date"
                                       name="card_expiration_date"
                                       placeholder="MM/YYYYY"
                                       pattern="(0[1-9]|1[0-2])\/\d{4}"
                                       maxlength="7"
                                       required>
                                @error('card_expiration_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="card_cvv" class="form-label">CVV</label>
                                <input type="text"
                                       class="form-control @error('card_cvv') is-invalid @enderror"
                                       id="card_cvv"
                                       name="card_cvv"
                                       pattern="[0-9]{3,4}"
                                       maxlength="4"
                                       required>
                                @error('card_cvv')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="paypalFields" style="display: none;">
                        <div class="mb-3">
                            <label for="paypal_email" class="form-label">PayPal Email</label>
                            <input type="email"
                                   class="form-control @error('paypal_email') is-invalid @enderror"
                                   id="paypal_email"
                                   name="paypal_email">
                            @error('paypal_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('paymentMethodModal');
            const form = document.getElementById('paymentMethodForm');
            const typeRadios = document.querySelectorAll('input[name="type"]');
            const creditCardFields = document.getElementById('creditCardFields');
            const paypalFields = document.getElementById('paypalFields');

            // Handle payment type toggle
            typeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'card') {
                        creditCardFields.style.display = 'block';
                        paypalFields.style.display = 'none';
                        document.querySelectorAll('#creditCardFields input').forEach(input => input.required = true);
                        document.querySelectorAll('#paypalFields input').forEach(input => input.required = false);
                    } else {
                        creditCardFields.style.display = 'none';
                        paypalFields.style.display = 'block';
                        document.querySelectorAll('#creditCardFields input').forEach(input => input.required = false);
                        document.querySelectorAll('#paypalFields input').forEach(input => input.required = true);
                    }
                });
            });
        });
    </script>
@endpush
