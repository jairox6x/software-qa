<?php

/*
 * @author: Jairo Rodriguez
 *
 * */

namespace Tests\Unit;

use App\Product;
use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

/**
 * Class ProductTest
 * @package Tests\Unit
 */
class ProductTest extends TestCase
{

    /**
     * @test
     */
    public function assert_crea_producto_con_middleware(): void
    {
        $data = [
            'name' => "New Product",
            'description' => "This is a product",
            'units' => 20,
            'price' => 10,
            'image' => "https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
        ];

        $response = $this->json('POST', '/api/products', $data);
        $response->assertStatus(401);
        $response->assertJson(['message' => "Unauthenticated."]);
    }

    /**
     * @test
     */
    public function assert_verifica_se_puede_crear_producto(): void
    {
        $data = [
            'name' => "New Product",
            'description' => "This is a product",
            'units' => 20,
            'price' => 10,
            'image' => "https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
        ];

        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/products', $data);
        $response->assertStatus(200);
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Product Created!"]);
        $response->assertJson(['data' => $data]);
    }


    /**
     * @test
     */
    public function assert_se_obtienen_listado_de_productos(): void
    {
        $response = $this->json('GET', '/api/products');
        $response->assertStatus(200);

        $response->assertJsonStructure(
            [
                [
                    'id',
                    'name',
                    'description',
                    'units',
                    'price',
                    'image',
                    'created_at',
                    'updated_at'
                ]
            ]
        );
    }


    /**
     * @test
     */
    public function assert_se_puede_actualizar_un_producto(): void
    {
        $response = $this->json('GET', '/api/products');
        $response->assertStatus(200);

        $product = $response->getData()[0];

        $user = factory(User::class)->create();
        $update = $this->actingAs($user, 'api')->json('PATCH', '/api/products/' . $product->id, ['name' => "Changed for test"]);
        $update->assertStatus(200);
        $update->assertJson(['message' => "Product Updated!"]);
    }


    /**
     * @test
     */
    public function assert_se_puede_cargar_una_imagen(): void
    {
        $response = $this->json('POST', '/api/upload-file', [
            'image' => UploadedFile::fake()->image('image.jpg')
        ]);
        $response->assertStatus(201);
        $this->assertNotNull($response->getData());
    }


    /**
     * @test
     */
    public function assert_se_puede_eliminar_un_producto(): void
    {
        $response = $this->json('GET', '/api/products');
        $response->assertStatus(200);

        $product = $response->getData()[0];

        $user = factory(User::class)->create();
        $delete = $this->actingAs($user, 'api')->json('DELETE', '/api/products/' . $product->id);
        $delete->assertStatus(200);
        $delete->assertJson(['message' => "Product Deleted!"]);
    }



    /**
     * @test
     */
    public function assert_se_puede_mostrar_un_producto(): void
    {

        $response = $this->json('GET', '/api/products');
        $response->assertStatus(200);

        $product = $response->getData()[0];

        $productResponse = $this->json('GET', '/api/products/' . $product->id);
        $productResponse->assertStatus(200);


    }



    /**
     * @test
     */
    public function assert_muestra_las_ordenes_de_un_producto(): void
    {
        $existing_product = Product::first();

        $response = $this->json('GET', '/api/product/'.$existing_product->id.'/orders');
        $response->assertStatus(200);
    }



    /**
     * @test
     */
    public function assert_retorna_not_found_al_solicitar_las_ordenes_de_un_producto_que_no_existe(): void
    {

        $response = $this->json('GET', '/api/product/' . '90909212' . '/orders');
        $response->assertStatus(404);
    }


}
