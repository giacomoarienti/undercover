@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Coupons</h1>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCouponModal">
            Create
        </button>
    </div>
    <!-- Coupons Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Discount</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->percentage_discount }}</td>
                    <td>
                                <span class="{{ $coupon->is_active ? 'text-success' : 'text-danger' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                    </td>
                    <td>
                        <div role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" title="Edit coupon {{ $coupon->code }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCouponModal"
                                    data-coupon-id="{{ $coupon->id }}"
                                    data-coupon-code="{{ $coupon->code }}"
                                    data-coupon-discount="{{ $coupon->discount * 100 }}"
                                    data-coupon-starts="{{ $coupon->starts_at->format('Y-m-d') }}"
                                    data-coupon-expires="{{ $coupon->expires_at->format('Y-m-d') }}">
                                <span class="fa fa-pencil" aria-hidden="true"></span>
                                <span class="visually-hidden">Edit coupon {{ $coupon->code }}</span>
                            </button>

                            <form action="{{ route('coupons.destroy') }}" method="POST" class="d-inline" title="Delete coupon {{ $coupon->code }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $coupon->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this coupon?')">
                                    <span class="fa fa-trash" aria-hidden="true"></span>
                                    <span class="visually-hidden">Delete coupon {{ $coupon->code }}</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @if ($coupons->hasPages())
        {{ $coupons->links() }}
    @endif

    <!-- Create Coupon Modal -->
    <div class="modal fade" id="createCouponModal" tabindex="-1" aria-labelledby="createCouponModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('coupons.create') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="createCouponModalLabel">Create New Coupon</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="code" class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" id="code" name="code" required />
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" id="discount" name="discount"
                                   min="0" max="100" step="0.01" required />
                        </div>
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="starts_at" name="starts_at" required />
                        </div>
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="expires_at" name="expires_at" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Coupon Modal -->
    <div class="modal fade" id="editCouponModal" tabindex="-1" aria-labelledby="editCouponModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('coupons.update') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    @method('PATCH')
                    <input type="hidden" name="id" id="edit_coupon_id" />
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="editCouponModalLabel">Edit Coupon</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_code" class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" id="edit_code" name="code" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_discount" class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" id="edit_discount" name="discount"
                                   min="0" max="100" step="0.01" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_starts_at" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_starts_at" name="starts_at" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_expires_at" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="edit_expires_at" name="expires_at" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editModal = document.getElementById('editCouponModal');
                if (editModal) {
                    editModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;

                        editModal.querySelector('#edit_coupon_id').value = button.getAttribute('data-coupon-id');
                        editModal.querySelector('#edit_code').value = button.getAttribute('data-coupon-code');
                        editModal.querySelector('#edit_discount').value = button.getAttribute('data-coupon-discount');
                        editModal.querySelector('#edit_starts_at').value = button.getAttribute('data-coupon-starts');
                        editModal.querySelector('#edit_expires_at').value = button.getAttribute('data-coupon-expires');
                    });
                }
            });
        </script>
    @endpush
@endsection
