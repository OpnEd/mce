<?php

namespace Database\Factories\Quality\Training;

use App\Models\Quality\Training\Certificate;
use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quality\Training\Certificate>
 */
class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'team_id' => Team::factory(),
            'certificate_number' => Certificate::generateCertificateNumber(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->text(200),
            'issuer' => 'D-Origin 2.0',
            'issued_at' => $this->faker->dateTime(),
            'valid_until' => $this->faker->optional()->dateTime(),
            'final_score' => $this->faker->numberBetween(60, 100),
            'status' => $this->faker->randomElement(['pending', 'issued', 'revoked']),
            'is_verified' => $this->faker->boolean(20),
            'verification_token' => $this->faker->sha256(),
            'pdf_path' => $this->faker->optional()->filePath(),
            'pdf_filename' => $this->faker->optional()->fileName('pdf'),
            'pdf_size' => $this->faker->optional()->numberBetween(100000, 500000),
            'template_used' => 'default',
            'metadata' => null,
            'notes' => $this->faker->optional()->text(100),
        ];
    }

    public function issued(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'issued',
                'issued_at' => now(),
                'verification_token' => hash('sha256', uniqid()),
            ];
        });
    }

    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'issued_at' => null,
            ];
        });
    }

    public function revoked(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'revoked',
                'notes' => 'Certificate revoked',
            ];
        });
    }

    public function verified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_verified' => true,
            ];
        });
    }
}
