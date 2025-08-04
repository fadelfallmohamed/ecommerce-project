<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_stock()
    {
        $product = Product::factory()->create();
        $stock = Stock::create([
            'product_id' => $product->id,
            'quantity' => 10,
            'alert_quantity' => 5,
            'purchase_price' => 10.50,
            'selling_price' => 19.99,
            'status' => 'in_stock',
        ]);

        $this->assertDatabaseHas('stocks', [
            'product_id' => $product->id,
            'quantity' => 10,
            'status' => 'in_stock',
        ]);
    }

    /** @test */
    public function it_can_add_to_stock()
    {
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $stock->addStock(5, 'Test d\'ajout de stock');
        
        $this->assertEquals(15, $stock->fresh()->quantity);
        $this->assertEquals('in_stock', $stock->fresh()->status);
    }

    /** @test */
    public function it_can_remove_from_stock()
    {
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $stock->removeStock(4, 'Test de retrait de stock');
        
        $this->assertEquals(6, $stock->fresh()->quantity);
    }

    /** @test */
    public function it_cannot_have_negative_stock()
    {
        $stock = Stock::factory()->create(['quantity' => 3]);
        
        $stock->removeStock(5, 'Test de retrait supérieur au stock');
        
        $this->assertEquals(0, $stock->fresh()->quantity);
        $this->assertEquals('out_of_stock', $stock->fresh()->status);
    }

    /** @test */
    public function it_can_detect_low_stock()
    {
        $stock = Stock::factory()->create([
            'quantity' => 3,
            'alert_quantity' => 5
        ]);
        
        $this->assertTrue($stock->isLow());
        $this->assertEquals('low_stock', $stock->fresh()->status);
    }

    /** @test */
    public function it_can_detect_out_of_stock()
    {
        $stock = Stock::factory()->create([
            'quantity' => 0,
            'alert_quantity' => 5
        ]);
        
        $this->assertTrue($stock->isOutOfStock());
        $this->assertEquals('out_of_stock', $stock->fresh()->status);
    }

    /** @test */
    public function it_can_update_quantity_directly()
    {
        $stock = Stock::factory()->create(['quantity' => 10]);
        
        $stock->updateQuantity(20, 'Mise à jour directe');
        
        $this->assertEquals(20, $stock->fresh()->quantity);
        $this->assertNotNull($stock->last_restocked_at);
    }

    /** @test */
    public function it_tracks_who_updated_the_stock()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $stock = Stock::factory()->create(['quantity' => 10]);
        $stock->addStock(5, 'Mise à jour par utilisateur');
        
        $this->assertEquals($user->id, $stock->fresh()->last_updated_by);
    }

    /** @test */
    public function it_can_scope_queries_by_stock_status()
    {
        Stock::factory()->count(3)->create(['status' => 'in_stock']);
        Stock::factory()->count(2)->create(['status' => 'low_stock']);
        Stock::factory()->count(1)->create(['status' => 'out_of_stock']);
        
        $this->assertEquals(3, Stock::inStock()->count());
        $this->assertEquals(2, Stock::lowStock()->count());
        $this->assertEquals(1, Stock::outOfStock()->count());
        $this->assertEquals(3, Stock::needsRestocking()->count()); // low_stock + out_of_stock
    }

    /** @test */
    public function it_has_a_relationship_with_product()
    {
        $product = Product::factory()->create();
        $stock = Stock::factory()->create(['product_id' => $product->id]);
        
        $this->assertInstanceOf(Product::class, $stock->product);
        $this->assertEquals($product->id, $stock->product->id);
    }

    /** @test */
    public function it_has_a_relationship_with_updater()
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create(['last_updated_by' => $user->id]);
        
        $this->assertInstanceOf(User::class, $stock->updatedBy);
        $this->assertEquals($user->id, $stock->updatedBy->id);
    }
}
