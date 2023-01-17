<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Size;

class SizeController extends Controller
{
    private const BASE_PATH = 'attributes::backend.size.';
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-size-list|product-size-create|product-size-edit|product-size-delete', ['only', ['index']]);
        $this->middleware('permission:product-size-create', ['only', ['store']]);
        $this->middleware('permission:product-size-edit', ['only', ['update']]);
        $this->middleware('permission:product-size-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $product_sizes = Size::all();
        return view(self::BASE_PATH.'all-size', compact('product_sizes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'size_code' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191',
        ]);

        $sluggable_text = $request->slug == null ? Str::slug(trim($request->name)) : Str::slug($request->slug);
        $slug = create_slug($sluggable_text, model_name: 'Size',is_module: true, module_name: 'Attributes');
        $data['slug'] = $slug;

        $product_size = Size::create([
            'name' => SanitizeInput::esc_html($request->name),
            'size_code' => SanitizeInput::esc_html($request->size_code),
            'slug' => $data['slug'],
        ]);

        return $product_size
            ? back()->with(FlashMsg::create_succeed('Product Size'))
            : back()->with(FlashMsg::create_failed('Product Size'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'size_code' => 'required|string|max:191',
            'slug' => 'required|string|max:191',
        ]);

        $product_size = Size::findOrFail($request->id);

        if ($product_size->slug != $request->slug)
        {
            $sluggable_text = Str::slug($request->slug ?? $request->name);
            $new_slug = create_slug($sluggable_text, 'Size', true, 'Attributes');
            $request['slug'] = $new_slug;
        }

        $product_size = $product_size->update([
            'name' => SanitizeInput::esc_html($request->name),
            'size_code' => SanitizeInput::esc_html($request->size_code),
            'slug' => $request->slug,
        ]);

        return $product_size
            ? back()->with(FlashMsg::update_succeed('Product Size'))
            : back()->with(FlashMsg::update_failed('Product Size'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $product_size = Size::findOrFail($id);

        return $product_size->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Size'))
            : back()->with(FlashMsg::delete_failed('Product Size'));
    }

    public function bulk_action(Request $request){
        Size::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
