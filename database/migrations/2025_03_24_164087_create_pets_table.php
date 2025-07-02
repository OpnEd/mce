<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)
                ->constrained()
                ->cascadeOnDelete(); // Ensure the customer is a valid record
            $table->string('name', 255);
            $table->enum('species', ['dog', 'cat', 'bird', 'reptile', 'other'])->default('dog'); // dog, cat, bird, reptile, etc.
            $table->enum('gender', ['male', 'female', 'other']); //
            $table->date('birth_date');
            $table->smallInteger('weight')->nullable();
            $table->json('history')->nullable();
            $table->boolean('is_alive')->default(true); // Indicates if the pet is currently alive
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
