<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the FAQs.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $faqs = Faq::paginate($request->get('limit', 15)); // Add pagination
        return response()->json($faqs);
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
    // No store, update, destroy as these are admin-only operations in API
}