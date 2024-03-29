<?php

namespace Tests\Bundles\Product\Feature;

use App\Bundles\Product\Models\Product;
use App\Bundles\User\Models\Permission;
use App\Bundles\Vocab\Models\SizeOfUnit;
use App\Bundles\Vocab\Models\VocabCurrency;
use App\Bundles\Vocab\Models\VocabGosStandard;
use App\Bundles\Vocab\Models\VocabPackageType;
use App\Bundles\Vocab\Models\VocabQuantityUnit;
use App\Based\Support\RuFaker;
use Illuminate\Http\Response;
use Tests\BaseTestCase;

final class ProductTest extends BaseTestCase
{
    private const API_INDEX = '/api/products' . DIRECTORY_SEPARATOR;

    /**
     * @covers \App\Bundles\Product\Http\Controllers\ProductController::index
     */
    public function testGetAll(): void
    {
        $this->actingAsUserWithPermission(Permission::PRODUCTS_LIST_VIEW);

        $response = $this->getJson(static::API_INDEX);
        $decode = $response->decodeResponseJson();

        $response->assertOk();

        self::assertNotEmpty($decode);
        self::assertIsArray($decode);
        self::assertArrayHasKey('data', $decode);
        self::assertGreaterThan(0, $decode['data']);
    }

    /**
     * @covers \App\Bundles\Product\Http\Controllers\ProductController::store
     */
    public function testStore(): void
    {
        $this->actingAsUserWithPermission(Permission::PRODUCTS_CREATE);

        $data = $this->makeData();

        $response = $this->postJson(static::API_INDEX, $data)
            ->assertCreated();

        foreach ($data as $key => $value) {
            self::assertEquals($response[$key], $value);
        }

        //

        $data[Product::FIELD_GOS_STANDARD_ID] = null;

        $this->postJson(static::API_INDEX, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdate(): void
    {
        $this->actingAsUserWithPermission(Permission::PRODUCTS_UPDATE);

        $product = Product::query()->create($this->makeData());
        $data = $this->makeData();

        $response = $this->putJson(static::API_INDEX . $product->id, $data);
        $response->assertOk();
    }

    /**
     * @covers \App\Bundles\Product\Http\Controllers\ProductController::destroy
     */
    public function testDestroy(): void
    {
        $this->actingAsUserWithPermission(Permission::PRODUCTS_DELETE);

        $product = Product::query()->create($this->makeData());

        $response = $this->deleteJson(static::API_INDEX . $product->id)
            ->assertOk()
            ->decodeResponseJson();

        self::assertTrue($response['success']);
        self::assertFalse(Product::query()->where('id', $product->id)->exists());
    }

    private function makeData(): array
    {
        return [
            Product::FIELD_NAME => RuFaker::product(),
            Product::FIELD_NAME_FOR_DOCUMENT => RuFaker::product(),
            Product::FIELD_PRICE => $this->getFaker()->randomFloat(),
            Product::FIELD_SIZE => $this->getFaker()->randomFloat(),
            Product::FIELD_CURRENCY_ID => factory(VocabCurrency::class)->create()->id,
            Product::FIELD_SIZE_OF_UNIT_ID => factory(SizeOfUnit::class)->create()->id,
            Product::FIELD_QUANTITY_UNIT_ID => factory(VocabQuantityUnit::class)->create()->id,
            Product::FIELD_PACKAGE_TYPE_ID => factory(VocabPackageType::class)->create()->id,
            Product::FIELD_GOS_STANDARD_ID => factory(VocabGosStandard::class)->create()->id,
        ];
    }

    /**
     * @covers ProductController::topChart
     */
    public function testTopChart(): void
    {
        $this->actingAsUserWithPermission(Permission::PRODUCTS_LIST_VIEW);

        $request = function () {
            return $this->getJson(static::API_INDEX . 'top-chart');
        };

        $request()->assertOk();
    }

    /**
     * @covers \App\Bundles\Product\Http\Controllers\ProductController::refreshTopChart
     */
    public function testRefreshTopChart(): void
    {
        $this->actingAsUserWithPermission(Permission::PRODUCTS_LIST_VIEW);

        $request = function () {
            return $this->getJson(static::API_INDEX . 'refresh-top-chart');
        };

        $request()->assertOk();
    }
}
