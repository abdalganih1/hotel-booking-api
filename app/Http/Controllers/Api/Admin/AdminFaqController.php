<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\FaqResource;
// use App\Http\Resources\FaqCollection;

class AdminFaqController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index()
    {
        $faqs = Faq::latest()->get();
        // return new FaqCollection($faqs);
        return response()->json($faqs);
    }

    public function store(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $faq = Faq::create($request->all());
        // return new FaqResource($faq);
        return response()->json($faq, 201);
    }

    public function show(Faq $faq)
    {
        // return new FaqResource($faq);
        return response()->json($faq);
    }

    public function update(Request $request, Faq $faq)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'question' => 'sometimes|required|string',
            'answer' => 'sometimes|required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $faq->update($request->all());
        // return new FaqResource($faq);
        return response()->json($faq);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(null, 204);
    }
}