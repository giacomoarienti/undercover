<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0">Reception Methods</h2>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#receptionMethodModal">
            <span class="fa fa-plus" aria-hidden="true"></span>
            Add Reception Method
        </button>
    </div>

    <div class="card-body">
        @if($receptionMethods->isEmpty())
            <p class="text-muted">No reception methods added yet.</p>
        @else
            <div class="list-group">
                @foreach($receptionMethods as $method)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="@if($method->default) fw-bold @endif">IBAN</div>
                                    <div class="text-muted">{{ $method->iban_number }}</div>
                                    <div class="text-muted small">
                                        SWIFT: {{ $method->iban_swift }}<br>
                                        Holder: {{ $method->iban_holder_name }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                @if(!$method->default)
                                    <form method="POST" action="{{ route('reception-methods') }}" class="d-inline">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        @method('PATCH')
                                        <input type="hidden" name="id" value="{{ $method->id }}">
                                        <input type="hidden" name="default" value="1">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            Set Default
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('reception-methods') }}" class="d-inline">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $method->id }}">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove this reception method?')">
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

<div class="modal fade" id="receptionMethodModal" tabindex="-1" aria-labelledby="receptionMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="receptionMethodForm" method="POST" action="{{ route('reception-methods') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="type" value="iban">

                <div class="modal-header">
                    <h5 class="modal-title" id="receptionMethodModalLabel">Add Reception Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="iban_number" class="form-label">IBAN Number</label>
                        <input type="text" class="form-control @error('iban_number') is-invalid @enderror"
                               id="iban_number" name="iban_number" maxlength="34" required>
                        @error('iban_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="iban_swift" class="form-label">SWIFT Code</label>
                        <input type="text" class="form-control @error('iban_swift') is-invalid @enderror"
                               id="iban_swift" name="iban_swift" required>
                        @error('iban_swift')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="iban_holder_name" class="form-label">Account Holder Name</label>
                        <input type="text" class="form-control @error('iban_holder_name') is-invalid @enderror"
                               id="iban_holder_name" name="iban_holder_name" required>
                        @error('iban_holder_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
