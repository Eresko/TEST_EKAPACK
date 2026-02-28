<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Order\StatusOrderEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->comment('Id клиента');
            $table->enum('status', [
                StatusOrderEnum::NEW,
                StatusOrderEnum::CONFIRMED,
                StatusOrderEnum::PROCESSING,
                StatusOrderEnum::SHIPPED,
                StatusOrderEnum::COMPLETED,
            ])->default(StatusOrderEnum::NEW)
                ->comment('Статус заказа');
            $table->decimal('total_amount', 10, 2)->comment('Итоговая сумма');
            $table->timestamp('confirmed_at')->nullable()->comment('Дата подтверждения');
            $table->timestamp('shipped_at')->nullable()->comment('Дата отправки');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
