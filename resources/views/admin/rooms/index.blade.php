@extends('admin.layouts.app')

@section('title', __('Rooms for :hotel_name', ['hotel_name' => $hotel->name]))

@section('content')
<div class="py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Rooms for :hotel_name', ['hotel_name' => $hotel->name]) }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.panel.hotels.show', $hotel->hotel_id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 shadow-sm whitespace-nowrap">
                ← {{ __('Back to Hotel Details') }}
            </a>
            <a href="{{ route('admin.panel.hotels.rooms.create', $hotel->hotel_id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ __('Add New Room') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Room ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Max Occupancy') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price Per Night') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Services') }}</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($rooms as $room)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->room_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->max_occupancy }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($room->price_per_night, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($room->services, 70) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.panel.rooms.show', $room->room_id) }}" class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out ml-2">
                                {{ __('View') }}
                            </a>
                            <a href="{{ route('admin.panel.rooms.edit', $room->room_id) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.panel.rooms.destroy', $room->room_id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this room?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ __('No rooms found for this hotel.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rooms->hasPages())
    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
    @endif
</div>
@endsection