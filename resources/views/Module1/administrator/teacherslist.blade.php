@extends('Module1.administrator.layout')

@section('content')
<div class="max-w-4xl mx-auto py-10">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">List of Teachers</h2>
        <a href="{{ route('administrator.addteacher') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
           ✏️ Register New Teacher
        </a>
    </div>

    {{-- Teacher Table --}}
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">NO</th>
                <th class="border border-gray-300 px-4 py-2">NAME</th>
                <th class="border border-gray-300 px-4 py-2">EMAIL</th>
                <th class="border border-gray-300 px-4 py-2">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $teacher->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $teacher->email }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $teacher->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
