<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Member;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['member', 'membership'])->latest()->get();
        $members = Member::all();
        $memberships = Membership::all();
        return view('admin.transactions.index', compact('transactions', 'members', 'memberships'));
    }

    public function create()
    {
        $members = Member::all();
        $memberships = Membership::all();
        return view('admin.transactions.create', compact('members', 'memberships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'membership_id' => 'required|exists:memberships,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Increased size limit and added more formats
        ]);

        $membership = Membership::findOrFail($request->membership_id);

        // Upload bukti pembayaran dengan nama file yang unik
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $paymentProofPath = $file->storeAs('payment_proofs', $fileName, 'public');

            // Verify file was uploaded successfully
            if (!Storage::disk('public')->exists($paymentProofPath)) {
                return redirect()->back()->with('error', 'Failed to upload payment proof. Please try again.');
            }
        }

        Transaction::create([
            'member_id' => $request->member_id,
            'membership_id' => $request->membership_id,
            'payment_proof' => $paymentProofPath,
            'status' => 'pending',
            'start_date' => null,
            'end_date' => null,
            'amount' => $membership->price,
            'transaction_code' => Transaction::generateTransactionCode(),
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil diajukan.');
    }

    public function approve(Transaction $transaction)
    {
        $membership = $transaction->membership;
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays($membership->valid_days);

        $transaction->update([
            'status' => 'approved',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Update member data
        $member = $transaction->member;
        $member->update([
            'membership_id' => $transaction->membership_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'payment_proof' => $transaction->payment_proof,
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil disetujui.');
    }

    public function reject(Transaction $transaction)
    {
        $transaction->update(['status' => 'rejected']);
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi telah ditolak.');
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    // Method untuk menampilkan gambar payment proof
    public function showPaymentProof(Transaction $transaction)
    {
        if (!$transaction->payment_proof || !Storage::disk('public')->exists($transaction->payment_proof)) {
            abort(404, 'Payment proof not found');
        }

        $path = Storage::disk('public')->path($transaction->payment_proof);
        $mimeType = Storage::disk('public')->mimeType($transaction->payment_proof);

        return response()->file($path, [
            'Content-Type' => $mimeType,
        ]);
    }
}
