<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminFaqController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    /**
     * Display a listing of FAQs.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', '%' . $search . '%')
                  ->orWhere('answer', 'like', '%' . $search . '%');
            });
        }

        $faqs = $query->latest()->paginate($request->get('limit', 15));
        return response()->json($faqs);
    }

    /**
     * Store a newly created FAQ.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => ['required', 'string', Rule::unique('faqs', 'question')],
            'answer' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'user_id' => Auth::id(), // Assign current admin as creator
        ]);

        return response()->json(['faq' => $faq, 'message' => 'FAQ created successfully.'], 201);
    }

    /**
     * Display the specified FAQ.
     * @param Faq $faq
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Faq $faq)
    {
        return response()->json($faq);
    }

    /**
     * Update the specified FAQ.
     * @param Request $request
     * @param Faq $faq
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(), [
            'question' => ['required', 'string', Rule::unique('faqs', 'question')->ignore($faq->id)],
            'answer' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $faq->update($validator->validated());

        return response()->json(['faq' => $faq, 'message' => 'FAQ updated successfully.']);
    }

    /**
     * Remove the specified FAQ.
     * @param Faq $faq
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['message' => 'FAQ deleted successfully.'], 204);
    }
}