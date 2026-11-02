<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /** Product => [available sizes, typical shelf life in days] */
    private const CATALOG = [
        'Whole milk'      => [['0.5 l', '1 l', '2 l'], 10],
        'Greek yogurt'    => [['150 g', '400 g', '1 kg'], 21],
        'Rye bread'       => [['400 g', '800 g'], 5],
        'Basmati rice'    => [['500 g', '1 kg', '5 kg'], 540],
        'Spaghetti'       => [['500 g', '1 kg'], 720],
        'Olive oil'       => [['0.5 l', '1 l', '5 l'], 540],
        'Cheddar cheese'  => [['200 g', '500 g'], 90],
        'Orange juice'    => [['1 l', '2 l'], 30],
        'Dark chocolate'  => [['100 g', '200 g'], 365],
        'Tomato sauce'    => [['350 g', '680 g'], 730],
        'Free-range eggs' => [['6 pcs', '10 pcs', '30 pcs'], 28],
        'Chicken fillet'  => [['500 g', '1 kg'], 6],
        'Butter'          => [['200 g', '500 g'], 60],
        'Oat flakes'      => [['500 g', '1 kg'], 365],
        'Strawberry jam'  => [['250 g', '450 g'], 540],
    ];

    private const QUALIFIERS = ['Organic', 'Premium', 'Farm', 'Classic', 'Light', 'Family pack'];

    public function definition(): array
    {
        $product = fake()->randomElement(array_keys(self::CATALOG));
        [$sizes, $shelfLifeDays] = self::CATALOG[$product];

        $status = fake()->boolean(70)
            ? ProductStatus::Active
            : fake()->randomElement([ProductStatus::Inactive, ProductStatus::Discontinued]);

        return [
            'name' => sprintf(
                '%s %s %s',
                fake()->randomElement(self::QUALIFIERS),
                $product,
                fake()->randomElement($sizes),
            ),
            'quantity' => $status === ProductStatus::Discontinued ? 0 : fake()->numberBetween(0, 200),
            'description' => fake()->optional(0.8)->sentences(2, true),
            // +/- 30 dienas ap tipisko derīguma termiņu, lai daļa produktu būtu jau novecojuši
            'expiration_date' => now()->addDays($shelfLifeDays + fake()->numberBetween(-30, 30))->toDateString(),
            'status' => $status,
        ];
    }
}