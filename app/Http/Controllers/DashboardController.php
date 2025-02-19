<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $currentYear = now()->year;  // Lấy năm hiện tại

            // Thu nhập của tháng hiện tại
            $currentMonthSales = Order::whereMonth('created_at', now()->month)
                ->where('order_status', 'completed')  // Đảm bảo chỉ tính các đơn hàng đã hoàn thành
                ->sum('total_amount');

            // Tổng thu nhập từ các đơn hàng đã hoàn thành
            $totalSales = Order::where('order_status', 'completed')
                ->sum('total_amount');

            // Đơn hàng đang xử lý
            $processingOrders = Order::where('order_status', 'pending')->count();

            // Đơn hàng đang vận chuyển
            $shippingOrders = Order::where('order_status', 'processing')->count();

            // Thu nhập theo tháng cho năm hiện tại
            $monthlyIncome = Order::selectRaw('SUM(total_amount) as income, MONTH(created_at) as month')
                ->whereYear('created_at', $currentYear)
                ->where('order_status', 'completed')  // Chỉ lấy các đơn hàng đã hoàn thành
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Số lượng đơn hàng theo tháng cho năm hiện tại
            $monthlyOrders = Order::selectRaw('COUNT(id) as order_count, MONTH(created_at) as month')
                ->whereYear('created_at', $currentYear)
                ->where('order_status', 'completed')  // Chỉ lấy các đơn hàng đã hoàn thành
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Tạo mảng tháng từ 1 đến 12 để đảm bảo tất cả các tháng đều có dữ liệu
            $months = collect(range(1, 12)); // Mảng các tháng (1 đến 12)

            // Chuyển đổi dữ liệu thu nhập tháng thành mảng với giá trị 0 nếu không có dữ liệu cho tháng đó
            $incomeData = $months->map(function ($month) use ($monthlyIncome) {
                $income = $monthlyIncome->firstWhere('month', $month);
                return [
                    'month' => $month,
                    'income' => $income ? $income->income : 0,
                ];
            });

            // Chuyển đổi dữ liệu đơn hàng tháng thành mảng với giá trị 0 nếu không có dữ liệu cho tháng đó
            $orderData = $months->map(function ($month) use ($monthlyOrders) {
                $order = $monthlyOrders->firstWhere('month', $month);
                return [
                    'month' => $month,
                    'order_count' => $order ? $order->order_count : 0,
                ];
            });

            return view('Admin.dashboard', compact(
                'currentMonthSales', 'totalSales', 'processingOrders', 'shippingOrders', 'incomeData', 'orderData'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lấy dữ liệu.');
        }
    }
}
