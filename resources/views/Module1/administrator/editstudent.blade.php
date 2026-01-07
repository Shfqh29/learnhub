@extends('Module1.administrator.layout')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Update Student Status</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('administrator.students.update', $student->id) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')

        {{-- Name (readonly) --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Name</label>
            <input type="text" value="{{ $student->name }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
        </div>

        {{-- Email (readonly) --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" value="{{ $student->email }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
        </div>

        {{-- Form (editable) --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Form</label>
            <select name="form" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="Form 1" {{ $student->form == 'Form 1' ? 'selected' : '' }}>Form 1</option>
                <option value="Form 2" {{ $student->form == 'Form 2' ? 'selected' : '' }}>Form 2</option>
                <option value="Form 3" {{ $student->form == 'Form 3' ? 'selected' : '' }}>Form 3</option>
                <option value="Form 4" {{ $student->form == 'Form 4' ? 'selected' : '' }}>Form 4</option>
                <option value="Form 5" {{ $student->form == 'Form 5' ? 'selected' : '' }}>Form 5</option>
            </select>
        </div>


        {{-- Status (editable) --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ $student->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
            Update Status
        </button>
    </form>
</div>
@endsection

