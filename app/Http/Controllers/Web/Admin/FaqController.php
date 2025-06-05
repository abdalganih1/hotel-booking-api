<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the FAQs.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Faq::orderBy('id', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', '%' . $search . '%')
                  ->orWhere('answer', 'like', '%' . $search . '%');
            });
        }

        $faqs = $query->paginate(15);
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'question' => ['required', 'string', 'unique:faqs,question'],
            'answer' => ['required', 'string'],
        ]);

        Faq::create([
            'question' => $validatedData['question'],
            'answer' => $validatedData['answer'],
            'user_id' => Auth::id(), // Assign current admin as creator
        ]);

        return redirect()->route('admin.panel.faqs.index')->with('success', __('FAQ created successfully.'));
    }

    /**
     * Display the specified FAQ.
     */
    public function show(Faq $faq): \Illuminate\View\View
    {
        return view('admin.faqs.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified FAQ.
     */
    public function edit(Faq $faq): \Illuminate\View\View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ in storage.
     */
    public function update(Request $request, Faq $faq): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'question' => ['required', 'string', Rule::unique('faqs', 'question')->ignore($faq->id)],
            'answer' => ['required', 'string'],
        ]);

        $faq->update($validatedData);

        return redirect()->route('admin.panel.faqs.index')->with('success', __('FAQ updated successfully.'));
    }

    /**
     * Remove the specified FAQ from storage.
     */
    public function destroy(Faq $faq): \Illuminate\Http\RedirectResponse
    {
        $faq->delete();
        return redirect()->route('admin.panel.faqs.index')->with('success', __('FAQ deleted successfully.'));
    }
}