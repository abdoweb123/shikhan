<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
=======
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
=======
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
<<<<<<< HEAD
=======
=======
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
<<<<<<< HEAD
    public function definition(): array
=======
<<<<<<< HEAD
    public function definition(): array
=======
    public function definition()
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
<<<<<<< HEAD
            'password' => static::$password ??= Hash::make('password'),
=======
<<<<<<< HEAD
            'password' => static::$password ??= Hash::make('password'),
=======
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
<<<<<<< HEAD
     */
    public function unverified(): static
=======
<<<<<<< HEAD
     */
    public function unverified(): static
=======
     *
     * @return static
     */
    public function unverified()
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
