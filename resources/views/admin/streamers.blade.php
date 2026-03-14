@extends('admin.layouts.app')

@section('title', '主播管理')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>主播列表</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>主播名称</th>
                        <th>所属用户</th>
                        <th>抖音链接</th>
                        <th>状态</th>
                        <th>监控</th>
                        <th>最后检测</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($streamers as $streamer)
                        <tr>
                            <td>{{ $streamer->id }}</td>
                            <td>{{ $streamer->name }}</td>
                            <td>{{ $streamer->user->name }}</td>
                            <td><a href="{{ $streamer->douyin_url }}" target="_blank">查看</a></td>
                            <td>
                                <span class="badge {{ $streamer->is_live ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ $streamer->is_live ? '直播中' : '未直播' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $streamer->is_monitoring ? 'bg-success' : 'bg-warning' }}">
                                    {{ $streamer->is_monitoring ? '监控中' : '已暂停' }}
                                </span>
                            </td>
                            <td>{{ $streamer->last_check_time ? $streamer->last_check_time->diffForHumans() : '从未' }}</td>
                            <td>
                                <form action="{{ route('admin.streamers.delete', $streamer) }}" method="POST" class="d-inline" onsubmit="return confirm('确定要删除此主播吗？')">
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
        {{ $streamers->links() }}
    </div>
</div>
@endsection