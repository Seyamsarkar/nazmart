<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Helpers\SanitizeInput;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\DeliveryOption;

class DeliveryOptionController extends Controller
{
    CONST BASE_PATH = 'attributes::backend.delivery-option.';

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
        $delivery_manages = DeliveryOption::all();
        return view(self::BASE_PATH.'index', compact('delivery_manages'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:191|unique:delivery_options',
            'sub_title' => 'required|string|max:191',
            'icon' => 'required|string|max:191'
        ]);

        $data['title'] = SanitizeInput::esc_html($data['title']);
        $data['sub_title'] = SanitizeInput::esc_html($data['sub_title']);

        $unit = DeliveryOption::create($data);
        return $unit
            ? back()->with(FlashMsg::create_succeed('Delivery Option'))
            : back()->with(FlashMsg::create_failed('Delivery Option'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|unique:delivery_options,id,'.$request->id,
            'sub_title' => 'required|string|max:191',
            'icon' => 'required|string|max:191'
        ]);

        $data['title'] = SanitizeInput::esc_html($data['title']);
        $data['sub_title'] = SanitizeInput::esc_html($data['sub_title']);

        $unit = DeliveryOption::findOrFail($request->id)->update($data);

        return $unit
            ? back()->with(FlashMsg::update_succeed('Delivery Option'))
            : back()->with(FlashMsg::update_failed('Delivery Option'));
    }

    /**
     * Remove the specified resource from storage.
     * @param DeliveryOption $item
     * @return RedirectResponse
     */
    public function destroy(DeliveryOption $item): RedirectResponse
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Delivery Option'))
            : back()->with(FlashMsg::delete_failed('Delivery Option'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function bulk_action(Request $request): bool
    {
        $units = DeliveryOption::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->delete();
        }
        return true;
    }

    /**
     * Display a listing of the soft deleted resource.
     * @return Renderable
     */
    public function trash_all(): Renderable
    {
        $delivery_manages = DeliveryOption::onlyTrashed()->get();
        return view(self::BASE_PATH.'trash', compact('delivery_manages'));
    }

    public function trash_restore($id)
    {
        $restore = DeliveryOption::withTrashed()->findOrFail($id)->restore();

        return $restore
            ? back()->with(FlashMsg::restore_succeed('Delivery Option'))
            : back()->with(FlashMsg::restore_failed('Delivery Option'));
    }

    public function trash_delete($id)
    {
        $delete = DeliveryOption::withTrashed()->findOrFail($id)->forceDelete();

        return $delete
            ? back()->with(FlashMsg::delete_succeed('Delivery Option'))
            : back()->with(FlashMsg::delete_failed('Delivery Option'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function trash_bulk_action(Request $request): bool
    {
        $units = DeliveryOption::withTrashed()->whereIn('id', $request->ids)->forceDelete();
        return true;
    }
}
