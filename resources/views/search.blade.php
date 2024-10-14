@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Search for Vaccine Status</h5>
                    <form method="POST" action="{{ route('search') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nid" class="form-label">NID:</label>
                            <input type="text" id="nid" name="nid" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Result -->
@if(isset($result))
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if(!empty($result['status']) && $result['status'] == \App\Contracts\AppContracts::STATUS_NOT_REGISTERED)
                    <div class="alert alert-warning">
                        <h5 class="alert-heading">Status: Not Registered</h5>
                        <p><a href="{{ route('home') }}" class="btn btn-primary">Register now!</a></p>
                    </div>
                @elseif(!empty($result['status']) && $result['status'] == \App\Contracts\AppContracts::STATUS_NOT_SCHEDULED)
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Status: Not Scheduled</h5>
                    </div>
                @elseif(!empty($result['status']) && $result['status'] == 'Scheduled')
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Status: Scheduled</h5>
                        <p>Scheduled Date: {{ $result['date'] }}</p>
                    </div>
                @elseif(!empty($result['status']) && $result['status'] == \App\Contracts\AppContracts::STATUS_VACINATED)
                    <div class="alert alert-success">
                        <h5 class="alert-heading">Status: Vaccinated</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection
