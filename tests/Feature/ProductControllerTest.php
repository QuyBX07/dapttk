<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $user = \App\Models\Account::factory()->create();
        $this->actingAs($user, 'account'); // Đăng nhập user với guard 'account'
    }
    public function test_get_all_returns_view_with_products()
    {
        // Giả lập sản phẩm có sẵn trong DB
        $products = \App\Models\Product::factory()->count(3)->create();

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewIs('layout.product.content'); // tên view bạn dùng
        $response->assertViewHas('products');
    }

    public function test_get_detail_returns_json()
    {
        $product = \App\Models\Product::factory()->create();

        $response = $this->get("/product/{$product->product_id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'product_id',
                'name',
                'category_id',
                'description',
                'unit',
                'quantity',
                'price',
            ],
        ]);
    }

    public function test_create_stores_product_and_redirects()
    {
        Storage::fake('public');
        $category = \App\Models\Category::factory()->create();

        $file = UploadedFile::fake()->image('product.jpg');

        $formData = [
            'name' => 'Sản phẩm test',
            'category_id' => $category->category_id, // bạn cần có category với id này trong DB hoặc dùng factory
            'description' => 'Mô tả',
            'unit' => 'cái',
            'quantity' => 10,
            'price' => 10000,
            'image' => $file,
        ];

        $response = $this->post('/products/create', $formData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Thêm sản phẩm thành công!');


        // Kiểm tra DB có dữ liệu mới
        $this->assertDatabaseHas('products', [
            'name' => 'Sản phẩm test',
            'category_id' => $category->category_id,
        ]);
    }

    public function test_update_updates_product_and_redirects()
    {
        Storage::fake('public');

        $product = \App\Models\Product::factory()->create();

        $file = UploadedFile::fake()->image('update.jpg');

        $formData = [
            'name' => 'Sản phẩm update',
            'category_id' => $product->category_id,
            'description' => 'Mô tả update',
            'unit' => 'cái',
            'quantity' => 5,
            'price' => 5000,
            'image' => $file,
            'old_image' => null,
        ];

        $response = $this->put("/products/update/{$product->product_id}", $formData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Cập nhật sản phẩm thành công!');


        $this->assertDatabaseHas('products', [
            'product_id' => $product->product_id,
            'name' => 'Sản phẩm update',
        ]);
    }

    public function test_delete_deletes_product_and_redirects()
    {
        $product = \App\Models\Product::factory()->create();

        $response = $this->delete("/products/delete/{$product->product_id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Xóa sản phẩm thành công!');

        $this->assertDatabaseMissing('products', [
            'product_id' => $product->product_id,
        ]);
    }

    // public function test_search_returns_view_with_products()
    // {
    //     $products = \App\Models\Product::factory()->count(3)->create();

    //     $query = $products[0]->name;

    //     $response = $this->get('/search/products?query=' . urlencode($query));

    //     $response->assertStatus(200);
    //     $response->assertViewIs('layout.product.content');
    //     $response->assertViewHas('products');
    // }
}
