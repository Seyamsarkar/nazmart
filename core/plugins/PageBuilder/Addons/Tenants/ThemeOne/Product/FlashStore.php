<?php

namespace Plugins\PageBuilder\Addons\Tenants\ThemeOne\Product;

use App\Helpers\SanitizeInput;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignProduct;
use Modules\Product\Entities\Product;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class FlashStore extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/brand-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Section Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $campaign = Campaign::where('status', 'publish')->select('id','title')->get()->mapWithKeys(function ($item){
            return [$item->id => $item->title];
        })->toArray();


        $output .= Select::get([
            'name' => 'champaign',
            'label' => __('Select Campaign'),
            'options' => $campaign,
            'value' => $widget_saved_values['champaign'] ?? null,
            'info' => __('you can select your desired campaign or leave it empty')
        ]);

        $output .= Number::get([
            'name' => 'item_show',
            'label' => __('Item Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
        ]);

        // add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $campaign_id = $this->setting_item('champaign');
        $title = SanitizeInput::esc_html($this->setting_item('title') ?? '');
        $item_show = SanitizeInput::esc_html($this->setting_item('item_show') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $campaign = Campaign::find($campaign_id);
        (array) $products_id = $campaign?->products?->pluck('product_id')->toArray();

        if (!empty($products_id))
        {
            $products = Product::whereIn('id',$products_id);

            if(!empty($item_show)){
                $products = $products->take($item_show)->get();
            }else{
                $products = $products->take(4)->get();
            }
        }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title' => $title,
            'products'=> $products ?? [],
            'campaign' => $campaign
        ];

        return self::renderView('tenant.theme_one.product.flash_store_slider',$data);
    }

    public function addon_title()
    {
        return __('Flash Store Slider: 01');
    }
}
