<!-- resources/views/logistics/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="p-4 pt-20 pl-60 pr-5">
    <div class="p-6 border border-gray-300 rounded-xl shadow-sm bg-white">
        <h1 class="text-2xl font-bold mb-6">Edit Logistik</h1>
        
        <form action="{{ route('logistics.update', $logistic->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Form input (sama seperti create, tapi dengan value lama) -->
            <div class="mb-4">
                <label class="block text-gray-700">Nama Barang</label>
                <input type="text" name="item_name" value="{{ $logistic->item_name }}" 
                       class="w-full p-2 border rounded">
            </div>
            
            <!-- Tambahkan field lainnya -->
            <div class="mb-4">
                <label class="block text-gray-700">Stok</label>
                <input type="number" name="stock" value="{{ $logistic->stock }}" 
                       class="w-full p-2 border rounded">
            </div>
            
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection