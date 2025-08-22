<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Lấy năm được chọn từ request, mặc định là năm hiện tại
            $selectedYear = $request->query('year', now()->year);

            // Lấy danh sách các năm có trong dữ liệu
            $availableYears = Order::selectRaw('YEAR(created_at) as year')
                ->groupBy('year')
                ->orderByDesc('year')
                ->pluck('year');

            // Tính toán thu nhập tháng hiện tại
            $currentMonthSales = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->where('order_status', 'completed')
                ->sum('total_amount');

            // Tính tổng thu nhập dựa trên năm được chọn
            $totalSalesQuery = Order::where('order_status', 'completed');
            if ($selectedYear !== 'all') {
                $totalSalesQuery->whereYear('created_at', $selectedYear);
            }
            $totalSales = $totalSalesQuery->sum('total_amount');

            // Đếm số đơn hàng theo trạng thái
            $processingOrders = Order::where('order_status', 'pending')->count();
            $shippingOrders = Order::where('order_status', 'processing')->count();

            // Lấy dữ liệu cho biểu đồ
            $chartData = $this->layDuLieuBieuDo($selectedYear);

            // Xử lý yêu cầu AJAX
            if ($request->ajax()) {
                return response()->json([
                    'chartData' => $chartData,
                    'totalSales' => $totalSales,
                    'totalOrders' => $processingOrders + $shippingOrders
                ]);
            }

            // Trả về view với đầy đủ dữ liệu
            return view('admin.dashboard', compact(
                'currentMonthSales', 'totalSales', 'processingOrders', 
                'shippingOrders', 'chartData', 'selectedYear', 'availableYears'
            ));
        } catch (\Exception $e) {
            // Xử lý lỗi
            if ($request->ajax()) {
                return response()->json(['error' => 'Có lỗi xảy ra khi lấy dữ liệu. Vui lòng thử lại sau.'], 500);
            }
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lấy dữ liệu. Vui lòng thử lại sau.');
        }
    }

    private function layDuLieuBieuDo($selectedYear)
    {
        // Tạo mảng 12 tháng
        $months = collect(range(1, 12));

        // Truy vấn dữ liệu thu nhập theo tháng
        $incomeQuery = Order::selectRaw('SUM(total_amount) as income, MONTH(created_at) as month')
            ->where('order_status', 'completed')
            ->groupBy('month')
            ->orderBy('month');

        // Truy vấn dữ liệu số lượng đơn hàng theo tháng
        $orderQuery = Order::selectRaw('COUNT(id) as order_count, MONTH(created_at) as month')
            ->where('order_status', 'completed')
            ->groupBy('month')
            ->orderBy('month');

        // Lọc theo năm nếu không phải "tất cả"
        if ($selectedYear !== 'all') {
            $incomeQuery->whereYear('created_at', $selectedYear);
            $orderQuery->whereYear('created_at', $selectedYear);
        }

        $monthlyIncome = $incomeQuery->get();
        $monthlyOrders = $orderQuery->get();

        // Định dạng dữ liệu cho biểu đồ
        return [
            'income' => $months->map(fn($month) => [
                'month' => $month,
                'income' => optional($monthlyIncome->firstWhere('month', $month))->income ?? 0,
            ]),
            'orders' => $months->map(fn($month) => [
                'month' => $month,
                'order_count' => optional($monthlyOrders->firstWhere('month', $month))->order_count ?? 0,
            ])
        ];
    }
}