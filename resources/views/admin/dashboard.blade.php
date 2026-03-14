@extends('admin.layouts.app')

@section('title', '概览')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">用户总数</h5>
                <p class="card-text display-4">{{ $stats['users'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">主播总数</h5>
                <p class="card-text display-4">{{ $stats['streamers'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">监控中</h5>
                <p class="card-text display-4">{{ $stats['active_streamers'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">监控记录</h5>
                <p class="card-text display-4">{{ $stats['monitor_logs'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>最近注册用户</h5>
            </div>
            <div class="card-body">
                @if($recentUsers->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>用户名</th>
                                <th>邮箱</th>
                                <th>状态</th>
                                <th>注册时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->is_active ? '正常' : '禁用' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center text-muted">暂无用户</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>最近添加主播</h5>
            </div>
            <div class="card-body">
                @if($recentStreamers->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>主播名称</th>
                                <th>所属用户</th>
                                <th>状态</th>
                                <th>添加时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentStreamers as $streamer)
                                <tr>
                                    <td>{{ $streamer->name }}</td>
                                    <td>{{ $streamer->user->name }}</td>
                                    <td>
                                        <span class="badge {{ $streamer->is_live ? 'bg-danger' : 'bg-secondary' }}">
                                            {{ $streamer->is_live ? '直播中' : '未直播' }}
                                        </span>
                                    </td>
                                    <td>{{ $streamer->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center text-muted">暂无主播</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection