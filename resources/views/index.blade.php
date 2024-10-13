
@extends('layouts.app')
@section('content')
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <h1 class="main_title">Take your COVID vaccine</h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="sub_title mb-5">Register now to get vaccinated for COVID-19.</p>
                    <a class="btn btn-primary btn-xl" href="#registration">Register now</a>
                </div>
            </div>
        </div>
    </header>
    <section class="page-section bg-secondary" id="registration">
        <div class="container px-4 px-lg-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('registration_submit') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input class="form-control" id="name" name="name" type="text"
                        placeholder="Enter your name..." data-sb-validations="required" />
                    <label for="name">Name</label>
                    <div class="invalid-feedback" data-sb-feedback="name:required">A name is required.</div>
                </div>
                <!-- Email address input-->
                <div class="form-floating mb-3">
                    <input class="form-control" id="email" type="email" name="email"
                        placeholder="name@example.com" data-sb-validations="required,email" />
                    <label for="email">Email address</label>
                    <div class="invalid-feedback" data-sb-feedback="email:required">An email is required.</div>
                    <div class="invalid-feedback" data-sb-feedback="email:email">Email is not valid.</div>
                </div>
                <!-- Phone number input-->
                <div class="form-floating mb-3">
                    <input class="form-control" id="phone" name="mobile_number" type="tel"
                        placeholder="(123) 456-7890" data-sb-validations="required" />
                    <label for="phone">Phone number</label>
                    <div class="invalid-feedback" data-sb-feedback="phone:required">A phone number is required.</div>
                </div>

                <div class="form-floating mb-3">
                    <input class="form-control" id="nid" name="nid" type="text"
                        placeholder="Your nid no: (eg. 913 ***)" data-sb-validations="required" />
                    <label for="nid">Nid card no</label>
                    <div class="invalid-feedback" data-sb-feedback="nid:required">A nid number is required.</div>
                </div>

                <div class="form-floating mb-3">
                    <select name="vaccine_center_id" id="vaccine_center_id" class="form-control">
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                    <label for="vaccine_center_id">Vacination center</label>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary btn-xl" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </section>
@endsection
