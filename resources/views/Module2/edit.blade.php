
<h1>Edit Course</h1>
<form action="{{ route('manage_courses.update', $course->id) }}" method="POST">
    @csrf
    @method('PUT')
    <p>Title: <input type="text" name="title" value="{{ $course->title }}" required></p>
    <p>Description: <textarea name="description" required>{{ $course->description }}</textarea></p>
    <p>Category: <input type="text" name="category" value="{{ $course->category }}" required></p>
    <p>Duration (hours): <input type="number" name="duration" value="{{ $course->duration }}" required></p>
    <button type="submit">Update Course</button>
</form>
<a href="{{ route('manage_courses.index') }}">Back</a>