<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\IPO;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        elseif ($user->isBuyer()) {
            return $this->buyerDashboard();
        }
        elseif ($user->isSeller()) {
            return $this->sellerDashboard();
        }
        elseif ($user->isIssue_manager()) {
            return $this->issueManagerDashboard();
        }

        abort(403, 'Unauthorized access.');
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_trades' => Trade::count(),
            'revenue' => Transaction::sum('amount'),
        ];
        
        return view('dashboard.admin', compact('stats'));
    }

    private function buyerDashboard()
    {
        $portfolio = auth()->user()->portfolio;
        $recentTrades = \App\Models\Trade::where('buyer_id', auth()->id())
            ->orWhere('seller_id', auth()->id())
            ->latest('trade_time')
            ->take(5)
            ->get();

        return view('dashboard.buyer', compact('portfolio', 'recentTrades'));
    }

    private function sellerDashboard()
    {
        $myAssets = auth()->user()->assets;
        $salesPerformance = [];

        return view('dashboard.seller', compact('myAssets', 'salesPerformance'));
    }

    private function issueManagerDashboard()
    {
        $activeIPOs = IPO::where('status', 'open')->get();
        $upcomingIPOs = IPO::where('status', 'upcoming')->get();
        
        return view('dashboard.issue_manager', compact('activeIPOs', 'upcomingIPOs'));
    }
}