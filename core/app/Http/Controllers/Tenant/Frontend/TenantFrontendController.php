<?php

namespace App\Http\Controllers\Tenant\Frontend;

use App\Helpers\EmailHelpers\VerifyUserMailSend;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Services\CheckoutCouponService;
use App\Mail\AdminResetEmail;
use App\Mail\BasicMail;
use App\Models\Admin;
use App\Models\Newsletter;
use App\Models\OrderProducts;
use App\Models\Page;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\ProductOrder;
use App\Models\ProductReviews;
use App\Models\ProductWishlist;
use App\Models\StaticOption;
use App\Models\User;
use App\Traits\SeoDataConfig;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\SubCategory;
use Modules\Blog\Entities\Blog;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\CountryManage\Entities\State;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductInventoryDetailAttribute;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\ProductUom;
use Modules\ShippingModule\Entities\ShippingMethod;
use Modules\ShippingModule\Entities\ZoneRegion;
use Modules\TaxModule\Entities\CountryTax;
use Modules\TaxModule\Entities\StateTax;

class TenantFrontendController extends Controller
{
    use SEOToolsTrait, SeoDataConfig;

    private const BASE_VIEW_PATH = 'tenant.frontend.';

    public function homepage()
    {
        $id = get_static_option('home_page');
        $page_post = Page::where('id', $id)->first();
        $this->setMetaDataInfo($page_post);

        return view(self::BASE_VIEW_PATH . 'frontend-home')->with([
            'page_post' => $page_post
        ]);
    }

    public function dynamic_single_page($slug)
    {
        $page_post = Page::where('slug', $slug)->first();

        abort_if(empty($page_post), 404);

        $blog_page_slug = get_page_slug(get_static_option('blog_page'), 'blog_page');
        if ($slug === $blog_page_slug) {
            if (tenant()) {
                $sorting = blog_sorting(request());
                $order_by = $sorting['order_by'];
                $order = $sorting['order'];
                $order_type = $sorting['order_type'];

                $blogs = Blog::where('status', 1)->orderBy($order_by, $order)->paginate(get_static_option('blog_page_item_show') ?? 9);

                return view('blog::tenant.frontend.blog.blog-all')->with([
                    'page_post' => $page_post,
                    'blogs' => $blogs,
                    'order_type' => $order_type
                ]);
            }
        }

        $shop_page_slug = get_page_slug(get_static_option('shop_page'), 'shop_page');
        if ($slug === $shop_page_slug) {
            if (tenant()) {
                $product_object = Product::where('status_id', 1)->latest()->paginate(12);
                $categories = Category::whereHas('product_categories')->select('id', 'name', 'slug')->withCount('product_categories')->get();
                $sizes = Size::whereHas('product_sizes')->select('id', 'name', 'size_code', 'slug')->get();
                $colors = Color::select('id', 'name', 'color_code', 'slug')->get();
                $tags = ProductTag::select('tag_name')->distinct()->get();

                $create_arr = request()->all();
                $create_url = http_build_query($create_arr);

                $product_object->url(route('tenant.shop') . '?' . $create_url);
                $product_object->url(route('tenant.shop') . '?' . $create_url);

                $links = $product_object->getUrlRange(1, $product_object->lastPage());
                $current_page = $product_object->currentPage();

                $products = $product_object->items();

                return view('tenant.frontend.shop.all-products')->with([
                    'page_post' => $page_post,
                    'products' => $products,
                    'links' => $links,
                    'current_page' => $current_page,
                    'pagination' => $product_object->withQueryString(),
                    'categories' => $categories,
                    'sizes' => $sizes,
                    'colors' => $colors,
                    'tags' => $tags
                ]);
            }
        }

        $track_page_slug = get_page_slug(get_static_option('track_order'), 'track_order');
        if ($slug === $track_page_slug) {
            if (tenant()) {
                return view('tenant.frontend.shop.track-order');
            }
        }

        $this->setMetaDataInfo($page_post);

        return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
            'page_post' => $page_post
        ]);
    }

    public function shop_page(Request $request)
    {
        $product_object = Product::with('badge', 'campaign_product')
            ->where('status_id', 1);

        if ($request->has('category')) {
            $category = $request->category;
            $product_object->whereHas('category', function ($query) use ($category) {
                return $query->where('slug', $category);
            });
        }

        if ($request->has('size')) {
            $size = $request->size;
            $product_object->whereHas('inventoryDetail', function ($query) use ($size) {
                return $query->where('size', $size);
            });
        }

        if ($request->has('color')) {
            $color = $request->color;
            $product_object->whereHas('inventoryDetail', function ($query) use ($color) {
                return $query->where('color', $color);
            });
        }

        if ($request->has('tag')) {
            $tag = $request->tag;
            $product_object->whereHas('tag', function ($query) use ($tag) {
                return $query->where('tag_name', 'LIKE', "%{$tag}%");
            });
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $min_price = $request->min_price;
            $max_price = $request->max_price;
            $product_object->whereBetween('sale_price', [$min_price, $max_price]);
        }

        if ($request->has('rating')) {
            $rating = $request->rating;

            $product_object->whereHas('reviews', function ($query) use ($rating) {
                $query->where('rating', $rating);
            });
        }

        if ($request->has('sort')) {

            $order = 'desc';
            switch ($request->sort) {
                case 1:
                    $order_by = 'name';
                    break;
                case 2:
                    $order_by = 'rating';
                    break;
                case 3:
                    $order_by = 'created_at';
                    break;
                case 4:
                    $order_by = 'sale_price';
                    $order = 'asc';
                    break;
                case 5:
                    $order_by = 'sale_price';
                    break;
                default:
                    $order_by = 'created_at';
            }

            $product_object->orderBy($order_by, $order);
        } else {
            $product_object->latest();
        }

        $product_object = $product_object->paginate(12)->withQueryString();

        $create_arr = $request->all();
        $create_url = http_build_query($create_arr);


        $product_object->url(route('tenant.shop') . '?' . $create_url);
        $product_object->url(route('tenant.shop') . '?' . $create_url);

        $links = $product_object->getUrlRange(1, $product_object->lastPage());
        $current_page = $product_object->currentPage();

        $products = $product_object->items();

        $grid = view("tenant.frontend.shop.partials.product-partials.grid-products", compact("products", "links", "current_page"))->render();
        $list = view("tenant.frontend.shop.partials.product-partials.list-products", compact("products", "links", "current_page"))->render();
        return response()->json(["list" => $list, "grid" => $grid, 'pagination' => $product_object]);
    }

    public function shop_search(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ]);

        $search = $request->search;

        $product_object = Product::with('badge', 'campaign_product')
            ->where('status_id', 1)
            ->where("name", "LIKE", "%" . $search . "%")
            ->orWhere("sale_price", $search);

        $product_object = $product_object->paginate(30)->withQueryString();

        $create_arr = $request->all();
        $create_url = http_build_query($create_arr);


        $product_object->url(route('tenant.shop') . '?' . $create_url);
        $product_object->url(route('tenant.shop') . '?' . $create_url);

        $links = $product_object->getUrlRange(1, $product_object->lastPage());
        $current_page = $product_object->currentPage();

        $products = $product_object->items();

        $grid = view("tenant.frontend.shop.partials.product-partials.grid-products", compact("products", "links", "current_page"))->render();
        $list = view("tenant.frontend.shop.partials.product-partials.list-products", compact("products", "links", "current_page"))->render();

        if ($request->ajax()) {
            return response()->json(["list" => $list, "grid" => $grid, 'pagination' => $product_object]);
        }

        return view('tenant.frontend.shop.product-single-search', compact('product_object', 'search'));
    }

    public function product_quick_view(Request $request)
    {
        $product = Product::with('badge', 'campaign_product')->findOrFail($request->id);
        $modal_object = view('tenant.frontend.shop.markup_for_controller.quick_view_product_modal', compact('product'))->render();

        return response()->json(['product_modal' => $modal_object]);
    }

    public function product_details($slug)
    {
        extract($this->productVariant($slug));


        $sub_category_arr = json_decode($product->sub_category_id, true);

        // related products
        $product_category = $product?->category?->id;
        $product_id = $product->id;
        $related_products = Product::where('status_id', 1)
            ->whereIn('id', function ($query) use ($product_id, $product_category) {
                $query->select('product_categories.product_id')
                    ->from(with(new ProductCategory())->getTable())
                    ->where('product_id', '!=', $product_id)
                    ->where('category_id', '=', $product_category)
                    ->get();
            })
            ->inRandomOrder()
            ->take(5)
            ->get();


        // sidebar data
        $all_category = ProductCategory::all();
        $all_units = ProductUom::all();
        $maximum_available_price = Product::query()->with('category')->max('price');
        $min_price = request()->pr_min ? request()->pr_min : Product::query()->min('price');
        $max_price = request()->pr_max ? request()->pr_max : $maximum_available_price;
        $all_tags = ProductTag::all();

        return view('tenant.frontend.shop.product_details.product-details', compact(
            'product',
            'related_products',
            'available_attributes',
            'product_inventory_set',
            'additional_info_store',
            'all_category',
            'all_units',
            'maximum_available_price',
            'min_price',
            'max_price',
            'all_tags',
            'productColors',
            'productSizes',
            'setting_text',
        ));
    }

    public function add_to_cart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        if (!empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }


        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }

        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_detail = ProductInventoryDetail::where("id", $cart_data['product_variant'])->first();

                $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_detail->image ?? $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];

            Cart::instance("default")->add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to cart'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }


    public function add_to_wishlist(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if (!Auth::guard("web")->check()) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('If you want to add cart item into wishlist than you have to login first.')
            ]);
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        if (!empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }


        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }

        DB::beginTransaction();
        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_detail = ProductInventoryDetail::where("id", $cart_data['product_variant'])->first();

                $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_detail->image ?? $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];
            $username = Auth::guard("web")->user()->id;

            Cart::instance("wishlist")->restore($username);
            Cart::instance("wishlist")->add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);
            Cart::instance("wishlist")->store($username);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to wishlist'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }


    public function add_to_compare(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'pid_id' => 'nullable',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        if (!empty($product->campaign_product)) {
            $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
            $product = Product::where('id', $request->product_id)->first();

            $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;
        } else {
            $product_left = $product_inventory_details->stock_count ?? $product_inventory->stock_count;
        }


        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be compare. Campaign product stock limit is over!')
            ]);
        }

        DB::beginTransaction();
        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_detail = ProductInventoryDetail::where("id", $cart_data['product_variant'])->first();

                $product_attributes = $product_detail->attribute?->pluck('attribute_value', 'attribute_name', 'inventory_details')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'description' => $product->description,
                    'sku' => $product?->inventory?->sku,
                    'image' => $product_detail->image ?? $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];

            Cart::instance("compare")->add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to compare.'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }

    public function cart_page()
    {
        $cart_data = Cart::instance("default")->content();
        $wishlist = false;

        return view('tenant.frontend.shop.cart.cart_page', compact('cart_data', 'wishlist'));
    }

    public function wishlist_page()
    {
        $username = Auth::guard("web")->user()->id;

        Cart::instance("wishlist")->restore($username);
        $cart_data = Cart::instance("wishlist")->content();
        Cart::instance("wishlist")->store($username);
        $wishlist = true;

        return view('tenant.frontend.shop.cart.cart_page', compact('cart_data', 'wishlist'));
    }

    public function cart_update_ajax(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'variant_id' => 'nullable'
        ]);

        $product_data = Cart::get($request->product_id);
        $product_inventory = ProductInventory::where('product_id', $product_data->id)->first();
        $product_inventory_details = ProductInventoryDetail::where('id', $request->variant_id)->first();

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        $sold_count = CampaignSoldProduct::where('product_id', $product_data->id)->first();
        $product = Product::where('id', $product_data->id)->first();

        $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;

        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }


        DB::beginTransaction();
        try {
            $rowId = $request->product_id;
            $qty = max($request->quantity, 1);

            Cart::update($rowId, ['qty' => $qty]);
            DB::commit();

            $cart_data = Cart::content();
            $markup = view('tenant.frontend.shop.cart.markup_for_controller.cart_products', compact('cart_data'))->render();
            $cart_price_markup = view('tenant.frontend.shop.cart.markup_for_controller.cart_price', compact('cart_data'))->render();

            return response()->json([
                'type' => 'success',
                'msg' => 'Cart is updated',
                'markup' => $markup,
                'cart_price_markup' => $cart_price_markup,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }

    public function cart_clear_ajax()
    {
        Cart::destroy();
        return back();
    }

    public function cart_remove_product_ajax(Request $request): JsonResponse
    {
        $request->validate([
            'product_hash_id' => 'required'
        ]);

        Cart::remove($request->product_hash_id);


        return response()->json([
            'type' => 'success',
            'msg' => 'The product is removed from the cart',
            'empty_cart' => Cart::count() == 0 ? view('tenant.frontend.shop.cart.cart_empty')->render() : ''
        ]);
    }

    public function wishlist_remove_product_ajax(Request $request): JsonResponse
    {
        $request->validate([
            'product_hash_id' => 'required'
        ]);

        $username = Auth::guard("web")->user()->id;

        Cart::instance("wishlist")->restore($username);

        Cart::instance("wishlist")->remove($request->product_hash_id);

        Cart::instance("wishlist")->store($username);


        return response()->json([
            'type' => 'success',
            'msg' => 'The product is removed from the cart',
            'empty_cart' => Cart::count() == 0 ? view('tenant.frontend.shop.cart.cart_empty')->render() : ''
        ]);
    }

    public function wishlist_move_product_ajax(Request $request): JsonResponse
    {
        $request->validate([
            'product_hash_id' => 'required'
        ]);

        $username = Auth::guard("web")->user()->id;

        Cart::instance("wishlist")->restore($username);

        $cartItem = Cart::instance("wishlist")->get($request->product_hash_id);

        Cart::instance("wishlist")->remove($request->product_hash_id);

        Cart::instance("default")->add(['id' => (int)$cartItem->id, 'name' => $cartItem->name, 'qty' => $cartItem->qty, 'price' => $cartItem->price, 'weight' => '0', 'options' => [
            "variant_id" => $cartItem->options->variant_id ?? "",
            "color_name" => $cartItem->options->color_name ?? "",
            "size_name" => $cartItem->options->size_name ?? "",
            "image" => $cartItem->options->image ?? "",
            "attributes" => $cartItem->options->attributes ?? "",
            "used_categories" => $cartItem->options->used_categories ?? "",
        ]]);

        Cart::instance("wishlist")->store($username);

        return response()->json([
            'type' => 'success',
            'msg' => 'The product is removed from the cart',
            'empty_cart' => Cart::count() == 0 ? view('tenant.frontend.shop.cart.cart_empty')->render() : ''
        ]);
    }

    public function buy_now(Request $request)
    {
        $request->validate([
            'pid_id' => 'nullable',
            'product_id' => 'required',
            'quantity' => 'required',
            'product_variant' => 'nullable',
            'selected_size' => 'nullable',
            'selected_color' => 'nullable'
        ]);

        $product_inventory = ProductInventory::where('product_id', $request->product_id)->first();
        if ($request->product_variant) {
            $product_inventory_details = ProductInventoryDetail::where('id', $request->product_variant)->first();
        } else {
            $product_inventory_details = null;
        }

        if ($product_inventory_details && $request->quantity > $product_inventory_details->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        } elseif ($product_inventory && $request->quantity > $product_inventory->stock_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested quantity is not available. Only available quantity is added to cart')
            ]);
        }

        $sold_count = CampaignSoldProduct::where('product_id', $request->product_id)->first();
        $product = Product::where('id', $request->product_id)->first();
        $product_left = $sold_count !== null ? $product->campaign_product->units_for_sale - $sold_count->sold_count : null;

        // now we will check if product left is equal or bigger than quantity than we will check
        if (!($request->quantity <= $product_left) && $sold_count) {
            return response()->json([
                'type' => 'warning',
                'quantity_msg' => __('Requested amount can not be cart. Campaign product stock limit is over!')
            ]);
        }

        DB::beginTransaction();
        try {
            $cart_data = $request->all();
            $product = Product::findOrFail($cart_data['product_id']);

            $sale_price = $product->sale_price;
            $additional_price = 0;

            if ($product->campaign_product) {
                $sale_price = $product?->campaign_product?->campaign_price;
            }

            if ($product_inventory_details) {
                $additional_price = $product_inventory_details->additional_price;
            }

            $final_sale_price = $sale_price + $additional_price;

            $product_image = $product->image_id;
            if ($cart_data['product_variant']) {
                $size_name = Size::find($cart_data['selected_size'])->name ?? '';
                $color_name = Color::find($cart_data['selected_color'])->name ?? '';

                $product_attributes = ProductInventoryDetailAttribute::where('inventory_details_id', $cart_data['product_variant'])
                    ->select('attribute_name', 'attribute_value')
                    ->get()
                    ->pluck('attribute_value', 'attribute_name')
                    ->toArray();

                $options = [
                    'variant_id' => $request->product_variant,
                    'color_name' => $color_name,
                    'size_name' => $size_name,
                    'attributes' => $product_attributes,
                    'image' => $product_image
                ];
            } else {
                $options = ['image' => $product_image];
            }

            $category = $product?->category?->id;
            $subcategory = $product?->subCategory?->id ?? null;

            $options['used_categories'] = [
                'category' => $category,
                'subcategory' => $subcategory
            ];

            Cart::add(['id' => $cart_data['product_id'], 'name' => $product->name, 'qty' => $cart_data['quantity'], 'price' => $final_sale_price, 'weight' => '0', 'options' => $options]);
            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Item added to cart',
                'redirect' => route('tenant.shop.checkout')
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'type' => 'error',
                'error_msg' => __('Something went wrong!')
            ]);
        }
    }

    public function get_state_ajax(Request $request) // Syncs with product shipping and tax rate
    {
        $request->validate([
            'country' => 'required'
        ]);

        $states = State::where('country_id', $request->country)->get();

        $markup = '<option value="">' . __('Select a state') . '</option>';
        foreach ($states as $state) {
            $markup .= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }

        return response()->json([
            'type' => 'success',
            'markup' => $markup,
        ]);
    }

    public function sync_product_total(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'state' => 'nullable'
        ]);

        $country = $request->country;
        $state = $request->state;

        $product_tax = $this->get_product_shipping_tax($request);

        $shipping_zones = ZoneRegion::whereJsonContains('country', $request->country)->get();

        $zone_ids = [];
        foreach ($shipping_zones ?? [] as $zone) {
            $zone_ids[] = $zone->zone_id;
        }

        $shipping_methods = ShippingMethod::whereIn('zone_id', $zone_ids)
            ->orWhere('is_default', 1)->get();
        $default_shipping = $shipping_methods->where('is_default', 1)->first();

        $shipping_tax_markup = view('tenant.frontend.shop.checkout.markup_for_controller.shipping_tax_ajax', compact('product_tax', 'shipping_methods', 'default_shipping', 'country', 'state'))->render();

        return response()->json([
            'type' => 'success',
            'sync_price_total_markup' => $shipping_tax_markup
        ]);
    }

    public function sync_product_total_wth_shipping_method(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'state' => 'nullable',
            'shipping_method' => 'required',
            'total' => 'required'
        ]);

        $selected_shipping_method = ShippingMethod::with('options')->find($request->shipping_method);
        $shipping_charge = $selected_shipping_method?->options?->cost;

        if ($request->total < $selected_shipping_method?->options?->minimum_order_amount) {
            return response()->json([
                'type' => 'error',
                'msg' => site_currency_symbol() . $selected_shipping_method?->options?->minimum_order_amount . ' ' . __('Minimum order amount needed.')
            ]);
        }

        if ($selected_shipping_method?->options?->tax_status == 1) {
            $country_tax = CountryTax::where('country_id', $request->country)->select('tax_percentage')->first();
            if ($request->state !== null) {
                $state_tax = StateTax::where(['country_id' => $request->country, 'state_id' => $request->state])->select('tax_percentage')->first();
            }

            $tax = isset($state_tax) && $state_tax != null ? $state_tax : $country_tax;

            $taxed_shipping_charge = $tax != null ? (($tax->tax_percentage / 100) * $shipping_charge) : $shipping_charge;
            $total = $taxed_shipping_charge + $request->total + $shipping_charge;
        } else {
            $total = $request->total + $shipping_charge;
        }

        return response()->json([
            'type' => 'success',
            'selected_shipping_method' => $selected_shipping_method,
            'total' => round($total, 2)
        ]);
    }

    /** ==============================================================================
     *                              Checkout Page Coupon
     * ============================================================================== */
    public function sync_product_coupon(Request $request)
    {
        $all_cart_items = Cart::content();
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $all_cart_items?->pluck("id")?->toArray())->get();
        $subtotal = Cart::subtotal();

        $coupon_amount_total = CheckoutCouponService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');
        if ($coupon_amount_total == 0) {
            return response()->json(['type' => 'error', 'msg' => 'Please enter a valid coupon code']);
        }

        $data = $this->get_product_shipping_tax_for_coupon(['country' => $request->country, 'state' => $request->state, 'shipping_method' => $request->shipping_method]);

        $product_tax = $data['product_tax'];
        $shipping_cost = $data['shipping_cost'];

        $taxed_price = ((double)$subtotal * $product_tax) / 100;
        $total = $coupon_amount_total + $taxed_price + $shipping_cost;

        // if coupon is valid ProductCoupon,
        // or is shipping coupon

        if ($coupon_amount_total) {
            return response()->json([
                'type' => 'success',
                'coupon_amount' => round($total, 2),
                'coupon_price' => (double)$subtotal - $coupon_amount_total,
                'msg' => __('Coupon applied successfully')
            ]);
        }

        return response()->json(['type' => 'danger', 'coupon_amount' => 0]);
    }

    private function get_product_shipping_tax_for_coupon($request)
    {
        $shipping_cost = 0;
        $product_tax = 0;

        if ($request['state'] && $request['country']) {
            $product_tax = StateTax::where(['country_id' => $request['country'], 'state_id' => $request['state']])->select('id', 'tax_percentage')->first();
            if (!empty($product_tax)) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            }

            if (empty($product_tax)) {
                $product_tax = CountryTax::where('country_id', $request['country'])->select('id', 'tax_percentage')->first();
                if ($product_tax) {
                    $product_tax = $product_tax->toArray()['tax_percentage'];
                }
            }
        } else {
            $product_tax = CountryTax::where('country_id', $request['country'])->select('id', 'tax_percentage')->first();
            if ($product_tax) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            }
        }

        $shipping = ShippingMethod::find($request['shipping_method']);
        $shipping_option = $shipping->options ?? null;

        if ($shipping_option != null && $shipping_option?->tax_status == 1) {
            $shipping_cost = $shipping_option?->cost + (($shipping_option?->cost * $product_tax) / 100);
        } else {
            $shipping_cost = $shipping_option?->cost;
        }

        $data['product_tax'] = $product_tax;
        $data['shipping_cost'] = $shipping_cost;

        return $data;
    }

    private function get_product_shipping_tax($request)
    {
        $product_tax = 0;
        $country_tax = CountryTax::where('country_id', $request->country)->select('id', 'tax_percentage')->first();

        if ($request->state && $request->country) {
            $product_tax = StateTax::where(['country_id' => $request->country, 'state_id' => $request->state])
                ->select('id', 'tax_percentage')->first();

            if (!empty($product_tax)) {
                $product_tax = $product_tax->toArray()['tax_percentage'];
            } else {
                if (!empty($country_tax))
                {
                    $product_tax = $country_tax->toArray()['tax_percentage'];
                }
            }
        } else {
            $product_tax = $country_tax->toArray()['tax_percentage'];
        }

        return $product_tax;
    }

    public function product_review(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required',
            'review_text' => 'required|max:1000'
        ]);

        $user = Auth::guard('web')->user();
        $existing_record = ProductReviews::where(['user_id' => $user->id, 'product_id' => $request->product_id])->select('id')->first();

        if (!$existing_record) {
            $product_review = new ProductReviews();
            $product_review->user_id = $user->id;
            $product_review->product_id = $request->product_id;
            $product_review->rating = $request->rating;
            $product_review->review_text = trim($request->review_text);
            $product_review->save();

            return response()->json(['type' => 'success', 'msg' => 'Your review is submitted']);
        }

        return response()->json(['type' => 'danger', 'msg' => 'Your have already submitted review on this product']);
    }

    public function render_reviews(Request $request)
    {
        $reviews = ProductReviews::with('user')->where('product_id', $request->product_id)->orderBy('created_at', 'desc')->take($request->items)->get();
        $review_markup = view('tenant.frontend.shop.product_details.markup_for_controller.product_reviews', compact('reviews'))->render();

        return response()->json([
            'type' => 'success',
            'markup' => $review_markup
        ]);
    }

    public function wishlist_product(Request $request)
    {

        $wishlist = ProductWishlist::where(['product_id' => $request->product_id, 'user_id' => Auth::guard('web')->user()->id])->first();

        if ($wishlist) {
            $wishlist->delete();
            $msg = 'Product is removed from wishlist';
        } else {
            ProductWishlist::create([
                'user_id' => Auth::guard('web')->user()->id,
                'product_id' => $request->product_id
            ]);
            $msg = 'Product added in wishlist successfully';
        }

        return response()->json(['type' => 'success', 'msg' => $msg]);
    }

    public function compare_product_page(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $session_array = Session::get('products');

        $product_array = [];
        foreach ($session_array ?? [] as $key => $item) {
            $product_array[$key] = Product::with('reviews', 'color', 'sizes', 'inventory')->findOrFail($item);
        }

        return view('tenant.frontend.shop.product-compare', compact('product_array'));
    }

    public function compare_product(Request $request)
    {
        $request->validate([
            'products' => 'required'
        ]);

        $products = explode(',', $request->products);

        foreach ($products as $key => $product) {
            Product::findOrFail($product);
        }

        Session::put('products', $products);

        return response()->json([
            'type' => 'success',
            'msg' => __('Product added in comparison board'),
            'item_count' => count($products),
            'url' => route('tenant.shop.compare.product.page')
        ]);
    }

    public function compare_product_remove(Request $request): bool
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        Cart::instance("compare")->remove($request->product_id);

        return true;
    }

//    From the Admin panel, you have complete control over the whole website.
// You can change the layout, design, and name of the website. Also, order management, blog settings, tax manage, mobile app management, location settings, etc.

    public function subscribe_newsletter(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:191|unique:newsletters'
        ]);
        $verify_token = Str::random(32);
        Newsletter::create([
            'email' => $request->email,
            'verified' => 0,
            'token' => $verify_token
        ]);
        $route = route('tenant.subscriber.verify', $verify_token);
        $msg = __('Verify your email to get all news from ') . get_static_option('site_' . get_user_lang() . '_title') . '<div class="btn-wrap"> <a class="anchor-btn" style="color: green;border: 1px solid green" href="' . $route . '">' . __('verify email') . '</a></div>';

        $message = $msg;
        $subject = __('Verify your email');

        //send verify mail to newsletter subscriber
        try {
            Mail::to($request->email)->send(new BasicMail($message, $subject));
        } catch (\Exception $e) {
            return redirect()->back()->with(ResponseMessage::delete($e->getMessage()));
        }

        return response()->json([
            'msg' => __('Thanks for Subscribe Our Newsletter'),
            'type' => 'success'
        ]);
    }

    public function subscriber_verify(Request $request)
    {
        $newsletter = Newsletter::where('token', $request->token)->first();
        $title = __('Sorry');
        $description = __('your token is expired');
        if (!empty($newsletter)) {
            Newsletter::where('token', $request->token)->update([
                'verified' => 1
            ]);
            $title = __('Thanks');
            $description = __('We are really thankful to you for subscribe our newsletter');

            $markup = '<div style="text-align: center;margin-top: 100px"><h2>'.$title.'</h2>';
            $markup .= '<p>'.$description.'</p></div>';
        }
        return $markup;
    }

    public function showTenantLoginForm()
    {
        if (auth('web')->check()) {
            return redirect()->route('tenant.user.home');
        }
        return view('tenant.frontend.user.login');
    }


    public function ajax_login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'username.required' => __('Username required'),
            'password.required' => __('Password required'),
            'password.min' => __('Password length must be 6 characters')
        ]);
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
            return response()->json([
                'msg' => __('Login Success Redirecting'),
                'type' => 'success',
                'status' => 'valid'
            ]);
        }
        return response()->json([
            'msg' => __('User name and password do not match'),
            'type' => 'danger',
            'status' => 'invalid'
        ]);
    }

    public function showTenantRegistrationForm()
    {
        if (auth('web')->check()) {
            return redirect()->route('tenant.user.home');
        }
        return view('tenant.frontend.user.register');
    }

    protected function tenant_user_create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'username' => ['required', 'string', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::table('users')->insert([
            'name' => $request['name'],
            'email' => $request['email'],
            'country' => $request['country'],
            'city' => $request['city'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $email = get_static_option('tenant_site_global_email');

        try {
            $subject = __('New user registration');
            $message_body = __('New user registered : ') . ' <span class="user-registration">' . $request['name'] . '</span>';
            Mail::to($email)->send(new BasicMail($message_body, $subject));

            Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password]);
            $user = User::where('username', $request['username'])->first();
            VerifyUserMailSend::sendMail($user);

        } catch (\Exception $e) {
            //handle error
        }

        return view('tenant.frontend.user.email-verify');
    }

    public function tenant_logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('tenant.user.login');
    }

    public function showUserForgetPasswordForm()
    {
        return view('tenant.frontend.user.forget-password');
    }

    public function sendUserForgetPasswordMail(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string:max:191'
        ]);
        $user_info = User::where('username', $request->username)->orWhere('email', $request->username)->first();
        if (!empty($user_info)) {
            $token_id = Str::random(30);
            $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
            if (empty($existing_token)) {
                DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);
            }
            $message = '<br>'.__('Here is you password reset link, If you did not request to reset your password just ignore this mail.') . '<br><br> <a class="btn" href="' . route('tenant.user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '" style="color:white;background:gray;">' . __('Click Reset Password') . '</a>';
            $data = [
                'username' => $user_info->username,
                'message' => $message
            ];
            try {
                Mail::to($user_info->email)->send(new AdminResetEmail($data));
            } catch (\Exception $e) {
                return redirect()->back()->with([
                    'msg' => $e->getMessage(),
                    'type' => 'danger'
                ]);
            }

            return redirect()->back()->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success'
            ]);
        }
        return redirect()->back()->with([
            'msg' => __('Your Username or Email Is Wrong!!!'),
            'type' => 'danger'
        ]);
    }

    public function showUserResetPasswordForm($username, $token)
    {
        return view('tenant.frontend.user.reset-password')->with([
            'username' => $username,
            'token' => $token
        ]);
    }

    public function UserResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user_info = User::where('username', $request->username)->first();
        $token_info = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (!empty($token_info)) {
            $user_info->password = Hash::make($request->password);
            $user_info->save();
            return redirect()->route('tenant.user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Went Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function lang_change(Request $request)
    {
        session()->put('lang', $request->lang);
        return redirect()->route('tenant.frontend.homepage');
    }


    public function order_payment_cancel($id)
    {
        $order_details = PaymentLogs::find($id);
        return view('tenant.frontend.payment.payment-cancel')->with(['order_details' => $order_details]);
    }

    public function order_payment_cancel_static()
    {
        return view('tenant.frontend.payment.payment-cancel-static');
    }

    public function plan_order($id)
    {
        $order_details = PricePlan::find($id);
        return view('tenant.frontend.pages.package.order-page')->with([
            'order_details' => $order_details
        ]);
    }

    public function order_confirm($id)
    {
        $order_details = PricePlan::where('id', $id)->first();
        return view('tenant.frontend.pages.package.order-page')->with(['order_details' => $order_details]);
    }


    public function order_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);

        $payment_details = '';
        if (!empty($extract_id)) {
            $payment_details = PaymentLogs::find($extract_id);
        }

        return view('tenant.frontend.payment.payment-success', compact('payment_details'));

    }

    /* -------------------------
       USER EMAIL VERIFY
   -------------------------- */
    public function verify_user_email()
    {
        if (empty(get_static_option('user_email_verify_status'))) {
            return redirect()->route('tenant.user.home');
        }
        return view('tenant.frontend.user.email-verify');
    }

    public function check_verify_user_email(Request $request)
    {
        $this->validate($request, [
            'verify_code' => 'required|string'
        ]);
        $user_info = User::where(['id' => Auth::guard('web')->id(), 'email_verify_token' => $request->verify_code])->first();

        if (is_null($user_info)) {
            return back()->with(['msg' => __('enter a valid verify code'), 'type' => 'danger']);
        }

        $user_info->email_verified = 1;
        $user_info->save();

        return redirect()->route('tenant.user.home');
    }

    public function resend_verify_user_email(Request $request)
    {
        VerifyUserMailSend::sendMail(Auth::guard('web')->user());
        return redirect()->route('tenant.user.email.verify')->with(['msg' => __('Verify mail send'), 'type' => 'success']);
    }


    public function expired_package_redirection()
    {
        $diff = Carbon::parse(tenant()->expire_date)->greaterThan(Carbon::today());

        if (tenant() && $diff) {
            return redirect()->route('tenant.frontend.homepage');
        }
        return view('tenant.frontend.pages.package.expired');
    }


    /* -------------------------
       TENENT ADMIN EMAIL VERIFY
   -------------------------- */
    public function verify_admin_email()
    {
        return view('landlord.auth.verify');
    }

    public function check_verify_admin_email(Request $request)
    {

        $this->validate($request, [
            'verify_code' => 'required|string'
        ]);

        $user_data = tenant()->user()->first();

        if (is_null($user_data)) {
            return back()->with(['msg' => __('enter a valid verify code'), 'type' => 'danger']);
        }

        $user_data->email_verified = 1;
        $user_data->save();

        return redirect()->route('tenant.admin.dashboard');
    }

    public function resend_admin_verify_user_email(Request $request)
    {
        VerifyUserMailSend::sendMail_tenant_admin(Auth::guard('admin')->user());
        return redirect()->route('tenant.admin.email.verify')->with(['msg' => __('Verify mail send'), 'type' => 'success']);
    }

    public function product_by_category_ajax_one(Request $request)
    {
        (string)$markup = '';

        $products = Product::with('badge')->where('status_id', 1);
        $category_id = Category::where('slug', $request->category)->firstOrFail();

        $products_id = ProductCategory::whereIn('category_id', $category_id)->pluck('product_id')->toArray();
        $products->whereIn('id', $products_id);

        $products = $products->orderBy('id', 'desc')
            ->select('id', 'name', 'slug', 'price', 'sale_price', 'badge_id', 'image_id')
            ->take($request->limit ?? 6)
            ->get();

        $tooltip_cart = __('Add to cart');
        $tooltip_wishlist = __('Add to Wishlist');
        $tooltip_compare = __('Add to Compare');
        $tooltip_preview = __('Preview');

        $markup .= '<div class="row">';
        foreach ($products as $product) {
            $img_data = get_attachment_image_by_id($product->image_id, 'grid');
            $img = !empty($img_data) ? $img_data['img_url'] : '';
            $alt = !empty($img_data) ? $img_data['img_alt'] : '';

            $data_info = get_product_dynamic_price($product);
            $campaign_name = $data_info['campaign_name'];
            $regular_price = $data_info['regular_price'];
            $sale_price = $data_info['sale_price'];
            $discount = $data_info['discount'] ?? null;

            if ($discount != null) {
                $discount = '<span class="global-card-thumb-badge-box bg-color-two">' . $discount . '% ' . __('Off') . '</span>';
            }
            $sale_price_markup = amount_with_currency_symbol($sale_price);
            $old_price_markup = $regular_price != null ? amount_with_currency_symbol($regular_price) : '';

            $product_name = Str::words($product->name, 4);

            $rating_markup = render_product_star_rating_markup_with_count($product);

            $route = route('tenant.shop.product.details', $product->slug);
            $options = view('tenant.frontend.shop.partials.product-options', compact('product'))->render();

            $markup .= <<<HTML
            <div class="col-xxl-4 col-lg-6 col-md-6 mt-4">
                                <div class="global-card no-shadow radius-0 pb-0">
                                    <div class="global-card-thumb">
                                        <a href="{$route}"><img class="lazyloads" src="{$img}" alt="{$alt}"> </a>
                                        <div class="global-card-thumb-badge right-side">
                                            {$discount}
                                        </div>

                                        {$options}
                                    </div>
                                    <div class="global-card-contents">
                                        <div class="global-card-contents-flex">
                                            <h5 class="global-card-contents-title"> <a href="{$route}"> {$product_name} </a> </h5>
                                            {$rating_markup}
                                        </div>
                                        <div class="price-update-through mt-3">
                                            <div class="price-update-through mt-3">
                                                <span class="flash-prices color-two"> {$sale_price_markup} </span>
                                                <span
                                                    class="flash-old-prices"> {$old_price_markup} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
HTML;
        }
        $markup .= '</div>';


        return response()->json([
            'markup' => $markup,
            'category' => $request->category
        ]);
    }

    public function product_by_category_ajax_two(Request $request)
    {
        (string)$markup = '';

        $products = Product::with('badge')->where('status_id', 1);

        if ($request->category != 'all') {
            $category_id = Category::where('slug', $request->category)->firstOrFail();

            $products_id = ProductCategory::where('category_id', $category_id->id)->pluck('product_id')->toArray();
            $products->whereIn('id', $products_id);

            $products = $products->orderBy('id', 'desc')
                ->select('id', 'name', 'slug', 'price', 'sale_price', 'badge_id', 'image_id')
                ->take($request->limit ?? 8)
                ->get();
        } else {
            $allId = explode(',', $request->allId);
            $category_id = ProductCategory::whereIn('category_id', $allId)->pluck('product_id')->toArray();

            $products = $products->whereIn('id', $category_id)
                ->orderBy('id', 'desc')
                ->select('id', 'name', 'slug', 'price', 'sale_price', 'badge_id', 'image_id')
                ->take($request->limit ?? 8)
                ->get();
        }

        $markup = view('pagebuilder::tenant.theme_two.product.partials.product_list_markup', compact('products'))->render();

        return response()->json([
            'markup' => $markup,
            'category' => $request->category
        ]);
    }


    public function productQuickViewPage($slug): string
    {
        extract($this->productVariant($slug));

        $sub_category_arr = json_decode($product->sub_category_id, true);

        // related products
        $product_category = $product?->category?->id;
        $product_id = $product->id;
        $related_products = Product::where('status_id', 1)
            ->whereIn('id', function ($query) use ($product_id, $product_category) {
                $query->select('product_categories.product_id')
                    ->from(with(new ProductCategory())->getTable())
                    ->where('product_id', '!=', $product_id)
                    ->where('category_id', '=', $product_category)
                    ->get();
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        // sidebar data
        $all_category = ProductCategory::all();
        $all_units = ProductUom::all();
        $maximum_available_price = Product::query()->with('category')->max('price');
        $min_price = request()->pr_min ? request()->pr_min : Product::query()->min('price');
        $max_price = request()->pr_max ? request()->pr_max : $maximum_available_price;
        $all_tags = ProductTag::all();

        return view('tenant.frontend.shop.product_details.quick-view', compact(
            'product',
            'related_products',
            'available_attributes',
            'product_inventory_set',
            'additional_info_store',
            'all_category',
            'all_units',
            'maximum_available_price',
            'min_price',
            'max_price',
            'all_tags',
            'productColors',
            'productSizes',
            'setting_text',
        ))->render();
    }

    private function productVariant($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(
                'category',
                'tag',
                'color',
                'sizes',
                'inventoryDetail',
                'inventoryDetail.productColor',
                'inventoryDetail.productSize',
                'inventoryDetail.attribute',
                'reviews'
            )
            ->where("status_id", 1)
            ->firstOrFail();

        // get selected attributes in this product ( $available_attributes )
        $inventoryDetails = optional($product->inventoryDetail);
        $product_inventory_attributes = $inventoryDetails->toArray();

        $all_included_attributes = array_filter(array_column($product_inventory_attributes, 'attribute', 'id'));
        $all_included_attributes_prd_id = array_keys($all_included_attributes);

        $available_attributes = [];  // FRONTEND : All displaying attributes
        $product_inventory_set = []; // FRONTEND : attribute store
        $additional_info_store = []; // FRONTEND : $additional info_store

        foreach ($all_included_attributes as $id => $included_attributes) {
            $single_inventory_item = [];
            foreach ($included_attributes as $included_attribute_single) {
                $available_attributes[$included_attribute_single['attribute_name']][$included_attribute_single['attribute_value']] = 1;

                // individual inventory item
                $single_inventory_item[$included_attribute_single['attribute_name']] = $included_attribute_single['attribute_value'];


                if (optional($inventoryDetails->find($id))->productColor) {
                    $single_inventory_item['Color'] = optional(optional($inventoryDetails->find($id))->productColor)->name;
                }

                if (optional($inventoryDetails->find($id))->productSize) {
                    $single_inventory_item['Size'] = optional(optional($inventoryDetails->find($id))->productSize)->name;
                }
            }

            $item_additional_price = optional(optional($product->inventoryDetail)->find($id))->additional_price ?? 0;
            $item_additional_stock = optional(optional($product->inventoryDetail)->find($id))->stock_count ?? 0;
            $image = get_attachment_image_by_id(optional(optional($product->inventoryDetail)->find($id))->image)['img_url'] ?? '';

            $product_inventory_set[] = $single_inventory_item;

            $sorted_inventory_item = $single_inventory_item;
            ksort($sorted_inventory_item);

            $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                'pid_id' => $id, //Info: ProductInventoryDetails id
                'additional_price' => $item_additional_price,
                'stock_count' => $item_additional_stock,
                'image' => $image,
            ];
        }

        $productColors = $product->color->unique();
        $productSizes = $product->sizes->unique();

        $colorAndSizes = $product?->inventoryDetail?->whereNotIn("id", $all_included_attributes_prd_id);


        if (!empty($colorAndSizes)) {
            $product_id = $product_inventory_attributes[0]['id'] ?? $product->id;

            foreach ($colorAndSizes as $inventory) {
                // if this inventory has attributes then it will fire continue statement
                if (in_array($inventory->product_id, $all_included_attributes_prd_id)) {
                    continue;
                }

                $single_inventory_item = [];

                if (optional($inventoryDetails->find($product_id))->color) {
                    $single_inventory_item['Color'] = optional($inventory->productColor)->name;
                }

                if (optional($inventoryDetails->find($product_id))->size) {
                    $single_inventory_item['Size'] = optional($inventory->productSize)->name;
                }

                $product_inventory_set[] = $single_inventory_item;

                $item_additional_price = optional($inventory)->additional_price ?? 0;
                $item_additional_stock = optional($inventory)->stock_count ?? 0;
                $image = get_attachment_image_by_id(optional($inventory)->image)['img_url'] ?? '';

                $sorted_inventory_item = $single_inventory_item;
                ksort($sorted_inventory_item);

                $additional_info_store[md5(json_encode($sorted_inventory_item))] = [
                    'pid_id' => $product_id,
                    'additional_price' => $item_additional_price,
                    'stock_count' => $item_additional_stock,
                    'image' => $image,
                ];
            }
        }

        $available_attributes = array_map(fn($i) => array_keys($i), $available_attributes);


        $setting_text = StaticOption::whereIn('option_name', [
            'product_in_stock_text',
            'product_out_of_stock_text',
            'details_tab_text',
            'additional_information_text',
            'reviews_text',
            'your_reviews_text',
            'write_your_feedback_text',
            'post_your_feedback_text',
        ])->get()->mapWithKeys(fn($item) => [$item->option_name => $item->option_value])->toArray();

        return [
            "available_attributes" => $available_attributes,
            "product_inventory_set" => $product_inventory_set,
            "additional_info_store" => $additional_info_store,
            "productColors" => $productColors,
            "productSizes" => $productSizes,
            "product" => $product,
            "setting_text" => $setting_text,
        ];
    }

    public function campaign($id)
    {
        $campaign = Campaign::findOrFail($id);
        $products = $campaign->products;
        return view('tenant.frontend.shop.campaign', compact('campaign', 'products'));
    }

    public function order_track()
    {
        return view('tenant.frontend.shop.track-order');
    }

    public function order_track_post(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer'
        ]);

        $track = (object)[];
        $order = ProductOrder::where('id', $validated['order_id'])->select('id','payment_status', 'status')->first();
        if (!empty($order))
        {
            $status = '<strong>'.__('Your Payment Status:').'</strong>'.' '.$order->payment_status.'</br>';
            $status .= '<strong>'.__('Your Order Status:').'</strong>'.' '.$order->status;
            $track->message = $status;
            $track->status = true;
        } else {
            $status = 'Sorry! No Information Found Regarding This Order ID';
            $track->message = $status;
            $track->status = false;
        }

        return back()->with('track', $track);
    }

    public function topbar_search(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ]);

        $search = $request->search;

        $product_object = Product::with('badge', 'campaign_product')
            ->where('status_id', 1)
            ->where("name", "LIKE", "%" . $search . "%")
            ->orWhere("sale_price", $search)
            ->take(5)
            ->get();

        $markup = '';
        foreach ($product_object as $item) {
            $data = get_product_dynamic_price($item);
            $campaign_name = $data['campaign_name'];
            $data_regular_price = $data['regular_price'];
            $data_sale_price = $data['sale_price'];

            $sale_price = $data_sale_price;
            $deleted_price = $data_regular_price != null ? amount_with_currency_symbol($data_regular_price) : '';

            $image = render_image_markup_by_attachment_id($item->image_id);
            $markup .= '<li class="product-suggestion-list-item">
                           <a href="' . route('tenant.shop.product.details', $item->slug) . '" class="product-suggestion-list-link">
                              <div class="product-image">' . $image . '</div>
                              <div class="product-info">
                                   <div class="product-info-top">
                                          <h6 class="product-name"> ' . $item->name . ' </h6>
                                   </div>
                                    <div class="product-price mt-2">
                                          <div class="price-update-through">
                                                <span class="flash-price fw-500"> ' . amount_with_currency_symbol($sale_price) . ' </span>
                                                <span class="flash-old-prices"> ' . $deleted_price . ' </span>
                                           </div>
                                    </div>
                               </div>
                           </a>
                     </li>';
        }

        if ($request->ajax()) {
            return response()->json(['product_object' => $product_object, 'markup' => $markup]);
        }
    }

    public function category_products($category_type = null, $slug)
    {
        $type = ['category', 'subcategory', 'child-category'];
        abort_if(!in_array($category_type, $type), 404);

        if ($category_type == 'category') {
            $category = Category::where('slug', $slug)->select('id', 'name')->firstOrFail();
            $products_id = ProductCategory::where('category_id', $category->id)->select('product_id')->pluck('product_id');
        } elseif ($category_type == 'subcategory') {
            $category = SubCategory::where('slug', $slug)->select('id', 'name')->firstOrFail();
            $products_id = ProductSubCategory::where('sub_category_id', $category->id)->select('product_id')->pluck('product_id');
        } else {
            $category = ChildCategory::where('slug', $slug)->select('id', 'name')->firstOrFail();
            $products_id = ProductChildCategory::where('child_category_id', $category->id)->select('product_id')->pluck('product_id');
        }

        $products = Product::whereIn('id', $products_id)->paginate(12);

        abort_if(empty($products), 403);

        return view('tenant.frontend.shop.single_pages.category', ['category' => $category, 'products' => $products]);
    }
}
