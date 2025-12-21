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
                <th class="border border-gray-300 px-4 py-2">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $teacher->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $teacher->email }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $teacher->status }}</td>
                <td class="border border-gray-300 px-4 py-2 text-center flex justify-center gap-2">
                    <!-- Edit Button -->
                    <a href="{{ route('administrator.teachers.edit', $teacher->id) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition">
                        Edit
                    </a>

                    <!-- Delete Button -->
                    <form action="{{ route('administrator.teachers.destroy', $teacher->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">
                            Delete
                        </button>
                    </form>
                </td>



            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
