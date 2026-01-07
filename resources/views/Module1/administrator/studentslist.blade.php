@extends('Module1.administrator.layout')

@section('content')
<div class="max-w-4xl mx-auto py-10">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">List of Students</h2>
    </div>

    {{-- Student Table --}}
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">NO</th>
                <th class="border border-gray-300 px-4 py-2">NAME</th>
                <th class="border border-gray-300 px-4 py-2">EMAIL</th>
                <th class="border border-gray-300 px-4 py-2">FORM</th>
                <th class="border border-gray-300 px-4 py-2">STATUS</th>
                <th class="border border-gray-300 px-4 py-2 text-center">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $student->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $student->email }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $student->form }}</td>
                <td class="border px-4 py-2">
                    <span class="px-2 py-1 rounded text-white
                        {{ $student->status == 'Active' ? 'bg-green-500' : 'bg-gray-500' }}">
                        {{ $student->status }}
                    </span>
                </td>

                <td class="border border-gray-300 px-4 py-2 text-center flex justify-center gap-2">
                    <!-- Edit Button -->
                    <a href="{{ route('administrator.students.edit', $student->id) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition">
                        Edit
                    </a>

                    <!-- Toggle Status Button -->
                    <form action="{{ route('administrator.students.toggleStatus', $student->id) }}" 
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to change this student status?');">
                        @csrf
                        <button type="submit"
                            class="{{ $student->status == 'Active' 
                                ? 'bg-red-600 hover:bg-red-700' 
                                : 'bg-green-600 hover:bg-green-700' }}
                            text-white px-3 py-1 rounded transition">
                            {{ $student->status == 'Active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
