@extends('layouts.app')

@section('title', '控制台')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>我的主播</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStreamerModal">
                    添加主播
                </button>
            </div>
            <div class="card-body">
                @if($streamers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>主播名称</th>
                                    <th>状态</th>
                                    <th>监控</th>
                                    <th>最后检测</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($streamers as $streamer)
                                    <tr>
                                        <td>
                                            <a href="{{ $streamer->douyin_url }}" target="_blank">{{ $streamer->name }}</a>
                                        </td>
                                        <td>
                                            @if($streamer->is_live)
                                                <span class="badge bg-danger">直播中</span>
                                            @else
                                                <span class="badge bg-secondary">未直播</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($streamer->is_monitoring)
                                                <span class="badge bg-success">监控中</span>
                                            @else
                                                <span class="badge bg-warning">已暂停</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $streamer->last_check_time ? $streamer->last_check_time->diffForHumans() : '从未' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.check', $streamer) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info">立即检测</button>
                                            </form>
                                            <form action="{{ route('dashboard.toggle', $streamer) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    {{ $streamer->is_monitoring ? '暂停' : '开启' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('dashboard.delete', $streamer) }}" method="POST" class="d-inline" onsubmit="return confirm('确定要删除吗？')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">删除</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">还没有添加主播，点击上方按钮添加。</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>最近监控记录</h5>
            </div>
            <div class="card-body">
                @if($recentLogs->count() > 0)
                    <div class="list-group">
                        @foreach($recentLogs as $log)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $log->streamer->name }}</strong>
                                    <span class="badge {{ $log->is_live ? 'bg-danger' : 'bg-secondary' }}">
                                        {{ $log->is_live ? '直播中' : '未直播' }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted">暂无监控记录</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addStreamerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">添加主播</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dashboard.add') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">主播名称</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="douyin_url" class="form-label">抖音主页链接</label>
                        <input type="url" class="form-control @error('douyin_url') is-invalid @enderror" id="douyin_url" name="douyin_url" placeholder="https://www.douyin.com/user/..." required>
                        @error('douyin_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">请输入主播的抖音主页链接</small>
                    </div>

                    <button type="submit" class="btn btn-primary">添加</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection