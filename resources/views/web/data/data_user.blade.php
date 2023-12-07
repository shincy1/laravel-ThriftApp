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
                <h2 class="card-title">Data User</h2>
                <h4 class="card-text text-body-secondary">Halaman Data User</h4>
            </div>
        </div>
        <div class="container-fluid px-4 pt-4 pb-5">
            <div class="row gap-4 justify-content-sm-around">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif (session('updated'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('updated') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif (session('deleted'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('deleted') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <center>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertUserModal">
                        Tambah User
                    </button>
                </center>
                <div class="table-responsive">
                    <table class="table align-middle table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <tr>
                                <th>No</th>
                                <th>Profil</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No. Telp</th>
                                <th>Alamat</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th colspan="2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($data_user as $items)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if ($items->user_profil_url === '' || $items->user_profil_url === null)
                                            <img width="100px" height="100px" src="{{ asset('img/user.png') }}"
                                                alt="..." class="img-profile img-thumbnail">
                                        @else
                                            <img width="100px" height="100px"
                                                src="{{ asset('storage/user/profile/' . basename($items->user_profil_url)) }}"
                                                alt="..." class="img-profile img-thumbnail">
                                        @endif
                                    </td>
                                    <td>{{ $items->user_fullname }}</td>
                                    <td>{{ $items->user_username }}</td>
                                    <td>{{ $items->user_email }}</td>
                                    <td>{{ $items->user_notelp }}</td>
                                    <td>{{ $items->user_alamat }}</td>
                                    @switch($items->user_level)
                                        @case ('admin')
                                            <td><button type="button" class="btn btn-outline-primary btn-sm w-100">Admin</button>
                                            </td>
                                        @break

                                        @case ('pengguna')
                                            <td><button type="button"
                                                    class="btn btn-outline-success btn-sm w-100">Pengguna</button></td>
                                        @break

                                        @default
                                            <td><button type="button" class="btn btn-outline-warning btn-sm w-100">Kesalahan
                                                    Data</button></td>
                                        @break
                                    @endswitch
                                    @switch($items->user_status)
                                        @case ('0')
                                            <td><button type="button" class="btn btn-outline-danger btn-sm w-100">Tidak
                                                    Aktif</button></td>
                                        @break

                                        @case ('1')
                                            <td><button type="button" class="btn btn-outline-success btn-sm w-100">Aktif</button>
                                            </td>
                                        @break

                                        @default
                                            <td><button type="button" class="btn btn-outline-warning btn-sm w-100">Kesalahan
                                                    Data</button></td>
                                        @break
                                    @endswitch
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#updateUserModal_{{ $items->user_id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteUserModal_{{ $items->user_id }}">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                {{-- ? Modal Update ? --}}
                                <div class="modal fade" data-bs-backdrop="static"
                                    id="updateUserModal_{{ $items->user_id }}" tabindex="-1"
                                    aria-labelledby="updateUserModalLabel_{{ $items->user_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5"
                                                    id="updateUserModalLabel_{{ $items->user_id }}">Ubah
                                                    Data User</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('data_user.update', ['user_id' => $items->user_id]) }}"
                                                method="post" enctype="multipart/form-data">
                                                <div class="modal-body row g-3">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="d-grid">
                                                        <label for="profil" class="form-label">Foto Profil</label>
                                                        <input type="file" name="profil" class="form-control"
                                                            id="profil" placeholder="Tambahkan Foto Profil" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="fullname" class="form-label">Nama User</label>
                                                        <input type="text" name="fullname" class="form-control"
                                                            id="nama" placeholder="Masukkan Nama User"
                                                            value="{{ $items->user_fullname }}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="username" class="form-label">Username User</label>
                                                        <input type="text" name="username" class="form-control"
                                                            id="username" placeholder="Masukkan Username User"
                                                            value="{{ $items->user_username }}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="password" class="form-label">Password User</label>
                                                        <input type="password" name="password" class="form-control"
                                                            id="password" placeholder="Masukkan Password User"
                                                            value="{{ old('password') }}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email" class="form-label">E-mail User</label>
                                                        <input type="email" name="email" class="form-control"
                                                            id="email" placeholder="Masukkan E-mail User"
                                                            value="{{ $items->user_email }}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="notelp" class="form-label">No. Telp User</label>
                                                        <input type="number" name="notelp" class="form-control"
                                                            id="notelp" placeholder="Masukkan No. Telp User"
                                                            value="{{ $items->user_notelp }}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="alamat" class="form-label">Alamat User</label>
                                                        <input type="text" name="alamat" class="form-control"
                                                            id="alamat" placeholder="Masukkan Alamat User"
                                                            value="{{ $items->user_alamat }}" />
                                                    </div>
                                                    <div class="col-md-6 d-grid">
                                                        <label for="level" class="form-label">Level User</label>
                                                        <div class="btn-group" role="group"
                                                            aria-label="Basic radio toggle button group">
                                                            <input type="radio" class="btn-check" name="level"
                                                                id="level1_{{ $items->user_id }}" value="pengguna"
                                                                autocomplete="off"
                                                                {{ $items->user_level == 'pengguna' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-primary"
                                                                for="level1_{{ $items->user_id }}">Pengguna</label>
                                                            <input type="radio" class="btn-check" name="level"
                                                                id="level2_{{ $items->user_id }}" value="admin"
                                                                autocomplete="off"
                                                                {{ $items->user_level == 'admin' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-warning"
                                                                for="level2_{{ $items->user_id }}">Admin</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 d-grid">
                                                        <label for="status" class="form-label">Status Aktif</label>
                                                        <div class="btn-group" role="group"
                                                            aria-label="Basic radio toggle button group">
                                                            <input type="radio" class="btn-check" name="status"
                                                                id="status1_{{ $items->user_id }}" value="1"
                                                                autocomplete="off"
                                                                {{ $items->user_status != 0 ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-success"
                                                                for="status1_{{ $items->user_id }}">Aktif</label>
                                                            <input type="radio" class="btn-check" name="status"
                                                                id="status2_{{ $items->user_id }}" value="0"
                                                                autocomplete="off"
                                                                {{ $items->user_status == 0 ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-danger"
                                                                for="status2_{{ $items->user_id }}">Tidak
                                                                Aktif</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- ? Modal Delete ? --}}
                                <div class="modal fade" data-bs-backdrop="static"
                                    id="deleteUserModal_{{ $items->user_id }}" tabindex="-1"
                                    aria-labelledby="deleteUserModalLabel_{{ $items->user_id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5"
                                                    id="deleteUserModalLabel_{{ $items->user_id }}">
                                                    Konfirmasi Hapus</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Data yang dipilih akan dihapus? Apakah anda yakin?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tidak (Batal Hapus)</button>
                                                <form class="d-grid"
                                                    action="{{ route('data_user.delete', ['user_id' => $items->user_id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger">Ya (Konfirmasi Hapus)</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- ? Modal Insert ? --}}
                            <div class="modal fade" data-bs-backdrop="static" id="insertUserModal" tabindex="-1"
                                aria-labelledby="insertUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="insertUserModalLabel">Tambah
                                                Data User</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('action.create_data_user') }}" method="post"
                                            enctype="multipart/form-data">
                                            <div class="modal-body row g-3">
                                                @csrf
                                                <div class="d-grid">
                                                    <label for="profil" class="form-label">Foto Profil</label>
                                                    <input type="file" name="profil" class="form-control"
                                                        id="profil" placeholder="Tambahkan Foto Profil" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="fullname" class="form-label">Nama User</label>
                                                    <input type="text" name="fullname" class="form-control"
                                                        id="nama" placeholder="Masukkan Nama User" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-label">Username User</label>
                                                    <input type="text" name="username" class="form-control"
                                                        id="username" placeholder="Masukkan Username User" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">Password User</label>
                                                    <input type="password" name="password" class="form-control"
                                                        id="password" placeholder="Masukkan Password User" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">E-mail User</label>
                                                    <input type="email" name="email" class="form-control"
                                                        id="email" placeholder="Masukkan E-mail User" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="notelp" class="form-label">No. Telp User</label>
                                                    <input type="number" name="notelp" class="form-control"
                                                        id="notelp" placeholder="Masukkan No. Telp User" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="alamat" class="form-label">Alamat User</label>
                                                    <input type="text" name="alamat" class="form-control"
                                                        id="alamat" placeholder="Masukkan Alamat User" />
                                                </div>
                                                <div class="col-md-6 d-grid">
                                                    <label for="level" class="form-label">Level User</label>
                                                    <div class="btn-group" role="group"
                                                        aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="level"
                                                            id="level1" value="pengguna" autocomplete="off" checked>
                                                        <label class="btn btn-outline-primary"
                                                            for="level1">Pengguna</label>
                                                        <input type="radio" class="btn-check" name="level"
                                                            id="level2" value="admin" autocomplete="off">
                                                        <label class="btn btn-outline-warning"
                                                            for="level2">Admin</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-grid">
                                                    <label for="status" class="form-label">Status Aktif</label>
                                                    <div class="btn-group" role="group"
                                                        aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="status"
                                                            id="status1" value="1" autocomplete="off" checked>
                                                        <label class="btn btn-outline-success"
                                                            for="status1">Aktif</label>
                                                        <input type="radio" class="btn-check" name="status"
                                                            id="status2" value="0" autocomplete="off">
                                                        <label class="btn btn-outline-danger" for="status2">Tidak
                                                            Aktif</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                    {{ $data_user->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <div class="fixed-bottom p-3 bg-dark-subtle">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Thrift Shop 2023</div>
        </div>
    </div>
@endsection
