@extends('admin.layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">قائمة المستخدمين</h1>
        <a href="{{ route('admin.panel.users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            إضافة مستخدم جديد
        </a>
    </div>

    <div class="bg-white shadow overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعرف</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المستخدم</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم الأول</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">إجراءات</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->user_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->first_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($user->role == 'app_admin') bg-red-100 text-red-800 @elseif($user->role == 'hotel_admin') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                {{ $user->role == 'app_admin' ? 'مدير تطبيق' : ($user->role == 'hotel_admin' ? 'مسؤول فندق' : 'مستخدم عادي') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.panel.users.edit', $user->user_id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">تعديل</a>
                            <form action="{{ route('admin.panel.users.destroy', $user->user_id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا المستخدم؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            لا يوجد مستخدمون لعرضهم.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }} {{--  لعرض روابط التصفح --}}
    </div>
</div>
@endsection