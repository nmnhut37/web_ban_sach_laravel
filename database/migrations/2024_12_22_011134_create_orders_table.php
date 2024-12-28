<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Liên kết với bảng users
            $table->string('order_number')->unique(); // Mã đơn hàng (ORD-xxxxxx)
            $table->string('name'); // Tên người đặt hàng
            $table->string('email'); // Địa chỉ email
            $table->string('phone'); // Số điện thoại
            $table->string('address'); // Địa chỉ giao hàng
            $table->string('note')->nullable(); // Ghi chú đơn hàng
            $table->unsignedInteger('total_amount'); // Tổng số tiền đơn hàng, đổi thành số nguyên
            $table->unsignedInteger('discount_amount')->default(0); // Số tiền giảm giá (nếu có), đổi thành số nguyên
            $table->unsignedInteger('final_amount'); // Tổng số tiền sau khi áp dụng giảm giá, đổi thành số nguyên
            $table->string('payment_method')->default('COD'); // Phương thức thanh toán (COD, MoMo, VNPay)
            $table->string('order_status')->default('pending'); // Trạng thái đơn hàng (chờ xử lý)
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
