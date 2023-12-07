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
                <h2 class="card-title">Data Pembelian</h2>
                <h4 class="card-text text-body-secondary">Halaman Data Pembelian</h4>
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
                <div class="table-responsive">
                    <table class="table align-middle table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Pembeli</th>
                                <th>Metode Pembayaran</th>
                                <th>Tanggal Pembelian</th>
                                <th>Total Harga</th>
                                <th>Status Pembelian</th>
                                <th colspan="3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($data_pembelian as $items)
                                <?php $user = \App\Models\Data_User::find($items->pembelian_user_id); ?>
                                <?php $metode = \App\Models\Metode_Pembayaran::find($items->pembelian_metode_pembayaran_id); ?>
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $user->user_fullname }}</td>
                                    <td>{{ $metode->metode_pembayaran_jenis }}</td>
                                    <td>{{ $items->pembelian_tanggal }}</td>
                                    <td>Rp. {{ $items->pembelian_total_harga }}</td>
                                    @switch($items->pembelian_status)
                                        @case ('beli')
                                            <td><button type="button" class="btn btn-outline-warning btn-sm w-100">Beli</button>
                                            </td>
                                        @break

                                        @case ('proses')
                                            <td><button type="button" class="btn btn-outline-info btn-sm w-100">Proses</button>
                                            </td>
                                        @break

                                        @case ('selesai')
                                            <td><button type="button" class="btn btn-outline-success btn-sm w-100">Selesai</button>
                                            </td>
                                        @break

                                        @case ('batal')
                                            <td><button type="button" class="btn btn-outline-danger btn-sm w-100">Batal</button>
                                            </td>
                                        @break

                                        @default
                                            <td><button type="button" class="btn btn-outline-danger btn-sm w-100">Kesalahan
                                                    Data</button></td>
                                        @break
                                    @endswitch
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewPembelianModal_{{ $items->pembelian_id }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#updatePembelianModal_{{ $items->pembelian_id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deletePembelianModal_{{ $items->pembelian_id }}">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                {{-- ? Modal View ? --}}
                                <div class="modal fade" data-bs-backdrop="static"
                                    id="viewPembelianModal_{{ $items->pembelian_id }}" tabindex="-1"
                                    aria-labelledby="viewPembelianModalLabel_{{ $items->pembelian_id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5"
                                                    id="viewPembelianModalLabel_{{ $items->pembelian_id }}">Lihat Detail
                                                    Data Pembelian Pakaian</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group">
                                                    @php
                                                        $details = \App\Models\Detail_Pembelian::where('detail_pembelian_pembelian_id', $items->pembelian_id)->get();
                                                    @endphp
                                                    @foreach ($details as $detail)
                                                        @php
                                                            $pakaian = \App\Models\Data_Pakaian::find($detail->detail_pembelian_pakaian_id);
                                                        @endphp
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $pakaian->pakaian_nama }} - Rp.
                                                            {{ $detail->detail_pembelian_total_harga }}
                                                            <span class="badge rounded-pill"
                                                                style="background-color: #8423ff">Jumlah :
                                                                {{ $detail->detail_pembelian_jumlah }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- ? Modal Update ? --}}
                                <div class="modal fade" data-bs-backdrop="static"
                                    id="updatePembelianModal_{{ $items->pembelian_id }}" tabindex="-1"
                                    aria-labelledby="updatePembelianModalLabel_{{ $items->pembelian_id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5"
                                                    id="updatePembelianModalLabel_{{ $items->pembelian_id }}">Ubah
                                                    Data Pembelian Pakaian</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form
                                                action="{{ route('data_pembelian.update', ['pembelian_id' => $items->pembelian_id]) }}"
                                                method="post">
                                                <div class="modal-body">
                                                    @csrf
                                                    @method ('PATCH')
                                                    <input type="hidden" name="user" class="form-control"
                                                        id="user" value="{{ $items->pembelian_user_id }}" />
                                                    <input type="hidden" name="metode_pembayaran" class="form-control"
                                                        id="metode_pembayaran"
                                                        value="{{ $items->pembelian_metode_pembayaran_id }}" />
                                                    <input type="hidden" name="total_harga" class="form-control"
                                                        id="total_harga" value="{{ $items->pembelian_total_harga }}" />
                                                    <div class="m-2">
                                                        <label for="nama" class="form-label">Tanggal Pembelian</label>
                                                        <input type="date" name="tanggal" class="form-control"
                                                            id="tanggal" placeholder="Masukkan Tanggal Pembelian"
                                                            value="{{ $items->pembelian_tanggal }}" />
                                                    </div>
                                                    <div class="m-2 d-grid">
                                                        <label for="status" class="form-label">Status Pembelian</label>
                                                        <div class="btn-group" role="group"
                                                            aria-label="Basic radio toggle button group">
                                                            <input type="radio" class="btn-check" name="status"
                                                                id="status0_{{ $items->pembelian_status }}"
                                                                value="beli" autocomplete="off"
                                                                {{ $items->pembelian_status == 'beli' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-warning"
                                                                for="status0_{{ $items->pembelian_status }}">Beli</label>
                                                            <input type="radio" class="btn-check" name="status"
                                                                id="status1_{{ $items->pembelian_status }}"
                                                                value="proses" autocomplete="off"
                                                                {{ $items->pembelian_status == 'proses' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-info"
                                                                for="status1_{{ $items->pembelian_status }}">Proses</label>
                                                            <input type="radio" class="btn-check" name="status"
                                                                id="status2_{{ $items->pembelian_status }}"
                                                                value="selesai" autocomplete="off"
                                                                {{ $items->pembelian_status == 'selesai' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-success"
                                                                for="status2_{{ $items->pembelian_status }}">Selesai</label>
                                                            <input type="radio" class="btn-check" name="status"
                                                                id="status3_{{ $items->pembelian_status }}"
                                                                value="batal" autocomplete="off"
                                                                {{ $items->pembelian_status == 'batal' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-danger"
                                                                for="status3_{{ $items->pembelian_status }}">Batal</label>
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
                                    id="deletePembelianModal_{{ $items->pembelian_id }}" tabindex="-1"
                                    aria-labelledby="deletePembelianModalLabel_{{ $items->pembelian_id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5"
                                                    id="deletePembelianModalLabel_{{ $items->pembelian_id }}">
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
                                                    action="{{ route('data_pembelian.delete', ['pembelian_id' => $items->pembelian_id]) }}"
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
                        </tbody>
                    </table>
                    {{ $data_pembelian->links('vendor.pagination.bootstrap-5') }}
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
