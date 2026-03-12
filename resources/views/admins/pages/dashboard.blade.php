@extends('admins.layouts.app')

@section('title', 'Admin Dashboard')

@section('css')

@endsection

@section('content')
    <!-- Top Bar -->
    <header class="top-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>

        <div class="user-menu">
            <i class="fas fa-bell" style="font-size: 1.2rem; color: var(--text-secondary);"></i>
            <span class="nav-badge" style="margin-left: 0;">3</span>
            <div class="user-avatar">XP</div>
            <span style="font-weight: 400; font-size: 14px;">X'eriya Ponald</span>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area">
        <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
            <a href="#" class="manage-btn">
                <i class="fas fa-cog"></i>
                Manage
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>$10,540</h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    22.45%
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>$1,056</h3>
                        <p>Orders</p>
                    </div>
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    22.45%
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>$56</h3>
                        <p>Active Sessions</p>
                    </div>
                    <div class="stat-icon sessions">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    2.45%
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>$56</h3>
                        <p>Total Sessions</p>
                    </div>
                    <div class="stat-icon sessions">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    0.45%
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title">Orders Over Time</h2>
                    <select class="time-filter">
                        <option>Last 12 hours</option>
                        <option>Last 24 hours</option>
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                    </select>
                </div>
                <div class="chart-placeholder">
                    <i class="fas fa-chart-line" style="font-size: 3rem; color: #CBD5E1;"></i>
                </div>
            </div>

            <div class="chart-card">
                <h3>Last 7 Days Sales</h3>
                <div class="sales-summary">
                    <div class="sales-item">
                        <span>Monday</span>
                        <span class="sales-value">$1,259</span>
                    </div>
                    <div class="sales-item">
                        <span>Tuesday</span>
                        <span class="sales-value">$1,845</span>
                    </div>
                    <div class="sales-item">
                        <span>Wednesday</span>
                        <span class="sales-value">$2,156</span>
                    </div>
                    <div class="sales-item">
                        <span>Thursday</span>
                        <span class="sales-value">$1,942</span>
                    </div>
                    <div class="sales-item">
                        <span>Friday</span>
                        <span class="sales-value">$2,438</span>
                    </div>
                    <div class="sales-item">
                        <span>Saturday</span>
                        <span class="sales-value">$1,876</span>
                    </div>
                    <div class="sales-item">
                        <span>Sunday</span>
                        <span class="sales-value">$924</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
