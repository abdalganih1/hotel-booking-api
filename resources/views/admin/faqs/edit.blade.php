@extends('admin.layouts.app')

@section('title', __('Edit FAQ: :question', ['question' => Str::limit($faq->question, 50)]))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit FAQ') }}</h1>
            <a href="{{ route('admin.panel.faqs.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to FAQs List') }}
            </a>
        </div>

        <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
            <form action="{{ route('admin.panel.faqs.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <x-input-label for="question" :value="__('Question')" />
                        <textarea id="question" name="question" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('question', $faq->question) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('question')" />
                    </div>
                    <div>
                        <x-input-label for="answer" :value="__('Answer')" />
                        <textarea id="answer" name="answer" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('answer', $faq->answer) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('answer')" />
                    </div>
                </div>
                <div class="pt-8 flex justify-end">
                    <a href="{{ route('admin.panel.faqs.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update FAQ') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection