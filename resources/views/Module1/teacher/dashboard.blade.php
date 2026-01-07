@extends('Module1.teacher.layout')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Dashboard</h1>

    <div class="bg-white rounded-xl shadow p-6 md:p-8">
        {{-- Month Navigation --}}
        <div class="flex justify-between items-center mb-4">
            <button id="prevMonth" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-lg">&lt;</button>
            <h2 id="monthYear" class="text-2xl font-bold text-center"></h2>
            <button id="nextMonth" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-lg">&gt;</button>
        </div>

        {{-- Days header --}}
        <div class="grid grid-cols-7 gap-2 text-center font-semibold text-gray-700">
            <div>Sun</div><div>Mon</div><div>Tue</div>
            <div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
        </div>

        {{-- Calendar days --}}
        <div id="calendarDays" class="grid grid-cols-7 gap-2 mt-2 text-center"></div>
    </div>
</div>

<script>
    const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    let currentDate = new Date();

    function renderCalendar(date) {
        const calendarDays = document.getElementById('calendarDays');
        const monthYear = document.getElementById('monthYear');
        calendarDays.innerHTML = '';

        const year = date.getFullYear();
        const month = date.getMonth();
        monthYear.textContent = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Add blank slots for first day of the month
        for (let i = 0; i < firstDay; i++) {
            const blank = document.createElement('div');
            calendarDays.appendChild(blank);
        }

        // Add actual days
        for (let day = 1; day <= daysInMonth; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.textContent = day;
            dayDiv.className = 'p-4 rounded-lg w-full flex items-center justify-center';

            // Highlight current day
            const today = new Date();
            if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                dayDiv.classList.add('bg-blue-500', 'text-white', 'font-bold');
            } else {
                dayDiv.classList.add('hover:bg-gray-200', 'cursor-pointer');
            }

            calendarDays.appendChild(dayDiv);
        }
    }

    document.getElementById('prevMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    // Initial render
    renderCalendar(currentDate);
</script>
@endsection
