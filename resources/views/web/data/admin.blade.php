@extends('layouts.app')

@section('title', 'Halaman Dashboard')

@auth
    @php $userRole = Auth::user()->user_level; @endphp
@endauth

@section('header')
    <style>
        body {
            background: #202340;
            width: 100vw;
            height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 0px;
        }
    </style>
    @include('layouts.nav_admin')
@endsection

@section('main')
    <section class="pt-2 container pb-5">
        <div class="card text-center border-2">
            <div class="card-body">
                <h2 class="card-title">Dashboard Admin</h2>
                <h4 class="card-text text-body-secondary">
                    Halaman Dashboard Admin
                </h4>
            </div>
        </div>
        <div class="container-fluid px-4 pt-4 pb-5">
            <div class="row gap-4 justify-content-sm-around">
                {{-- Card 1 --}}
                <div class="card shadow-lg text-bg-primary mb-3" style="max-width: 18rem">
                    <div class="card-header">Data User</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ count($data_user) }} User</h5>
                        <p class="card-text">
                            {{ count($data_user) }} Data User tersimpan pada database
                        </p>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="card shadow-lg text-bg-success mb-3" style="max-width: 18rem">
                    <div class="card-header">Data Pakaian</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ count($data_pakaian) }} Pakaian</h5>
                        <p class="card-text">
                            {{ count($data_pakaian) }} Data Pakaian terdapat pada database
                        </p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="card shadow-lg text-bg-warning mb-3" style="max-width: 18rem">
                    <div class="card-header">Data Pembelian</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ count($data_pembelian) }} Pembelian</h5>
                        <p class="card-text">
                            {{ count($data_pembelian) }} Data Pembelian terdapat pada database
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <div class="fixed-bottom p-3" style="background-color: #8D99AE;">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Thrift Shop 2023</div>
        </div>
    </div>
@endsection
