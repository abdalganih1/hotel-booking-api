@extends('admin.layouts.app')
@section('title', 'إدارة الفنادق')
@section('content')
    {{--  العنوان وزر الإضافة --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">قائمة الفنادق</h1>
        <a href="{{ route('admin.panel.hotels.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">إضافة فندق</a>
    </div>
    {{--  جدول الفنادق --}}
    <div class="bg-white shadow overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الفندق</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموقع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المسؤول</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($hotels as $hotel)
                    <tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
    <a href="{{ route('admin.panel.hotels.show', $hotel->hotel_id) }}" class="text-indigo-600 hover:text-indigo-900">
        {{ $hotel->name }}
    </a>
</td>                        <td class="px-6 py-4 whitespace-nowrap">{{ Str::limit($hotel->location, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $hotel->adminUser->username ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.panel.hotels.edit', $hotel->hotel_id) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                            {{--  نموذج الحذف --}}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4">لا توجد فنادق.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $hotels->links() }}</div>
@endsection