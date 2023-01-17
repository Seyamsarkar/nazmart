<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Helpers\SanitizeInput;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Color;

class ColorController extends Controller
{
    private const BASE_PATH = 'attributes::backend.color.';
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-color-list|product-color-create|product-color-edit|product-color-delete', ['only', ['index']]);
        $this->middleware('permission:product-color-create', ['only', ['store']]);
        $this->middleware('permission:product-color-edit', ['only', ['update']]);
        $this->middleware('permission:product-color-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     * @return View|Factory
     */
    public function index(): Factory|View
    {
        $product_colors = Color::all();
        return view(self::BASE_PATH.'all-color', compact('product_colors'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'color_code' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191',
        ]);

        $sluggable_text = $request->slug == null ? Str::slug(trim($request->name)) : Str::slug($request->slug);
        $slug = create_slug($sluggable_text, model_name: 'Color',is_module: true, module_name: 'Attributes');
        $data['slug'] = $slug;

        $product_color = Color::create([
            'name' => SanitizeInput::esc_html($request->name),
            'color_code' => $request->color_code,
            'slug' => $data['slug'],
        ]);

        return $product_color
            ? back()->with(FlashMsg::create_succeed('Product Color'))
            : back()->with(FlashMsg::create_failed('Product Color'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'color_code' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191',
        ]);

        $product_color = Color::findOrFail($request->id);

        if ($product_color->slug != $request->slug)
        {
            $sluggable_text = Str::slug($request->slug ?? $request->name);
            $new_slug = create_slug($sluggable_text, 'Color', true, 'Attributes');
            $request['slug'] = $new_slug;
        }

        $product_color = $product_color->update([
            'name' => SanitizeInput::esc_html($request->name),
            'color_code' => $request->color_code,
            'slug' => $request->slug,
        ]);

        return $product_color
            ? back()->with(FlashMsg::update_succeed('Product Color'))
            : back()->with(FlashMsg::update_failed('Product Color'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $product_color = Color::findOrFail($id);

        return $product_color->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Color'))
            : back()->with(FlashMsg::delete_failed('Product Color'));
    }

    public function bulk_action(Request $request): JsonResponse
    {
        $all_product_colors = Color::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
