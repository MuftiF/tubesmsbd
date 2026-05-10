@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');

    .pengumuman-wrap * {
        font-family: 'Inter', sans-serif;
    }

    .pengumuman-wrap {
        background: #f8f6f2;
        min-height: 100vh;
        padding: 2rem 1.5rem;
    }

    /* HEADER */
    .lap-header {
        margin-bottom: 2rem;
        position: relative;
        padding-left: 1rem;
        text-align: left;
    }
    .lap-header::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #2d6a4f;
        border-radius: 2px;
    }
    .lap-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e1e1e;
        letter-spacing: -0.3px;
        margin: 0;
    }
    .lap-header p {
        font-size: 0.85rem;
        color: #78716c;
        margin-top: 0.25rem;
    }

    /* ANNOUNCEMENT LIST */
    .announcement-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .announcement-card {
        background: white;
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e7e5e4;
        transition: all 0.2s;
    }
    .announcement-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
    .announcement-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    .announcement-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1c1c1c;
        margin: 0;
    }
    .announcement-date {
        font-size: 0.65rem;
        color: #a8a29e;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .announcement-divider {
        height: 2px;
        background: #eef5f0;
        margin: 0.75rem 0 0.75rem 0;
        border-radius: 2px;
    }
    .announcement-content {
        font-size: 0.8rem;
        color: #57534e;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }
    .announcement-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 0.5rem;
    }
    .badge-date {
        background: #f8f6f2;
        padding: 0.2rem 0.7rem;
        border-radius: 30px;
        font-size: 0.6rem;
        font-weight: 500;
        color: #78716c;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* EMPTY STATE */
    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 3rem 1rem;
        text-align: center;
        color: #a8a29e;
        border: 1px solid #e7e5e4;
    }
    .empty-state .emoji {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        display: block;
    }
    .empty-state p {
        font-weight: 600;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }
    .empty-state span {
        font-size: 0.7rem;
    }
</style>

<div class="pengumuman-wrap">
<div style="max-width:800px;margin:0 auto;">

    <!-- HEADER -->
    <div class="lap-header">
        <h1>Pengumuman</h1>
        <p>Informasi terbaru untuk seluruh pegawai</p>
    </div>

    <!-- LIST PENGUMUMAN -->
    @if($announcements->count())
    <div class="announcement-list">
        @foreach($announcements as $a)
        <div class="announcement-card">
            <div class="announcement-header">
                <h3 class="announcement-title">{{ $a->judul }}</h3>
            </div>
            <div class="announcement-divider"></div>
            <div class="announcement-content">
                {{ $a->isi }}
            </div>
            <div class="announcement-footer">
                <span class="badge-date">
                    <i class="fas fa-clock"></i> {{ $a->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <div class="emoji">📭</div>
        <p>Belum ada pengumuman</p>
        <span>Belum ada pengumuman yang tersedia saat ini</span>
    </div>
    @endif

</div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@endsection