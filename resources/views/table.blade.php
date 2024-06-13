@extends('layouts.template')

@section('content')

<div class="container">
    <div class="shadow card">
        <div class="card-header">
            <h3>Data Mahasiswa</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>rara</td>
                        <td>A</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>riri</td>
                        <td>B</td>
                    </tr>
                </tbody>
                </table>
        </div>
    </div>
</div>

@endsection
