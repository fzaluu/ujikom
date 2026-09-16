<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *//*  */
    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(5_000, 100_000);
        
        // Daftar nama file dummy agar bervariasi
        $dummyPhotos = ['default.png', 'placeholder.jpg', 'item-default.png'];

        return [
            'user_id' => User::where('role_id', 1)->inRandomOrder()->value('id') ?? 1,
            // Mengisi kolom foto dengan salah satu nama file dummy secara acak
            'foto' => $this->faker->randomElement($dummyPhotos), 
            'nama' => $this->faker->words(2, true),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaBeli + $this->faker->numberBetween(5_000, 100_000),
            'stok' => $this->faker->numberBetween(1, 500),
        ];
    }
}