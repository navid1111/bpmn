<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'partner_id' => 'PTR-2026-' . $this->faker->unique()->numberBetween(100, 999),
            'proposal' => $this->faker->sentence(4),
            'category' => $this->faker->randomElement(['Loyalty', 'OTT', 'Content', 'Vas', 'Digital', 'Strategic']),
            'business_type' => $this->faker->randomElement(['Entertainment', 'ART', 'Education', 'Other']),
            'status' => $this->faker->randomElement(['Active', 'Rejected', 'Pending']),
            'invitation_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
