<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('is_published', true)->latest()->paginate(6);

        $avgRating = Review::where('is_published', true)->avg('rating');
        $totalReviews = Review::where('is_published', true)->count();

        return view('ulasan', compact('reviews', 'avgRating', 'totalReviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'role_label' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $validated['is_published'] = true;

        Review::create($validated);

        return redirect()->route('ulasan')->with('success', 'Terima kasih! Ulasan kamu berhasil dikirim.');
    }
}