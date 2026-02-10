<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\Role;
use App\Models\Membership;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Initialize Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function index()
    {
        $currentUser = Auth::user();

        // Ambil data membership untuk user yang login
        $membership = $currentUser->membership ? [
            'name' => $currentUser->membership->name,
            'start_date' => $currentUser->start_date ? Carbon::parse($currentUser->start_date)->format('d M Y') : 'N/A',
            'end_date' => $currentUser->end_date ? Carbon::parse($currentUser->end_date)->format('d M Y') : 'N/A',
        ] : [
            'name' => 'No Membership',
            'start_date' => 'N/A',
            'end_date' => 'N/A',
        ];

        // Ambil semua membership yang tersedia
        $memberships = Membership::all();

        // Ambil total jumlah untuk semua role, project, task, dan user
        $totalProjects = Project::count();
        $totalUsers = User::count();
        $totalTasks = Task::count();
        $totalRoles = Role::count();

        return view('member.dashboard', compact(
            'totalProjects',
            'totalUsers',
            'totalTasks',
            'totalRoles',
            'membership',
            'memberships'
        ));
    }

    public function purchaseMembership(Request $request)
    {
        $validated = $request->validate([
            'membership_id' => 'required|exists:memberships,id',
        ]);

        $membership = Membership::findOrFail($request->membership_id);
        $user = Auth::user();

        // Handle Free membership (membership_id = 1)
        if ($membership->id == 1) {
            $startDate = Carbon::now();
            $endDate = $membership->valid_days > 0 ? $startDate->copy()->addDays($membership->valid_days) : null;

            Transaction::create([
                'member_id' => $user->id,
                'membership_id' => $membership->id,
                'payment_proof' => null,
                'status' => 'success',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'amount' => 0,
                'transaction_code' => Transaction::generateTransactionCode(),
            ]);

            // Update user's membership
            $user->membership_id = $membership->id;
            $user->start_date = $startDate;
            $user->end_date = $endDate;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Free membership activated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Use payment gateway for Pro or Enterprise plans.']);
    }

    public function generateSnapToken(Request $request)
    {
        $validated = $request->validate([
            'membership_id' => 'required|exists:memberships,id',
            'membership_name' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $membership = Membership::findOrFail($validated['membership_id']);
        $user = Auth::user();

        // Generate unique transaction code
        $transactionCode = Transaction::generateTransactionCode();

        // Create transaction record
        $transaction = Transaction::create([
            'member_id' => $user->id,
            'membership_id' => $membership->id,
            'payment_proof' => null,
            'status' => 'pending',
            'start_date' => null,
            'end_date' => null,
            'amount' => $validated['price'],
            'transaction_code' => $transactionCode,
        ]);

        // Prepare Midtrans transaction details
        $transactionDetails = [
            'order_id' => $transactionCode,
            'gross_amount' => $validated['price'],
        ];

        $itemDetails = [
            [
                'id' => $membership->id,
                'price' => $validated['price'],
                'quantity' => 1,
                'name' => $validated['membership_name'],
            ],
        ];

        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
        ];

        $midtransParams = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            // Generate Snap token
            $snapToken = Snap::getSnapToken($midtransParams);
            return response()->json(['success' => true, 'snapToken' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to generate Snap token: ' . $e->getMessage()]);
        }
    }

    public function handlePayment(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:success,pending,error',
            'payment_result' => 'required|array',
            'membership_id' => 'required|exists:memberships,id',
        ]);

        $user = Auth::user();
        $membership = Membership::findOrFail($validated['membership_id']);
        $transaction = Transaction::where('member_id', $user->id)
            ->where('membership_id', $membership->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'No pending transaction found.']);
        }

        $startDate = Carbon::now();
        $endDate = $membership->valid_days > 0 ? $startDate->copy()->addDays($membership->valid_days) : null;

        // Update transaction based on payment status
        $transaction->status = $validated['status'];
        if ($validated['status'] === 'success') {
            $transaction->start_date = $startDate;
            $transaction->end_date = $endDate;
            $transaction->payment_proof = json_encode($validated['payment_result']); // Store payment result for reference

            // Update user's membership
            $user->membership_id = $membership->id;
            $user->start_date = $startDate;
            $user->end_date = $endDate;
            $user->save();
        }
        $transaction->save();

        return response()->json(['success' => true, 'message' => 'Payment processed successfully.']);
    }
}
