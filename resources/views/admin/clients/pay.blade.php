@extends('admin.layouts.app', [
    'pageName' => 'Record Payment',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        @include('admin.layouts.partials._flash')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Record Payment for: {{ $client->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.clients.show', $client->id) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th width="200">Current Balance</th><td>{{ number_format($client->balance, 2) }}</td></tr>
                                <tr><th>Client</th><td>{{ $client->name }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="{{ route('admin.clients.payments.store', $client->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Enter payment amount">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description (optional)</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}" placeholder="e.g., Cash payment">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-cash-register"></i> Record Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
