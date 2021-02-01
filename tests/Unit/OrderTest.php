<?php

namespace Tests\Unit;

use App\Order;
use App\User;
use Tests\TestCase;

/**
 * Class OrderTest
 * @package Tests\Unit
 */
class OrderTest extends TestCase
{


    /**
     * @test
     */
    public function assert_se_puede_crear_una_orden(): void
    {
        $data = [
            'product' => 1,
            'quantity' => 20,
            'address' => "No place like home"
        ];


        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/orders', $data);
        $response->assertStatus(200);
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Order Created!"]);
        $response->assertJsonStructure(['data' => [
            'id',
            'product_id',
            'user_id',
            'quantity',
            'address',
            'created_at',
            'updated_at'
        ]]);


        $this->assertCount(1,$user->orders);

    }


    /**
     * @test
     */
    public function assert_obtiene_todas_las_ordenes(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('GET', '/api/orders');
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                [
                    'id',
                    'product_id',
                    'user_id',
                    'quantity',
                    'address',
                    'created_at',
                    'updated_at'
                ]
            ]
        );
    }


    /**
     * @test
     */
    public function assert_marcar_orden_como_atendida(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('GET', '/api/orders');
        $response->assertStatus(200);

        $order = $response->getData()[0];

        $update = $this->actingAs($user, 'api')->json('PATCH', '/api/orders/' . $order->id . "/deliver");
        $update->assertStatus(200);
        $update->assertJson(['message' => "Order Delivered!"]);

        $updatedOrder = $update->getData('data');
        $this->assertTrue($updatedOrder['data']['is_delivered']);
        $this->assertEquals($updatedOrder['data']['id'], $order->id);
    }


    /**
     * @test
     */
    public function assert_se_puede_actualizar_orden(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('GET', '/api/orders');
        $response->assertStatus(200);

        $order = $response->getData()[0];



        $update = $this->actingAs($user, 'api')->json('PATCH', '/api/orders/' . $order->id, ['quantity' => ($order->id + 5)]);
        $update->assertStatus(200);
        $update->assertJson(['message' => "Order Updated!"]);

        $check_order_user = Order::find($order->id);

        $this->assertInstanceOf(User::class, $check_order_user->user, 'Orden pertenece a usuario');
    }




    /**
     * @test
     */
    public function assert_se_puede_borrar_orden(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('GET', '/api/orders');
        $response->assertStatus(200);

        $order = $response->getData()[0];

        $update = $this->actingAs($user, 'api')->json('DELETE', '/api/orders/' . $order->id);
        $update->assertStatus(200);
        $update->assertJson(['message' => "Order Deleted!"]);
    }





    /**
     * @test
     */
    public function assert_crea_una_orden_Y_muestra_el_resultado(): void
    {

        $data = [
            'product' => 1,
            'quantity' => 20,
            'address' => "No place like home"
        ];


        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/orders', $data);
        $response->assertStatus(200);


        $response = $this->actingAs($user, 'api')->json('GET', '/api/orders/'. $response->getData()->data->id);
        $response->assertStatus(200);


    }

}
