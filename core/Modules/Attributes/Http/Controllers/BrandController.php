<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Helpers\SanitizeInput;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Http\Requests\BrandStoreRequest;

class BrandController extends Controller
{
    CONST BASE_PATH = 'attributes::backend.brand.';
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): Renderable
    {
        $delivery_manages = Brand::all();
        return view(self::BASE_PATH.'index', compact('delivery_manages'));
    }

    /**
     * Store a newly created resource in storage.
     * @param BrandStoreRequest $request
     * @return RedirectResponse
     */
    public function store(BrandStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $slug = create_slug($data['slug'] ?? $data['name'], 'Brand', true, 'Attributes');
        $data['slug'] = $slug;
        $data['name'] = SanitizeInput::esc_html($data['name']);
        $data['title'] = isset($data['title']) ? SanitizeInput::esc_html($data['title']) : '';
        $data['description'] = isset($data['description']) ? SanitizeInput::esc_html($data['description']) : '';

        $data['banner_id'] = $data['banner_id'] ?? $data['image_id'];
        $data['url'] = $data['url'] == null ? '#' : $data['url'];
        $data['url'] = SanitizeInput::esc_html($data['url']);

        $brand = Brand::create($data);
        return $brand
            ? back()->with(FlashMsg::create_succeed('Brand'))
            : back()->with(FlashMsg::create_failed('Brand'));
    }

    /**
     * Update the specified resource in storage.
     * @param BrandStoreRequest $request
     * @return RedirectResponse
     */
    public function update(BrandStoreRequest $request): RedirectResponse
    {
        $brand = Brand::findOrFail($request->id);
        $data = $request->validated();
        $data['name'] = SanitizeInput::esc_html($data['name']);
        $data['title'] = isset($data['title']) ? SanitizeInput::esc_html($data['title']) : '';
        $data['description'] = isset($data['description']) ? SanitizeInput::esc_html($data['description']) : '';

        if ($brand->slug != $request->slug)
        {
            $slug = create_slug($data['slug'] ?? $data['name'], 'Brand', true, 'Attributes');
            $data['slug'] = $slug;
        }

        $data['url'] = $data['url'] == null ? '#' : $data['url'];
        $data['url'] = SanitizeInput::esc_html($data['url']);

        $brand->update($data);

        return $brand
            ? back()->with(FlashMsg::update_succeed('Brand'))
            : back()->with(FlashMsg::update_failed('Brand'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Brand $item
     * @return RedirectResponse
     */
    public function destroy(Brand $item): RedirectResponse
    {
        return $item->forceDelete()
            ? back()->with(FlashMsg::delete_succeed('Brand'))
            : back()->with(FlashMsg::delete_failed('Brand'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function bulk_action(Request $request): bool
    {
        $units = Brand::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->forceDelete();
        }
        return true;
    }
}
