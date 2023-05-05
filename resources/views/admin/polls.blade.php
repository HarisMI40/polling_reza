@extends('layout.layoutadmin')

@section('title')
    Pollings
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($polls as $poll)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-center">
                            <h4 class="fw-bold">{{ $poll['title'] }}</h4>
                        </div>

                        <div class="card-body">
                            @if ($poll['voted'])
                                <span class="badge bg-success text-white mb-3">Kamu sudah voting ini</span> <br>
                            @else
                                <span class="badge bg-warning text-black mb-3">Ayo vote sekarang juga !</span> <br>
                            @endif
                            <small>- {{ $poll['votes_count'] }} Orang sudah vote</small> <br>
                            <small>- Polling ini memilik {{ $poll['choises_count'] }} pilihan</small> <br>
                            <small>- Deadline : {{ $poll['deadline'] }}</small>

                            <div class="d-flex justify-content-end">
                                {{-- <a href="/admin/polling/{{ $poll['id'] }}" class="btn btn-primary btn-sm mt-3">Lihat</a> --}}
                                <a href="{{ url('/admin/polling/' . $poll['id']) }}"
                                    class="btn btn-primary btn-sm mt-3">Lihat</a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
